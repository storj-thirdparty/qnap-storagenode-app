#!/usr/local/bin/bash

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
identitySimulator=/tmp/iSimulator.php
identityBinary=/share/Public/
identityDirPath=/share/Public/identity/storagenode
fileList="ca.key identity.key ca.cert identity.cert"
identityKey=${home}/.local/share/storj/identity/storagenode/identity.key
caKey=${home}/.local/share/storj/identity/storagenode/ca.key
runSimulator=0

if [[ -f $identityKey ]] 
then
    logMessage "Identity key $identityKey already exists" 
    exit 2
fi

if [[ $runSimulator -gt 0 ]]
then
    identityBinary=" php /tmp/iSimulator.php "
fi

logMessage "Launching Identity generation program "
logMessage "Running $identityBinary create storagenode "
$identityBinary create storagenode --identity-dir /root/.local/share/storj/identity
logMessage "Identity key generation completed (STEP#1) "

if [[ ! -f $identityKey  ]]
then
    logMessage "ERROR: Identity key not generated on run" 
    exit 3 
fi

count=$(/bin/ls $identityDirPath | wc -l)
if [[ $count -lt 4 ]]
then
    logMessage "ERROR: All Identity files not generated on run" 
    #exit 4
fi

logMessage "Authorizing identity using identity key string "
logMessage "Running $identityBinary authorize storagenode $identityString --identity-dir /root/.local/share/storj/identity --signer.tls.revocation-dburl bolt:///root/.local/share/storj/identity/revocations.db "
$identityBinary authorize storagenode $identityString --identity-dir /root/.local/share/storj/identity --signer.tls.revocation-dburl bolt:///root/.local/share/storj/identity/revocations.db

count=$(/bin/ls $identityDirPath | wc -l)
if [[ $count -lt 6 ]]
then
    logMessage "Error: Authorization of Identity Signature has possibly failed (Only $count files found)!!"
    exit 5
fi

numBeginCa=`grep -c BEGIN /root/.local/share/storj/identity/storagenode/ca.cert`
numBeginId=`grep -c BEGIN /root/.local/share/storj/identity/storagenode/identity.cert`

if [[ $numBeginCa -ne 2 ]]
then
    logMessage "Error: Authorization has failed (#begin in CA=$numBeginCa) "
    exit 6
fi
if [[ $numBeginId -ne 3 ]]
then
    logMessage "Error: Authorization has failed (#begin in ID=$numBeginId) "
    exit 7
fi
logMessage "Authorization of Identity Signature Completed (STEP #2)"
logMessage "Identity Generation Successfully completed"
logMessage Done
exit 0
