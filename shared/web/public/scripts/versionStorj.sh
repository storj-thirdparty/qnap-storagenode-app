#!/bin/bash

function setVars() {
    dirpath=$(dirname "$0")
    . "${dirpath}"/resource.sh
}
setVars
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin



statuscmd="docker exec -t storjlabsSnContainer ./storagenode version  | grep Version | awk -F ' ' '{print $2}'"
status="$(eval $statuscmd)"
echo "${status}"

