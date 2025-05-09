#!/bin/bash

# Drop and and recreate the database
psql -U sploder -d postgres --command="drop database sploder;"
psql -U sploder -d postgres --command="create database sploder;"
psql -U sploder -d sploder -f /bootstrap/sploder.sql

# Create some mock data to work with

# TEST USERS
psql -U sploder -d sploder --command="\
  insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, level, ip_address, isolate)\
  values ('test', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 0, 1000, '127.0.0.1', true);"
psql -U sploder -d sploder --command="\
  insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, level, ip_address, isolate)\
  values ('test2', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 0, 1000, '127.0.0.1', true);"

# DUMMY GAMES
for i in $(seq 1 50);
do
    psql -U sploder -d sploder --command="\
        insert into games (author, title, date, description, g_swf, user_id, isprivate, ispublished)\
        values ('test', 'gtest$i', timezone('utc', now()), 'foobar', 0, 1, 0, 1);"

    psql -U sploder -d sploder --command="\
        insert into game_tags (g_id, tag)\
        values ($i, 'foobar');"
done

# DUMMY DELETIONS
for i in $(seq 1 20);
do
    psql -U sploder -d sploder --command="\
        insert into pending_deletions (g_id, deleter, reason, timestamp)
        values ($i, 'test', 'stuff$i', timezone('utc', now()));"
done

# DUMMY GRAPHIC
psql -U sploder -d sploder --command="\
  insert into graphics (version, userid, isprivate, ispublished)\
  values (0, 1, false, true);"
