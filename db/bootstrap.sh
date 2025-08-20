#!/bin/bash

# Check if environment parameter is provided
ENVIRONMENT=${1:-dev}

echo "Bootstrapping database for environment: $ENVIRONMENT"

# Drop and recreate the database
psql -U sploder -d postgres --command="drop database IF EXISTS sploder;"
psql -U sploder -d postgres --command="create database sploder OWNER sploder;"
psql -U sploder -d sploder -f /bootstrap/sploder.sql

echo "Database schema loaded successfully"

# Create environment-specific data
if [ "$ENVIRONMENT" = "dev" ]; then
    echo "Creating development mock data..."
    
    # TEST USERS (for development only)
    psql -U sploder -d sploder --command="\
      insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, ip_address, isolate)\
      values ('test', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 1000, '127.0.0.1', true);"
    psql -U sploder -d sploder --command="\
      insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, ip_address, isolate)\
      values ('test2', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 1000, '127.0.0.1', true);"
    
    echo "Created development test users: test, test2 (password: 'password' for both)"

    # DUMMY GAMES (for development only)
    for i in $(seq 1 50);
    do
        psql -U sploder -d sploder --command="\
            insert into games (author, title, date, description, g_swf, user_id, isprivate, ispublished)\
            values ('test', 'gtest$i', timezone('utc', now()), 'foobar', 0, 1, 0, 1);"

        psql -U sploder -d sploder --command="\
            insert into game_tags (g_id, tag)\
            values ($i, 'foobar');"
    done
    echo "Created 50 dummy games"

    # DUMMY DELETIONS (for development only)
    for i in $(seq 1 20);
    do
        psql -U sploder -d sploder --command="\
            insert into pending_deletions (g_id, deleter, reason, timestamp)
            values ($i, 'test', 'stuff$i', timezone('utc', now()));"
    done
    echo "Created 20 pending deletions"

    # DUMMY GRAPHIC (for development only)
    psql -U sploder -d sploder --command="\
      insert into graphics (version, userid, isprivate, ispublished)\
      values (0, 1, false, true);"
    echo "Created dummy graphic"
    
    echo "Development mock data creation completed!"
    
elif [ "$ENVIRONMENT" = "prod" ]; then
    echo "Production environment selected. No mock data will be created."
else
    echo "Invalid environment: $ENVIRONMENT"
    echo "Usage: bootstrap.sh [dev|prod]"
    echo "No additional data created"
fi

echo "Bootstrap completed for $ENVIRONMENT environment"