#!/bin/bash

# prepare
find piwigo-scripts/ -name "*.sh" -execdir chmod u+x {} +

# exec
bash ./piwigo-scripts/CheckAndUpdatePiwigo/start.sh
