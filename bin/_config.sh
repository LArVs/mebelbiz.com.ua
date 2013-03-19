#!/bin/bash

_DB_BIN="mysql $_DB_CONNECT"

_DB_ONE_DUMP_OPTION="$_DB_CONNECT -v --force --comments=false --quote-names"
_DB_ALL_DUMP_OPTION="$_DB_ONE_DUMP_OPTION --add-drop-database --databases"
_DB_ONE_DUMP_BIN="mysqldump $_DB_ONE_DUMP_OPTION"
_DB_ALL_DUMP_BIN="mysqldump $_DB_ALL_DUMP_OPTION"

_CURRENT_DATE=$( date +%F )

_MSG_HR1="=================================================="
_MSG_HR2="=========="
_MSG_HR3="====="

SetFileByLastDate() {
	_FILE_BY_LAST_DATE=$(  ls -t ${_BACKUP_DIR}/ | head -n1 )
	echo "Found last file by date: '${_FILE_BY_LAST_DATE}'"
	_BACKUP_FILE_ARC="${_BACKUP_DIR}/$_FILE_BY_LAST_DATE"
}

SetDirectoryByFile() {
	_SUB_NAME=${1:-$_CURRENT_DATE}
	_BACKUP_FILE="${_BACKUP_DIR}/${_DB_NAME}-${_SUB_NAME}.sql"
	if [[ ( "$_DB_MODE" == "backup" ) && ( ! -d $_BACKUP_DIR ) ]]; then
		echo $_MSG_HR2
		echo "Create backup directory"
		mkdir $_BACKUP_DIR -p
	else
		if [[ ! -d $_BACKUP_DIR ]]; then
			echo $_MSG_HR2
			echo "Not exist backup directory:"
			echo "'${_BACKUP_DIR}'"
			exit 1
		fi
	fi
}

SetDirectoryByTable() {
	_SUB_NAME=${1:-$_CURRENT_DATE}
	_BACKUP_DIR="${_BACKUP_DIR}/${_SUB_NAME}"
	if [[ ( "$_DB_MODE" == "backup" ) && ( ! -d $_BACKUP_DIR ) ]]; then
		echo $_MSG_HR2
		echo "Create backup directory"
		mkdir $_BACKUP_DIR -p
	else
		if [[ ! -d $_BACKUP_DIR ]]; then
			echo $_MSG_HR2
			echo "Not exist backup directory:"
			echo "'${_BACKUP_DIR}'"
			exit 1
		fi
	fi
}

SetDirectory() {
	if [ -z "${_BACKUP_PATH}" ]; then
		_BACKUP_PATH="/home/larv/git";
		_BACKUP_DIR="${_BACKUP_PATH}/${_DB_NAME}/db";
	else
		_BACKUP_DIR=$_BACKUP_PATH
	fi
	case $_DB_SUB_MODE in
		file)
			SetDirectoryByFile $1 $2 $3
			;;
		table)
			SetDirectoryByTable $1 $2 $3
			;;
		*)
			echo "Unknow submode operation!";
			exit 1;
	esac
}

SetCompressor() {
	local _compressor=${1:-''}

	case $_compressor in
		none)
			_COMPRESSOR=""
			;;
		gzip)
			_COMPRESSOR="gzip"
			_BACKUP_FILE_SUFF="gz"
			;;
		bzip2|*)
			_COMPRESSOR="bzip2"
			_BACKUP_FILE_SUFF="bz2"
			;;
	esac

	if [[ -z $_COMPRESSOR ]]; then
		_BACKUP_FILE_ARC="$_BACKUP_FILE"
		_COMPRESSOR_BIN="cat"
		_DECOMPRESSOR_BIN="cat"
	else
		if [[ -z $_BACKUP_FILE_ARC ]]; then
			_BACKUP_FILE_ARC="$_BACKUP_FILE.$_BACKUP_FILE_SUFF"
		fi
		_COMPRESSOR_BIN="$_COMPRESSOR -c -9"
		_DECOMPRESSOR_BIN="$_COMPRESSOR -dc"
	fi
	if [[ ( "$_DB_MODE" == "restore" ) && ( "$_DB_SUB_MODE" == "file" ) && ( ! -f $_BACKUP_FILE_ARC ) ]]; then
		echo $_MSG_HR2
		echo "Not exist backup file:"
		echo "'${_BACKUP_FILE_ARC}'"
		exit 1
	fi
}

echo $_MSG_HR1
echo "Load config"

