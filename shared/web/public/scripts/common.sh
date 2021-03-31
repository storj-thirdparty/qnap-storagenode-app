#!/bin/bash

. resource.sh

function logMessage {
    module=$1 && shift
    echo $(date) ": ($module) $@" | tee -a $LOGFILE 
}
