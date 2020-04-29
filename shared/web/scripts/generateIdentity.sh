#!/bin/bash

#===========================================================================
# Generate Identity
# Authorize Identity
# 
# num of files checks only minimal number of files being present
#===========================================================================


function logMessage {
    logFile="/var/log/STORJ" 
    echo `date` ": (generateIdentity) $@" >> $logFile 
    echo $@
}

selfName=`basename $0`
scriptDir=`dirname $0`
identityFileDir=`dirname $scriptDir`

logMessage "==== Generate Identity called ($@) ============"
if [[ $# -lt 1 ]] 
then
    logMessage "ERROR($selfName): sufficient params not supplied ($@)"
    logMessage "Usage($selfName): $selfName <IdentityKeyString>  "
    exit 1 
fi
identityString=$1
user=www
home=/root
identityBase=/share/Public/identity
keyBase=${home}/.local/share/storj/identity

identityLogFile=${identityBase}/logs/storj_identity.log
#identityPidFile=${identityBase}/logs/identity.pid
identityDirPath=${identityBase}/storagenode
identityBinary=${identityBase}.bin/identity

identityPidFile=${identityFileDir}/identity.pid

identityKey=${keyBase}/storagenode/identity.key
caKey=${keyBase}/storagenode/ca.key
fileList="ca.key identity.key ca.cert identity.cert"

if [[ -f $identityKey ]] 
then
    logMessage "Identity key $identityKey already exists" 
    exit 2
fi

logMessage "Launching Identity generation program "
logMessage "Running $identityBinary create storagenode "
$identityBinary create storagenode --identity-dir ${keyBase}  > ${identityLogFile} 2>&1 & 
BG_PID=$!
echo ${BG_PID} > ${identityPidFile}
logMessage "$identityBinary launched with PID ${BG_PID}. Going to wait for it to complete"
while  [ -d "/proc/${BG_PID}" ]
do
    sleep 1
done
logMessage "Identity key generation (${identityBinary}:${BG_PID} completed (STEP#1) "

if [[ ! -f $identityKey  ]]
then
    logMessage "ERROR: Identity key not generated on run" 
    rm ${identityPidFile}
    exit 3 
fi

count=$(/bin/ls $identityDirPath | wc -l)
if [[ $count -lt 4 ]]
then
    logMessage "ERROR: All Identity files not generated on run" 
    #rm ${identityPidFile}
    #exit 4
fi

logMessage "Authorizing identity using identity key string (IdentityPidRef:${BG_PID}) "
logMessage "Running $identityBinary authorize storagenode $identityString --identity-dir /root/.local/share/storj/identity --signer.tls.revocation-dburl bolt:///root/.local/share/storj/identity/revocations.db "
$identityBinary authorize storagenode $identityString --identity-dir /root/.local/share/storj/identity --signer.tls.revocation-dburl bolt:///root/.local/share/storj/identity/revocations.db

count=$(/bin/ls $identityDirPath | wc -l)
if [[ $count -lt 6 ]]
then
    logMessage "Error: Authorization of Identity Signature has possibly failed (Only $count files found)(IdentityPidRef:${BG_PID})!!"
    rm ${identityPidFile}
    exit 5
fi

numBeginCa=`grep -c BEGIN /root/.local/share/storj/identity/storagenode/ca.cert`
numBeginId=`grep -c BEGIN /root/.local/share/storj/identity/storagenode/identity.cert`

if [[ $numBeginCa -ne 2 ]]
then
    logMessage "Error: Authorization has failed (#begin in CA=$numBeginCa) (IdentityPidRef:${BG_PID})"
    rm ${identityPidFile}
    exit 6
fi
if [[ $numBeginId -ne 3 ]]
then
    logMessage "Error: Authorization has failed (#begin in ID=$numBeginId) (IdentityPidRef:${BG_PID})"
    rm ${identityPidFile}
    exit 7
fi
logMessage "Authorization of Identity Signature Completed (STEP #2)(IdentityPidRef:${BG_PID})"
logMessage "Identity Generation Successfully completed(IdentityPidRef:${BG_PID})"
logMessage Done
rm ${identityPidFile}
exit 0
