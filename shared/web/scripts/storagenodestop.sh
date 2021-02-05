#!/bin/bash
# This script Stops the docker image of storagenode and removes it
function setupEnv() {
    dirpath=$(dirname "$0")
    export PATH=$PATH:$dirpath
    . common.sh
}
setupEnv 

export PATH=$PATH:${SYS_QPKG_INSTALL_PATH}/container-station/bin
CONTAINER_NAME=storjlabsSnContainer
LOG=$LOGFILE
echo "$(date)" "Storagenode(${CONTAINER_NAME}) being stopped " >> "$LOG"
#output=$(docker stop ${CONTAINER_NAME} 2>&1 )
output=$(systemctl stop storagenode-update)
if [[ "x$output" == "x${CONTAINER_NAME}" ]]
then
	output="Success in stopping storagenode ${CONTAINER_NAME}"
fi
echo "$output" >> "$LOG"
echo "$output"
#output=$(docker rm -f ${CONTAINER_NAME} 2>&1 )
output=$(systemctl stop storagenode-update)
if [[ "x$output" == "x${CONTAINER_NAME}" ]]
then
	output="Success in removing storagenode ${CONTAINER_NAME} "
fi
echo "$output" >> "$LOG"
echo "$output"

