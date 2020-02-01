#!/bin/bash
# This script starts storagenode 
PKGNAME="StorJ"
LOG="/var/log/$PKGNAME"
echo `date` "Storagenode is starting" >> $LOG

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin
IPADDR=$(ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1)
chmod 666 /share/CACHEDEV1_DATA/.qpkg/STORJ/web/config.json

cmd="docker run -d --restart no -p \"${1}\":28967 -p 14002:14002 -e WALLET=\"${2}\" -e EMAIL=\"${3}\" -e ADDRESS=\"${IPADDR}:${1}\" -e BANDWIDTH=\"${4}TB\" -e STORAGE=\"${5}TB\" -v ${6}:/app/identity -v ${7}:/app/config --name storagenode storjlabs/storagenode:beta "
echo `date` " Starting Storagenode ---> " >> $LOG
docker ps -a  >> $LOG
echo "$cmd" >> $LOG 


docker run -d --restart no -p "${1}":28967 -p 14002:14002 -e WALLET="${2}" -e EMAIL="${3}" -e ADDRESS="${IPADDR}:${1}" -e BANDWIDTH="${4}TB" -e STORAGE="${5}TB" -v ${6}:/app/identity -v ${7}:/app/config --name storagenode storjlabs/storagenode:beta 
echo $output >> $LOG 
echo $output 
output=`docker ps -a `
echo $output >> $LOG 
cat <<< $output

