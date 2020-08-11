#!/bin/bash

# This script updates the storagenode docker image
# Assumption: # It needs CONFIG_FILE path as a parameter for 
function setupEnv() {
    dirpath=$(dirname "$0")
    export PATH=$PATH:$dirpath
    . common.sh
}
setupEnv 


LOG=$LOGFILE
echo "$(date)" "$PKGNAME"  " docker container updater script running " >> "$LOG"
export PATH=$PATH:${SYS_QPKG_INSTALL_PATH}/container-station/bin
export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

# ------------------------------------------------------------------
# Figure out parameters for container
# ------------------------------------------------------------------
jq=$(which jq)
if [[ "x$jq" == "x" ]]
then
function jsonval {
    temp=$(echo $json | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w $1 )
    echo ${temp##*|}
}
else
function jsonval {
	echo $(echo $json | jq .$1 | sed 's/"//g' )
}
fi

# Case where all of params are available
if [ $# -ge 7 ]
then
    # Remove config file name and do processing similar to way to start script params
    # Corresponding command is presented here
    shift
    #Params-> 0        1       2      3    4    5     6    7    8       ----------
    read -r cfgfile address wallet size  id config myIP email rest <<< $*
    echo "GOT ONLINE params: cfgfile address wallet size id config myIP email " >> "$LOG"
    echo " $cfgfile $address $wallet $size $id $config $myIP $email " >> "$LOG"
elif [ $# -ge 1 ]
then
    #This should be processed as a parameter to this script (by calling party)
    CONFIG_FILE=$1
    IPADDR=$(ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1)
    echo "$(date)" "Using config file path as $CONFIG_FILE" >> "$LOG"
    # Setup json before param processing
    json=$(cat "$CONFIG_FILE" )
    address=$(jsonval Port)
    wallet=$(jsonval Wallet)
    size=$(jsonval Allocation)
    id=$(jsonval Identity)
    config=$(jsonval Directory)
    myIP=${IPADDR}
    email=$(jsonval Email)
    PORTADDR=$(sed -e 's#.*:\(\)#\1#' <<< "${address}")
    echo "GOT params FROM FILE: address/PORTADDR  wallet size id config myIP email " >> "$LOG"
    echo " $address/$PORTADDR $wallet $size $id $config $myIP $email " >> "$LOG"
else
    # default config path not available
    echo "ERROR: Processing failed as all params or config file path not provided " >> "$LOG"
    exit 1
fi


# ------------------------------------------------------------------------------
#       Container Updation logic 
# ------------------------------------------------------------------------------
set -e
BASE_IMAGE="storjlabs/storagenode:latest"
CONTAINER_NAME=storjlabsSnContainer
IMAGE="$BASE_IMAGE"
CID=$(docker ps | grep ${CONTAINER_NAME} | awk '{print $1}')
OLD=$(docker inspect --format "{{.Id}}" $IMAGE)
docker pull $IMAGE
LATEST=$(docker inspect --format "{{.Id}}" $IMAGE)

if [[ "x${OLD}" != "x${LATEST}" ]]
then
    RUNNING=$(docker inspect --format "{{.Image}}" "$CID")
    if [ "$RUNNING" != "$LATEST" ];then
        echo $(date) "Upgrading $CONTAINER_NAME" >> "$LOG"
	docker stop $CONTAINER_NAME
        docker rm -f $CONTAINER_NAME
	# ------------------------------------------------------------------
	# Re-start new container with related params
	# ------------------------------------------------------------------
	if [[ "x$email" == "x" ]]
        then
          docker run -d --restart=always -p "${PORTADDR}":28967 -p "${myIP}":14002:14002 -p "${myIP}":9000:9000 -e WALLET="${wallet}" -e ADDRESS="${address}"  -e STORAGE="${size}GB" -v "${id}/storagenode":/app/identity -v "${config}":/app/config --name ${CONTAINER_NAME} ${IMAGE} >> "$LOG" 2>&1
        else
          docker run -d --restart=always -p "${PORTADDR}":28967 -p "${myIP}":14002:14002 -p "${myIP}":9000:9000 -e WALLET="${wallet}" -e EMAIL="${email}" -e ADDRESS="${address}"  -e STORAGE="${size}GB" -v "${id}/storagenode":/app/identity -v "${config}":/app/config --name ${CONTAINER_NAME} ${IMAGE} >> "$LOG" 2>&1
        fi
    
	echo "$(date)" "Image $IMAGE updated (And running container $CONTAINER_NAME updated)" >> "$LOG"
    else
	echo "$(date)" "Image $IMAGE updated (And no container was running)" >> "$LOG"
    fi
else
    echo "$(date)" "$IMAGE is already up to date" >> "$LOG"
fi

# ------------------------------------------------------------------------------
