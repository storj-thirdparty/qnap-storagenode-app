#!/bin/sh
CONF=/etc/config/qpkg.conf
QPKG_NAME="STORJ"
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
APACHE_ROOT=`/sbin/getcfg SHARE_DEF defWeb -d Qweb -f /etc/config/def_share.info`
export QNAP_QPKG=$QPKG_NAME
DOCKER=/share/CACHEDEV1_DATA/.qpkg/container-station/bin/docker

is_container_created() {
    ${DOCKER} ps -a
}

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
    /usr/bim/wget  https://github.com/storj/storj/releases/latest/download/identity_linux_arm.zip
    output = $ls
    #output = is_container_created
    echo $output
    #${DOCKER} rm "hello-world-container"
    # DOCKER_VERSION = ${DOCKER}
    #echo "${DOCKER}"
    #${DOCKER} -v
    echo "node started"
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
