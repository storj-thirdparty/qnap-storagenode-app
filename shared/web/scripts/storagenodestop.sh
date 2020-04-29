#!/bin/bash
# This script Stops the docker image of storagenode and removes it
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
PKGNAME="STORJ"
CONTAINER_NAME=storjlabsSnContainer
LOG="/var/log/$PKGNAME"
echo `date` "Storagenode(${CONTAINER_NAME}) being stopped " >> $LOG
output=`docker stop ${CONTAINER_NAME} 2>&1 `
if [[ "x$output" == "x${CONTAINER_NAME}" ]]
then
	output="Success in stopping storagenode ${CONTAINER_NAME}"
fi
echo $output >> $LOG
echo $output
output=`docker rm -f ${CONTAINER_NAME} 2>&1 `
if [[ "x$output" == "x${CONTAINER_NAME}" ]]
then
	output="Success in removing storagenode ${CONTAINER_NAME} "
fi
echo $output >> $LOG
echo $output

