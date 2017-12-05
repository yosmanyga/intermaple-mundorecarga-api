#!/bin/sh

# Note: Don't create files containing dot(.)

cp -R /root/cron/1hour /etc/periodic/1hour
cp -R /root/cron/1day /etc/periodic/1day

chmod +x /etc/periodic/1hour/*
chmod +x /etc/periodic/1day/*

echo "* * * * * run-parts /etc/periodic/1hour" | crontab -
(crontab -l; echo "0 * * * * run-parts /etc/periodic/1day") | crontab -

touch /var/log/cron.log

crond

exec tail -f /var/log/cron.log