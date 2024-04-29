#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
export NEEDRESTART_MODE=a
apt-fast update
apt-fast -y upgrade

unset DEBIAN_FRONTEND
unset NEEDRESTART_MODE
apt-fast full-upgrade

apt -y autoremove
apt autoclean
