# support
- debian
- ubuntu


# features
- use Sury PHP & Apache debian repo  
- install all PHP versions  
    - with the most PHP modules it can, minus blacklist  
    or  
    - with whitelisted PHP modules only
- optionnally create apache-fpm virtualhost for each PHP version for the specified user, with HTTPS enabled  



# requirements for dev workstation
user must have sudo rights, but do not use real root (whereas CA cert will not be installed into your browser)
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

  
  
# requirements
```
sudo apt install wget curl php git
```


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
./install.php [--update] --packages=whitelist --user=$USER
```
- php virtualhost server
```
./install.php [--update] --packages=whitelist
```


# workaround
Sury PPA only support ubuntu LTS releases, so you may see errors while running the script (and not all expected packages will be installed).
```
sudo vim /etc/apt/sources.list.d/ondrej*.sources
```
and replace the "Suites: " property by the last TLS before your actual used release.
```
sudo apt update
```
and then rerun your latest command with the "--update" option


# renew certificate
Once installed, the certificate won't be created anymore. It is valid for about 2 years.  
After this, browsers will show a warning.  
To force the renewal, type :  
```
rm "$HOME/.local/share/mkcert/rootCA*.pem"
sudo rm "/etc/ssl/mkcert/*.pem"
```

