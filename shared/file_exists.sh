count=$(/bin/ls /id/identity/storagenode/ | wc -l)
if [ $count == "6" ];
    then
    echo "ok"
fi
