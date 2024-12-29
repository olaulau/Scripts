# support
- debian
- ubuntu


# features
- use Sury PHP & Apache debian repo  
- install all PHP versions  
    - with the most PHP modules it can, minus blacklist  
    or  
    - with whitelisted PHP modules (WiP)
- optionnally create apache-fpm virtualhost for each PHP version for the specified user, with HTTPS enabled  
  
  
# requirements
```
sudo apt install wget curl php git  
```


# requirements for dev workstation
user must have sudo rights, but do not use real root (whereas CA cert will not be isntalled into your browser)
```
su - root  
    <root password>  
adduser <user> sudo  
exit  
su - <user>  
```

for mkcert :
https://packages.debian.org/search?keywords=mkcert&searchon=names&suite=all&section=all  
https://blog.gabrielsagnard.fr/brew-sur-linux-linuxbrew/ (auto installed)    

  
# installation
```
git clone https://github.com/olaulau/Scripts/
cd Scripts/PHP/install
```
  
  
# usage
```
./install.php [--update] [--packages=<package_mode>] [--user=<USER>]  
--update : only install packages  
<package_mode> = blacklist / whitelist. without this option, no additional package will be installed  
<USER> = unix user to create fpm & vhost  
```


# examples
- php dev workstation
```
./install.php [--update] --packages=whitelist --user=<user>  
```
- php virtualhost server
```
./install.php [--update] --packages=whitelist  
```

# workaround
Sury PPA only support ubuntu LST releases, so you may see errors while running thee script (and not all expected packages will be installed).
```
sudo vim /etc/apt/sources.list.d/ondrej*.sources
```
and replace the "Suites: " property by the last TLS before your actual used release.
```
sudo apt update
```
and then rerun your latest script command with the "--update" option
