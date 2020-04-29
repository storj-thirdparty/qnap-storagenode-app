#!/bin/bash
# This script starts storagenode 
PKGNAME="STORJ"
LOG="/var/log/$PKGNAME"
echo `date` "Storagenode is starting" >> $LOG

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
IPADDR=$(ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1)
PORTADDR=$(sed -e 's#.*:\(\)#\1#' <<< "${1}")
CONTAINER_NAME=storjlabsSnContainer

echo `date` " Starting Storagenode ${CONTAINER_NAME} ---> " >> $LOG
docker ps -a  >> $LOG
cmd="docker run -d --restart no -p ${PORTADDR}:28967 -p ${IPADDR}:14002:14002 -e WALLET=${2} -e EMAIL=${3} -e ADDRESS=${1} -e STORAGE=${4}GB -v ${5}:/app/identity -v ${6}:/app/config --name ${CONTAINER_NAME} storjlabs/storagenode:beta " 
echo "$cmd" >> $LOG
$cmd >> $LOG 2>&1 
echo $output >> $LOG 
echo $output 
output=`docker ps -a `
echo $output >> $LOG 
cat <<< $output

