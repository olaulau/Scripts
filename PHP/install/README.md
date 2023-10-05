# features
- use Sury PHP & Apache debian repo
- install all PHP versions
    - with the most PHP modules it can, minus blacklist
    or
    - with whitelisted PHP modules
- optionnally create apache-fpm virtualhost for each PHP version for the specified user, with HTTPS enabled  
  
  
# requirements
sudo apt install wget curl php git  
  
for mkcert :
https://packages.debian.org/search?keywords=mkcert&searchon=names&suite=all&section=all  
https://blog.gabrielsagnard.fr/brew-sur-linux-linuxbrew/ (auto installed)  

  
# installation
git clone <project>  
cd <project>/PHP/install  
  
  
# usage
- php dev workstation  
./install.php [--update] <user>  
  
- php virtualhost server  
./install.php [--update]  

