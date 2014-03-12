#!/bin/sh
read -p "DB host [localhost]: " db_host
read -p "DB user [test_user]: " db_user
read -p "DB password [test_password]: " db_password
read -p "DB schema [test_user]: " db_database

db_host=${db_host:-localhost}
db_user=${db_user:-test_user}
db_password=${db_password:-test_password}
db_database=${db_database:-test_user}

echo Executing create.sql
mysql -h$db_host -u$db_user -p$db_password -D$db_database -e "source create.sql"

for file in test_data/*
do
	echo Executing $file
	mysql -h$db_host -u$db_user -p$db_password -D$db_database -e "source ${file}"
done
