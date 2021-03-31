#!/bin/sh
CONF=/etc/config/qpkg.conf
QPKG_NAME="STORJ_LUMEN_TEST"
LOGFILE=/var/log/${QPKG_NAME}
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
APACHE_ROOT=`/sbin/getcfg SHARE_DEF defWeb -d Qweb -f /etc/config/def_share.info`
export QNAP_QPKG=$QPKG_NAME

case "$1" in
  start)
    echo `date` " STORJ_LUMEN_TEST : Request to start ($@) " >> $LOGFILE
    ENABLED=$(/sbin/getcfg $QPKG_NAME Enable -u -d FALSE -f $CONF)
    if [ "$ENABLED" != "TRUE" ]; then
        echo "$QPKG_NAME is disabled."
	echo `date` " STORJ : $PKG_NAME is disabled " >> $LOGFILE
        exit 1
    fi
    : ADD START ACTIONS HERE
    echo `date` "Create $QPKG_ROOT/web folder link to STORJ folder " >> /tmp/runSTORJ.log
    ln -s $QPKG_ROOT/web /home/Qhttpd/Web/STORJ_LUMEN_TEST
    echo `date` " STORJ_LUMEN_TEST : Request to start ($@) completed " >> $LOGFILE
    ;;

  stop)
    echo `date` " STORJ_LUMEN_TEST : Request to stop ($@) " >> $LOGFILE
    : ADD STOP ACTIONS HERE
    rm /home/Qhttpd/Web/STORJ_LUMEN_TEST
    ;;

  restart)
    echo `date` " STORJ_LUMEN_TEST : Request restart ($@) " >> $LOGFILE
    $0 stop
    $0 start
    ;;

  *)
    echo `date` " STORJ_LUMEN_TEST : Request UNKNOWN ($@) " >> $LOGFILE
    echo "Usage: $0 {start|stop|restart}"
    exit 1
esac

exit 0
