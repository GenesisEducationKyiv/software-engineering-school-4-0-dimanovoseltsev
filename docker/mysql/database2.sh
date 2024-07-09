#/bin/bash
echo ${mysql[@]}

#!/bin/bash

echo "Creating databases"

databases=("$MYSQL_DATABASE2" "$MYSQL_DATABASE3")

for db in "${databases[@]}"; do
    if [ -n "$db" ]; then
        echo "Creating database $db if it doesn't exist"
        mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$db\`"

        echo "Granting privileges on database $db to user $MYSQL_USER"
        mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON \`$db\`.* TO '$MYSQL_USER'@'%'"
    fi
done

