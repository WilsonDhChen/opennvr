#!/bin/sh

firewall-cmd --zone=public --add-port=1935/tcp --permanent
firewall-cmd --zone=public --add-port=554/tcp --permanent
firewall-cmd --zone=public --add-port=2554/tcp --permanent
firewall-cmd --zone=public --add-port=80/tcp --permanent
firewall-cmd --zone=public --add-port=443/tcp --permanent
firewall-cmd --zone=public --add-port=180/tcp --permanent
firewall-cmd --zone=public --add-port=280/tcp --permanent
firewall-cmd --zone=public --add-port=281/tcp --permanent
firewall-cmd --zone=public --add-port=380/tcp --permanent
firewall-cmd --zone=public --add-port=480/tcp --permanent
firewall-cmd --zone=public --add-port=580/tcp --permanent
firewall-cmd --zone=public --add-port=680/tcp --permanent
firewall-cmd --reload

