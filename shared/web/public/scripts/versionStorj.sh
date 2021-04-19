#!/bin/bash

function setVars() {
    dirpath=$(dirname "$0")
    . "${dirpath}"/resource.sh
}
setVars
#export PATH=$PATH:${SYS_QPKG_INSTALL_PATH}/container-station/bin
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
container=storjlabsSnContainer



statuscmd="docker exec -t storjlabsSnContainer ./storagenode version  | grep Version | awk -F ' ' '{print $2}'"
status=$(eval $statuscmd)
echo ${status}

