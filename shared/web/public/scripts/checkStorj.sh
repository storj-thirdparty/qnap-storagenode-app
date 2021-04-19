#!/bin/bash

function setVars() {
    dirpath=$(dirname "$0")
    . "${dirpath}"/resource.sh
}
setVars
#export PATH=$PATH:${SYS_QPKG_INSTALL_PATH}/container-station/bin
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
container=storjlabsSnContainer
cmd="docker ps -a --filter name=\"^/${container}$\" | wc -l"
numLines=$(eval "$cmd")

if [[ $numLines -gt 1 ]]
then
	statuscmd="docker ps -a --filter name=\"^/${container}$\" "
	status=$(eval "$statuscmd")

	echo "Container named $container launched <br> "
	
else
	echo "Container named $container not running "
fi
