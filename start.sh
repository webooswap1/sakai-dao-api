#!/bin/bash

/usr/local/bin/supercronic /etc/cron.d/my-crontab &

# Start PHP service.
exec "php-fpm"
