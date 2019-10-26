count=$(/bin/ls /share/Public/identity/storagenode/ | wc -l)
if [ $count == "6" ];
    then
    echo "ok"
    else
    echo "nok"
fi
