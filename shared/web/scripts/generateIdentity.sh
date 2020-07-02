#!/bin/bash

#===========================================================================
# Generate Identity
# Authorize Identity
# 
# num of files checks only minimal number of files being present
#===========================================================================

function setupEnv() {
    dirpath=$(dirname "$0")
    export PATH=$PATH:$dirpath
    . common.sh
}
setupEnv 

function logMessage {
    logFile="/var/log/STORJ" 
    echo "$(date)" ": (generateIdentity) $@" >> "$logFile" 
    echo "$@"
}

selfName=$(basename "$0")
scriptDir=$(dirname "$0")
identityPidFileDir=$(dirname "$scriptDir")

logMessage "==== Generate Identity called ($@) ============"
if [[ $# -lt 2 ]] 
then
    logMessage "ERROR($selfName): sufficient params not supplied ($@)"
    logMessage "Usage($selfName): $selfName <IdentityKeyString> <keyBase> "
    exit 1 
fi
identityString="$1"
identityBase=/share/Public/identity
keyBase="$2"


identityLogFile="${identityBase}"/logs/storj_identity.log
identityDirPath="${keyBase}"/storagenode
identityBinary="${identityBase}".bin/identity
identityPidFile="${identityPidFileDir}"/identity.pid
identityKey="${keyBase}"/storagenode/identity.key
if [[ -f $identityKey ]] 
then
    logMessage "Identity key $identityKey already exists" 
    exit 2
fi
logMessage "Launching Identity generation program "
logMessage "Running $identityBinary create storagenode "
mkdir -p "${keyBase}"
$identityBinary create storagenode --identity-dir "${keyBase}"  > ${identityLogFile} 2>&1 & 

BG_PID=$!
echo ${BG_PID} > "${identityPidFile}"
function cleanup {
    rm -f "${identityPidFile}"
}
trap cleanup EXIT

logMessage "$identityBinary launched with PID ${BG_PID}. Going to wait for it to complete"
while  [ -d "/proc/${BG_PID}" ]
do
    sleep 1
done
logMessage "Identity key generation ${identityBinary}:${BG_PID} completed (STEP#1) "

if [[ ! -f $identityKey  ]]
then
    logMessage "ERROR: Identity key not generated on run" 
    exit 3 
fi
count=$(/bin/ls "$identityDirPath" | wc -l)
if [[ $count -lt 4 ]]
then
    logMessage "ERROR: All Identity files not generated on run" 
    #exit 4
fi

logMessage "Authorizing identity using identity key string (IdentityPidRef:${BG_PID}) "
logMessage "Running $identityBinary authorize storagenode $identityString --identity-dir ${keyBase} --signer.tls.revocation-dburl bolt://${keyBase}/revocations.db "
$identityBinary authorize storagenode "$identityString" --identity-dir "${keyBase}" --signer.tls.revocation-dburl bolt://${keyBase}/revocations.db

count=$(/bin/ls "$identityDirPath" | wc -l)
if [[ $count -lt 6 ]]
then
    logMessage "Error: Authorization of Identity Signature has possibly failed (Only $count files found)(IdentityPidRef:${BG_PID})!!"
    exit 5
fi

numBeginCa=$(grep -c BEGIN "${keyBase}"/storagenode/ca.cert)
numBeginId=$(grep -c BEGIN "${keyBase}"/storagenode/identity.cert)

if [[ $numBeginCa -ne 2 ]]
then
    logMessage "Error: Authorization has failed (#begin in CA=$numBeginCa) (IdentityPidRef:${BG_PID}) folder (keybase:$keyBase)"
    exit 6
fi
if [[ $numBeginId -ne 3 ]]
then
    logMessage "Error: Authorization has failed (#begin in ID=$numBeginId) (IdentityPidRef:${BG_PID}) folder (keybase:$keyBase)"
    exit 7
fi
logMessage "Authorization of Identity Signature Completed (STEP #2)(IdentityPidRef:${BG_PID}) folder (keybase:$keyBase)"
logMessage "Identity Generation Successfully completed(IdentityPidRef:${BG_PID})"
logMessage Done
logFile=/share/Public/identity/logs/storj_identity.log
echo > "$logFile"
exit 0
