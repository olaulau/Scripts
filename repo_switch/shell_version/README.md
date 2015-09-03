## repository switcher for Debian-based OS
easy switch to debian / ubuntu / mint faster mirror  
- very simple (on bash script, one config file)  
- based on prefixes (target : apt-mirror)  

#### most simple way to use the script (live cd / fresh install / usb-key)
open a terminal (ctrl + alt + T) and type those commands :  
`mkdir repo && cd repo`  
`wget https://github.com/olaulau/Scripts/raw/master/repo_switch/shell_version/menu.sh`  
`wget https://github.com/olaulau/Scripts/raw/master/repo_switch/shell_version/config.sh.EXAMPLE`  

edit the config file :  
`mv config.sh.EXAMPLE config.sh`  
`gedit config.sh &`  
fill-in the repository names and prefixes, save the file  

run the script with `sudo bash menu.sh`  
then update APT database : `sudo apt-get update`  
if all was good (correct sources.list and config.sh before running the script), you should have switched to the specified repo.  


#### git-style usage (laptop)
`git clone https://github.com/olaulau/Scripts`  
`cd Scripts/repo_switch/shell_version`  
`cp config.sh.EXAMPLE config.sh && gedit config.sh`  
`cd /usr/local/bin`  
`sudo ln -s ~/Script/repo_switch/menu.sh repo_switch.sh`  
`cd`  
`sudo repo_switch.sh`  
