###### most simple way to use the script (live cd / fresh install)
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
