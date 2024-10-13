#!/bin/bash
# ------------------ Config -------------------------
websitePath=/var/www/html
# those configs coming from outside, from the server installer
#dbHost="your db host, useually localhost"
#dbUser="sql user"
#dbPassword="sql user password"
#dbName="db name, usually pw"
#pwAdminId="pw admin user id"
#pwAdminSalt="pw admin salt"

DEBIAN_FRONTEND=noninteractive apt install -y libapache2-mod-php
apt install -y php php-mysql php-curl mcrypt

# pwAdminId="$lastInsertedUserId" pwAdminUsername="$pwAdminUsername" pwAdminPassword="$pwAdminPw"
wget https://github.com/hoangnguyent/pwAdmin/archive/refs/heads/master.zip
tar -xzf ./master.tar.gz -C $websitePath
rm ./master.tar.gz

service apache2 restart

chmod 777 -R "$websitePath"

initWebFolder="$websitePath/pwAdmin-master"
webFolder="$websitePath/pwAdmin"

if [ -d "$webFolder" ]; then rm -Rf $webFolder; fi
cp -R $initWebFolder $webFolder
rm -Rf $initWebFolder

mariadb -u"$dbUser" -p"$dbPassword" $dbName <<eof
    ALTER TABLE users ADD VotePoint INT(11) DEFAULT 0;
    ALTER TABLE users ADD VoteDates varchar(255) NOT NULL DEFAULT "1970-01-01 01:00:00";
eof

sed -r -e "s|DATABASE_HOST_NAME|$dbHost|" -e "s|DATABASE_USERNAME|$dbUser|" -e "s|DATABASE_PASSWORD|$dbPassword|" -e "s|DATABASE_NAME|$dbName|" -e "s|WEB_SITE_PATH|$websitePath|" -e "s|PW_ADMIN_ID|$pwAdminId|" -e "s|PW_ADMIN_HASH|$pwAdminSalt|" $websitePath/pwAdmin/config.php>$websitePath/pwAdmin/config.php.new
mv "$websitePath/pwAdmin/config.php" "$websitePath/pwAdmin/config.php.old"
mv "$websitePath/pwAdmin/config.php.new" "$websitePath/pwAdmin/config.php"