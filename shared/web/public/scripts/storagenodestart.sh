#!/bin/bash
# This script starts storagenode 
function setupEnv() {
    dirpath=$(dirname "$0")
    export PATH=$PATH:$dirpath
    . common.sh
}
setupEnv 

LOG=$LOGFILE

if [[ $# -lt 5 ]]
then
    msg="Not sufficient parameters to start storage node"
    echo "$msg"
    echo "$msg" >> "$LOG"
    exit 1
fi

#export PATH=$PATH:${SYS_QPKG_INSTALL_PATH}/container-station/bin
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
IPADDR=$(ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1)
PORTADDR=$(sed -e 's#.*:\(\)#\1#' <<< "${1}")
CONTAINER_NAME=storjlabsSnContainer

echo "$(date)" " Starting Storagenode ${CONTAINER_NAME} ---> " >> "$LOG"
docker ps -a  >> "$LOG"
if [[ $# -ge 6 ]]
then
    cmd="docker run -d --restart=always -p ${PORTADDR}:28967 -p ${IPADDR}:14002:14002 -p ${IPADDR}:9000:9000 -e WALLET=${2} -e EMAIL=${6} -e ADDRESS=${1} -e STORAGE=${3}GB -v ${4}:/app/identity -v ${5}:/app/config --name ${CONTAINER_NAME} storjlabs/storagenode:latest "
else
    cmd="docker run -d --restart=always -p ${PORTADDR}:28967 -p ${IPADDR}:14002:14002 -p ${IPADDR}:9000:9000 -e WALLET=${2} -e ADDRESS=${1} -e STORAGE=${3}GB -v ${4}:/app/identity -v ${5}:/app/config --name ${CONTAINER_NAME} storjlabs/storagenode:latest "
fi
 
echo "$cmd" >> "$LOG"
$cmd >> "$LOG" 2>&1 
echo "$output" >> "$LOG" 
echo "$output" 
output=$(docker ps -a | grep "${CONTAINER_NAME}" )
echo "$output" >> "$LOG" 
cat <<< "$output"

