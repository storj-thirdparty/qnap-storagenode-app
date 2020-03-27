#!/bin/bash
# This script starts storagenode 
PKGNAME="STORJ"
LOG="/var/log/$PKGNAME"
echo `date` "Storagenode is starting" >> $LOG

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
#IPADDR=$(ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1)
CONTAINER_NAME=storjlabsSnContainer

echo `date` " Starting Storagenode ${CONTAINER_NAME} ---> " >> $LOG
docker ps -a  >> $LOG
cmd="docker run -d --restart no -p ${1}:28967 -p 14002:14002 -e WALLET=${2} -e EMAIL=${3} -e ADDRESS=${1} -e BANDWIDTH=${4}TB -e STORAGE=${5}GB -v ${6}:/app/identity -v ${7}:/app/config --name ${CONTAINER_NAME} storjlabs/storagenode:beta " 
echo "$cmd" >> $LOG
$cmd >> $LOG 2>&1 
echo $output >> $LOG 
echo $output 
output=`docker ps -a `
echo $output >> $LOG 
cat <<< $output

