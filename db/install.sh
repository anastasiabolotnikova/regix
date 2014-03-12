#!/bin/sh
read -p "DB host [localhost]: " db_host
read -p "DB user [test_user]: " db_user
read -p "DB password [test_password]: " db_password
read -p "DB table [test_user]: " db_table

db_host=${db_host:-localhost}
db_user=${db_user:-test_user}
db_password=${db_password:-test_password}
db_table=${db_table:-test_user}

mysql -h$db_host -u$db_user -p$db_password -e "source create.sql" $db_table

for file in test_data/*
do
	echo Executing $file
	mysql -h$db_host -u$db_user -p$db_password -e "source ${file}" $db_table
done
