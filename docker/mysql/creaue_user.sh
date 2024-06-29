 echo "CREATE Users"
 mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "
ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';

CREATE USER 'db_user'@'localhost' IDENTIFIED BY 'db_pass';
GRANT ALL PRIVILEGES ON *.* TO 'db_user'@'localhost' WITH GRANT OPTION;

# CREATE USER 'db_user'@'%' IDENTIFIED BY 'db_pass';
# GRANT ALL PRIVILEGES ON *.* TO 'db_user'@'%' WITH GRANT OPTION;

SHOW GRANTS FOR db_user;
FLUSH PRIVILEGES;

 "
