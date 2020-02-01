#!/bin/sh
CONF=/etc/config/qpkg.conf
LOGFILE=/var/log/StorJ
QPKG_NAME="STORJ"
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
APACHE_ROOT=`/sbin/getcfg SHARE_DEF defWeb -d Qweb -f /etc/config/def_share.info`
export QNAP_QPKG=$QPKG_NAME
DOCKER=/share/CACHEDEV1_DATA/.qpkg/container-station/bin/docker
IDENTITY=/share/Public/identity_linux_amd64

is_container_created() {
    ${DOCKER} ps -a --format "{{.Names}}" | grep "^$QPKG_NAME$"
}

#echo "$@"

case "$1" in
  start)
    ENABLED=$(/sbin/getcfg $QPKG_NAME Enable -u -d FALSE -f $CONF)
    if [ "$ENABLED" != "TRUE" ]; then
        echo "$QPKG_NAME is disabled."
        exit 1
    fi
    : ADD START ACTIONS HERE
    ln -s $QPKG_ROOT/web /home/Qhttpd/Web/STORJ
    /usr/bin/wget https://github.com/storj/storj/releases/latest/download/identity_linux_arm.zip -P /share/Public/
    ;;

  stop)
    : ADD STOP ACTIONS HERE
    rm /home/Qhttpd/Web/STORJ
    ;;

  start-node)
    : ADD STOP ACTIONS HERE
    echo "start node"
    echo "$2" "$3"
    $IDENTITY 2>&1 | tee /share/Public/output1.txt 
    #output = is_container_created
    echo $output
    #${DOCKER} rm "hello-world-container"
    # DOCKER_VERSION = ${DOCKER}
    #echo "${DOCKER}"
    ${DOCKER} -v
    echo "node started"
    ;;

   authorize)
    : ADD AUTHORIZE COMMAND HERE
     #${IDENTITY} create storagenode 2>&1
     #/bin/cp -r /tmp/.local/share/storj/identity/ /id
     #/bin/cp /tmp/.local/share/storj/identity/ /
     #${IDENTITY} authorize storagenode "$2" 2>&1
     #/bin/cp -r /tmp/.local/share/storj/identity/ /id
     #move the identity files to Public/
    ;;
 
   is-authorized)
    /share/CACHEDEV1_DATA/.qpkg/STORJ/file_exists.sh 2>&1
    #if [ -e "/root/ca.key" ];
    #then
    #echo "ok"
    #fi
   ;;

   start-docker)
   command="${DOCKER} run -d --restart unless-stopped -p "$2":28967 -p 14002:14002 -e WALLET="$3" -e EMAIL="$6" -e ADDRESS="${9}:${2}" -e BANDWIDTH="${5}TB" -e STORAGE="${4}GB" --mount type=bind,source="${8}/storagenode/",destination=/app/identity --mount type=bind,source="$7",destination=/app/config --name ${QPKG_NAME} storjlabs/storagenode:beta "
   output=` $command 2>&1 ` 
   echo $command >> $LOGFILE
   echo $output >> $LOGFILE
 
 
   #${DOCKER} -v 
    ;;

  stop-docker)
    command1="${DOCKER} stop ${QPKG_NAME} "
    command2="${DOCKER} rm -f ${QPKG_NAME} "
    output1=` $command1 2>&1 `
    output2=` $command2 2>&1 `
    echo `date` " $output1  " >> $LOGFILE
    echo `date` " $output2  " >> $LOGFILE
    ;; 

   is-running)
    : ADD STOP ACTIONS HERE
    #echo "$2" "$3"
    #${DOCKER} -v
    #if [ ! ${DOCKER} ps -a --format "{{.Names}}" | grep "storagenode" = "" ];
    #if [ ! is_container_created  = "" ];
    #if [ ${DOCKER} ps -a --format "{{.Names}}" | grep "^$QPKG_NAME$"  ];
    #then
    #  echo "ok"
    #fi
    ${DOCKER} ps -a --format "{{.Names}}" | grep "^$QPKG_NAME$" 2>&1
    ;;

  restart)
    $0 stop
    $0 start
    ;;

  *)
    echo "Usage: $0 {start|stop|restart}"
    exit 1
esac

exit 0
