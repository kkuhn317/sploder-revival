#!/bin/bash

# Drop and and recreate the database
psql -U sploder -d postgres --command="drop database sploder;"
psql -U sploder -d postgres -f /docker-entrypoint-initdb.d/sploder.sql

# Create some mock data to work with

# TEST USERS
psql -U sploder -d sploder --command="\
  insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, level, ip_address, isolate)\
  values ('test', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 0, 1000, '127.0.0.1', true);"
psql -U sploder -d sploder --command="\
  insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, level, ip_address, isolate)\
  values ('test2', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 0, 1000, '127.0.0.1', true);"
