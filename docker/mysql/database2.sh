#/bin/bash
echo ${mysql[@]}
if [ "$MYSQL_DATABASE2" ]; then
 echo "CREATE DATABASE IF NOT EXISTS"
 mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "create database $MYSQL_DATABASE2"

 fi
#		if [ "$MYSQL_USER" -a "$MYSQL_PASSWORD" ]; then
#			mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "CREATE USER '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD'"

			if [ "$MYSQL_DATABASE2" ]; then
				mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "GRANT ALL ON \`$MYSQL_DATABASE2\`.* TO '$MYSQL_USER'@'%' "
			fi
#		fi
