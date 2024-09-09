#!/bin/bash
TIMESTAMP=$(date +"%F")
BACKUP_DIR="/var/backup"
MYSQL_USER="root"
MYSQL_PASSWORD="${MYSQL_ROOT_PASSWORD}"
MYSQL_HOST="db"
MYSQL_DATABASE="${DB_DATABASE}"
BACKUP_FILE="$BACKUP_DIR/$MYSQL_DATABASE-$TIMESTAMP.sql"

mkdir -p "$BACKUP_DIR"
mysqldump -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" > "$BACKUP_FILE"

echo "Backup completed: $BACKUP_FILE"
