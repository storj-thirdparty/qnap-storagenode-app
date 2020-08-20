#!/bin/bash

function setVars() {
    dirpath=$(dirname "$0")
    . "${dirpath}"/resource.sh
}
setVars
export PATH=$PATH:${SYS_QPKG_INSTALL_PATH}/container-station/bin
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
container=storjlabsSnContainer



statuscmd="docker exec -it storjlabsSnContainer ./storagenode version "
status=$(eval "$statuscmd")
echo ${status}

