#!/bin/bash

# Define paths for the dump directory and database files
DUMP_DIR="./database-dump"
WEBSITE_DB="website.db"
DATABASE_DB="database.db"
WEBSITE_DUMP="$DUMP_DIR/website_dump.sql"
DATABASE_DUMP="$DUMP_DIR/database_dump.sql"

# Check if the dump directory exists
if [ ! -d "$DUMP_DIR" ]; then
  echo "Error: Dump directory '$DUMP_DIR' does not exist."
  exit 1
fi

# Create website.db from website_dump.sql
if [ -f "$WEBSITE_DUMP" ]; then
  echo "Creating database '$WEBSITE_DB'..."
  sqlite3 "$WEBSITE_DB" < "$WEBSITE_DUMP"
  echo "Database '$WEBSITE_DB' created successfully."
else
  echo "Error: Dump file '$WEBSITE_DUMP' not found."
fi

# Create fake database.db from database_dump.sql
if [ -f "$DATABASE_DUMP" ]; then
  echo "Creating database '$DATABASE_DB'..."
  sqlite3 "$DATABASE_DB" < "$DATABASE_DUMP"
  echo "Database '$DATABASE_DB' created successfully."
else
  echo "Error: Dump file '$DATABASE_DUMP' not found."
fi
