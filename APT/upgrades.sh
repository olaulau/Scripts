#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
export NEEDRESTART_MODE=a

apt-fast update
apt-fast -y full-upgrade
apt -y autoremove
apt autoclean

