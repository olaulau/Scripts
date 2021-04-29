# features
- use Sury PHP & Apache debian repo
- install all PHP versions with the most php modules it can  
- optionnally create apache-fpm virtualhost for each PHP version for the specified user, with HTTPS enabled  

# installation
git clone <project>  
cd <project>/PHP/install  

# requirement
sudo apt install wget  
https://github.com/ameinild/add-apt-key  
https://blog.gabrielsagnard.fr/brew-sur-linux-linuxbrew/  
	/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"  
	test -d ~/.linuxbrew && eval $(~/.linuxbrew/bin/brew shellenv)  
test -d /home/linuxbrew/.linuxbrew && eval $(/home/linuxbrew/.linuxbrew/bin/brew shellenv)  
test -r ~/.bash_profile && echo "eval \$($(brew --prefix)/bin/brew shellenv)" >>~/.bash_profile  
echo "eval \$($(brew --prefix)/bin/brew shellenv)" >>~/.profile  

# usage
- php dev workstation  
./install.php [--update] <user>  
- php virtualhost server  
./install.php [--update]  
