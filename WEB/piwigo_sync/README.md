## install
 #git submodule add https://github.com/pommes-frites/piwigo-scripts  
git submodule update  
find piwigo-scripts/ -name "*.sh" -execdir chmod u+x {} +  
  
  
  
## config
go to piwigo-scripts/CheckAndUpdatePiwigo/conf  
copy file and fill in values  
  
  
  
## needed piwigo plugin
need to install custom missing derivative  
https://piwigo.org/ext/extension_view.php?eid=846  
https://github.com/pommes-frites/piwigo-custom_missing_derivatives  
  
download https://piwigo.org/ext/download.php?rid=6084  
unzip move directory to piwigo plugins subfolder  
go to admin > plugins and activate it  
  
  
  
## test
./sync.sh
