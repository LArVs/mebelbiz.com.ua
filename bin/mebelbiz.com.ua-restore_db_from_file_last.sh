#!/bin/bash

# ----- configuration db file
_DB_FILE=".mebelbiz.com.ua.sh";
_DB_MODE="restore";
_DB_SUB_MODE="file";

# ----- load configuration, prepare directory and compressor
_CONFIG_FILE="_config.sh";
_SCRIPT_PATH=$( dirname $( readlink -f $0 ) );
_INCLUDE_PATH="$_SCRIPT_PATH";
_CONFIG_PATH="$_INCLUDE_PATH/$_CONFIG_FILE";
_DB_PATH="$_INCLUDE_PATH/$_DB_FILE";
_BACKUP_PATH="$( dirname $_SCRIPT_PATH )/db";
. "$_DB_PATH";
. "$_CONFIG_PATH";

# ----- override config variables
# --- example:
#SetDirectoryByFile "$_CURRENT_DATE"
#SetDirectoryByFile "2012-08-03"
#SetDirectoryByFile "production"
SetDirectory "${1:-$_CURRENT_DATE}"
SetFileByLastDate
#SetCompressor none
#SetCompressor gzip
SetCompressor bzip2

# ----- 0 -----
echo $_MSG_HR1
echo "Backup DB '$_DB_NAME' by $_SUB_NAME"
echo "    file: '$_BACKUP_FILE_ARC'"

# ----- 1 -----
echo $_MSG_HR2
echo "Restore DB from file"
$_DECOMPRESSOR_BIN $_BACKUP_FILE_ARC | $_DB_BIN

