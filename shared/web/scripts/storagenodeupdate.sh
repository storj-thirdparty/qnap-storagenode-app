#!/bin/bash

# This script updates the storagenode docker image
# Assumption: # It needs CONFIG_FILE path as a parameter 

PKGNAME="StorJ"
LOG="/var/log/$PKGNAME"
echo `date` $PKGNAME  " docker container updater script running " >> $LOG

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

#This should be processed as a parameter to this script (by calling party)
if [[ $# -gt 0 ]]
then
    CONFIG_FILE=$1
else
    # default path not available
    echo "ERROR: Processing failed as config file path not provided " >> $LOG
    exit 1
fi
echo `date` "Using config file path as $CONFIG_FILE" >> $LOG

# ------------------------------------------------------------------
# Figure out parameters for container
# ------------------------------------------------------------------
jq=`which jq`
if [[ "x$jq" == "x" ]]
then
function jsonval {
    temp=`echo $json | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w $1 `
    echo ${temp##*|}
}
else
function jsonval {
	echo `echo $json | jq .$1 | sed 's/"//g' `
}
fi
json=`cat $CONFIG_FILE `
id=`jsonval Identity`
port=`jsonval Port`
wallet=`jsonval Wallet`
size=`jsonval Allocation`
bw=`jsonval Bandwidth`
email=`jsonval Email`
config=`jsonval Directory`
#echo " id port wallet size bw email config "
#echo " $id $port $wallet $size $bw $email $config "

# ------------------------------------------------------------------
# 	ERROR HANDLING (TODO) -> In case params not found and 
#  	all params passed to script, use them
# ------------------------------------------------------------------
if [[ "x$config" == "" ]]
then
    # Case where all of params aren't available
    if [ $# ge 9 ]
    then
	# Remove config file name and do processing similar to way to start script params
	# Corresponding command is presented here
	shift
	#Params-> 1   2      3    4    5   6    7    8       ----------
	read -r port wallet email bw size id config myIP rest <<< $*
    else
	echo "ERROR: Processing failed as not enough information" >> $LOG
	exit 1
    fi
fi


# ------------------------------------------------------------------------------
#       Container Updation logic 
# ------------------------------------------------------------------------------
set -e
BASE_IMAGE="storjlabs/storagenode:beta"
REGISTRY=""
IMAGE="$BASE_IMAGE"
#REGISTRY="registry.hub.docker.com"
#IMAGE="storjlabs/storagenode:beta"
#IMAGE="$REGISTRY/$BASE_IMAGE"
CID=$(docker ps | grep $IMAGE | awk '{print $1}')
docker pull $IMAGE
for im in $CID
do
    LATEST=`docker inspect --format "{{.Id}}" $IMAGE`
    RUNNING=`docker inspect --format "{{.Image}}" $im`
    NAME=`docker inspect --format '{{.Name}}' $im | sed "s/\///g"`
    echo `date` "Latest:" $LATEST >> $LOG
    echo `date` "Running:" $RUNNING >> $LOG
    if [ "$RUNNING" != "$LATEST" ];then
        echo `date` "upgrading $NAME" >> $LOG
        #stop docker-$NAME
	docker stop $NAME
        docker rm -f $NAME
	# ------------------------------------------------------------------
	# Re-start new container with related params
	# ------------------------------------------------------------------
	docker run -d --restart unless-stopped -p "${port}":28967 -p 14002:14002 -e WALLET="${wallet}" -e EMAIL="${email}" -e ADDRESS="${myIP}:${port}" -e BANDWIDTH="${bw}TB" -e STORAGE="${size}GB" -v ${id}:/app/identity -v ${config}:/app/config --name storagenode ${IMAGE} >> $LOG 2>&1
    else
        echo `date` "$NAME up to date" >> $LOG
    fi
done

# ------------------------------------------------------------------------------
