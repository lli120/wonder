# wonder

1. git pull the repo, place all files under a folder in yoru local apache /var/www dir. 
2. Fireup a browser, start off index.php i.e.: If your folder name is "wonder", url should be localhost/wonder/index.php
3. Make sure the created folder has 775 permission, we will be generating a data file in. i.e.: chmod -R 775 wonder
Light It OFF!


Note:
###### data.php => Main used for to structure the xml data into a json object and stored in data.json file. The initial process would take a few mins to settle. This script could use for set with scheduled job/cron job which can be executed once per day/per week/fornightly, based on the change frequence on the data it self.
###### index.php => Render search and Result table. Used jquery-UI for some nice formatting on the hospital name.
###### search.php => used for searching logic, filtering through the json object based on the data initializing process.
