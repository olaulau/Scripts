#!/bin/bash


##  check root
sudo -v
if [ $? -ne 0 ]
then
	echo "this script needs sudo rights to run."
	exit 1
fi


##  check params
if [ $# -ne 1 ]
then
	echo "parameter problem"
	exit 1
fi
user=$1


##  install brew if needed
is_brew_installed=`whereis brew | cut -d':' -f2 | sed '/^$/d' | wc -l`
if [ $is_brew_installed -ne 1 ]
then
	sudo apt install -y libnss3-tools
	sh -c "$(curl -fsSL https://raw.githubusercontent.com/Linuxbrew/install/master/install.sh)"
	test -d ~/.linuxbrew && eval $(~/.linuxbrew/bin/brew shellenv)
	test -d /home/linuxbrew/.linuxbrew && eval $(/home/linuxbrew/.linuxbrew/bin/brew shellenv)
	test -r ~/.bash_profile && echo "eval \$($(brew --prefix)/bin/brew shellenv)" >>~/.bash_profile
	echo "eval \$($(brew --prefix)/bin/brew shellenv)" >>~/.profile
	echo "brew install complete"
else
	echo "brew is already installed"
fi


##  install mkcert if needed
is_brew_installed=`whereis mkcert | cut -d':' -f2 | sed '/^$/d' | wc -l`
if [ $is_brew_installed -ne 1 ]
then
	brew install mkcert
	echo "mkcert install complete"
else
	echo "mkcert is already installed"
fi


##  create CA
if [ -f "$HOME/.local/share/mkcert/rootCA-key.pem" -a -f "$HOME/.local/share/mkcert/rootCA.pem" ]
then
	echo "CA already created"
else
	mkcert -install
	echo "CA generated and installed"
fi


##  prepare directory for key & cert into apache subdir
sudo mkdir -p /etc/ssl/mkcert


## generate key & cert for localhost
if [ -f "/etc/ssl/mkcert/localhost+2-key.pem" -a -f "/etc/ssl/mkcert/localhost+2.pem" ]
then
	echo "localhost key and cert already generated"
else
	mkcert localhost 127.0.0.1 ::1
	echo "localhost key and cert generation complete"
	sudo chown root:root localhost+2-key.pem localhost+2.pem
	sudo mv localhost+2-key.pem /etc/ssl/mkcert/
	sudo mv localhost+2.pem /etc/ssl/mkcert/
fi


##  generate key & cert for user
if [ -f "/etc/ssl/mkcert/_wildcard.$user.localhost-key.pem" -a -f "/etc/ssl/mkcert/_wildcard.$user.localhost.pem" ]
then
	echo "user key and cert already generated"
else
	mkcert "*.$user.localhost"
	echo "user key and cert generation complete"
	sudo chown root:root _wildcard.$user.localhost-key.pem _wildcard.$user.localhost.pem
	sudo mv _wildcard.$user.localhost-key.pem _wildcard.$user.localhost.pem /etc/ssl/mkcert/
fi


##  add localhost http vhost
sudo cp localhost.inc /etc/apache2/sites-available/
sudo cp localhost.conf /etc/apache2/sites-available/
sudo a2ensite localhost.conf


## enable ssl module
sudo a2enmod ssl

