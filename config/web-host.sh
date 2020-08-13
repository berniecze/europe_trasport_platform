#!/bin/bash

IP=$(getent hosts web | awk '{ print $1 }')
echo "" >> /etc/hosts
echo "$IP project.l" >> /etc/hosts

/opt/bin/entry_point.sh
