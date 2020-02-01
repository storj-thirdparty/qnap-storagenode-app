#!/bin/bash

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
chmod 666 /share/CACHEDEV1_DATA/.qpkg/STORJ/web/config.json
container=storagenode
cmd="docker ps -a --filter name=\"^/${container}$\" | wc -l"
numLines=`eval $cmd`

if [[ $numLines -gt 1 ]]
then
	statuscmd="docker ps -a --filter name=\"^/${container}$\"  | cut -c86-109 "
	status=`eval $statuscmd `

	echo "Container named $container launched <br> "
	echo "$status "
else
	echo "Container named $container not launched  "
fi
