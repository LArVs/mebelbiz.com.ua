#!/bin/bash

# ----- configuration db file
_DB_FILE=".mebelbiz.com.ua.sh";
_DB_MODE="restore";
_DB_SUB_MODE="table";

# ----- load configuration, prepare directory and compressor
_CONFIG_FILE="_config.sh";
_SCRIPT_PATH=$( dirname $( readlink -f $0 ) );
_INCLUDE_PATH="$_SCRIPT_PATH";
_CONFIG_PATH="$_INCLUDE_PATH/$_CONFIG_FILE";
_BACKUP_PATH="$( dirname $_SCRIPT_PATH )/db";
_DB_PATH="$_INCLUDE_PATH/$_DB_FILE";
. "$_DB_PATH";
. "$_CONFIG_PATH";

# ----- override config variables
# --- example:
#SetDirectoryByTable "$_CURRENT_DATE"
#SetDirectoryByTable "2012-08-03"
#SetDirectoryByTable "production"
SetDirectory "${1:-$_CURRENT_DATE}"
#SetCompressor none
#SetCompressor gzip
SetCompressor bzip2

# ----- 0 -----
echo $_MSG_HR1
echo "Restore DB '$_DB_NAME' by ${_SUB_NAME}"
echo "     path: '$_BACKUP_DIR'"

# ----- 1 -----
echo $_MSG_HR2
echo "Create database if not exists"
_CREATE_DB_SQL="
CREATE DATABASE IF NOT EXISTS $_DB_NAME
	DEFAULT CHARACTER SET = UTF8
	DEFAULT COLLATE = utf8_general_ci
;"
echo $_CREATE_DB_SQL | $_DB_BIN; _ERROR_CODE=$?
if [ $_ERROR_CODE -ne 0 ]; then
	echo "Error: create db is fail!"
	exit $_ERROR_CODE;
fi

# ----- 2 -----
echo $_MSG_HR2
echo "Restore DB from table files"
tables_by_file=$(ls $_BACKUP_DIR)
#tables_by_file="t_tags_old.sql.bz2"

echo $_MSG_HR2
for table_file in $tables_by_file
do
	table_file_path="$_BACKUP_DIR/$table_file"
    if [ -f $table_file_path ]; then
		echo $table_file
		$_DECOMPRESSOR_BIN $table_file_path | $_DB_BIN $_DB_NAME
    fi
done

