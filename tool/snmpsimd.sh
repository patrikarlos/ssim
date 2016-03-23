#!/bin/bash


function handle_signal {
    echo `date +'%F %T'` "Stopping snmpsim " >> /var/log/snmpsimd.log
    exit 0
}

ulimit -c unlimited
trap handle_signal SIGINT SIGTERM


cd /opt/ssim

while true; do
    echo `date +'%F %T'` "Starting snmpsim." >> /var/log/snmpsimd.log
    ./snmpsimd.pl >> /var/log/snmpsimd.log
    RET=$?
    echo `date +'%F %T'` "snmpsimd exited with code $RET." >> /var/log/snmpsimd.log
done

