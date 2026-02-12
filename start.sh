#!/bin/bash
set -e

service mariadb start

# Wait for DB
sleep 5

# Secure + create DB
mysql -u root <<EOF
CREATE DATABASE IF NOT EXISTS appdb;
CREATE USER IF NOT EXISTS 'appuser'@'localhost' IDENTIFIED BY 'apppass';
GRANT ALL PRIVILEGES ON appdb.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;
EOF

# Import SQL if exists
if [ -f /docker-entrypoint-initdb.d/init.sql ]; then
  mysql -u root appdb < /docker-entrypoint-initdb.d/init.sql
fi

apache2-foreground