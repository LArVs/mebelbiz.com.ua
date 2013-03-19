#!/bin/bash

# ----- configuration db file
_DB_FILE=".mebelbiz.com.ua.sh";
_DB_MODE="backup";
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
echo "Backup DB '$_DB_NAME' by $_SUB_NAME"
echo "    path: '$_BACKUP_DIR'"

# ----- 1 -----
echo $_MSG_HR2
echo "table:"
echo $_MSG_HR3
_GET_TABLES_LIST="echo 'show tables;' | $_DB_BIN --column-names=0 $_DB_NAME | grep -v '('"
tables=$( eval $_GET_TABLES_LIST )
#tables="t_tags_old"
for table in $tables; do
    _BACKUP_FILE="${_BACKUP_DIR}/${table}.sql.${_BACKUP_FILE_SUFF}"
    $_DB_ONE_DUMP_BIN $_DB_NAME $table | $_COMPRESSOR_BIN > $_BACKUP_FILE
done

