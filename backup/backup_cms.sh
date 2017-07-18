#!/bin/bash

##
## This file is for backup Drupal on cms_serv, global_cms_serv.
##
## Store this file to /bacula directory.
## Vacula-fd will run this file when before/after backup.
## 
## Before the backup:
##  - run drush command 
##
## After the backup:
##  - None to do
##

##
## Variables
BACKUP_DEST_DIR=/bacula/cms
BACKUP_FILE_NM=cms_dump.tar.gz
BACKUP_DRUPAL_DIR=/var/www/html/drupal

PG_NAME=`basename ${0}`
BEFORE_FLG=""


##
## Usage
usage() {
    echo ""
    echo "Usage:"
    echo "  ${PG_NAME} [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -b   : before the backup"
    echo "  -a   : after the backup"
    echo ""
    echo "Notie:"
    echo "  Backup file will be named ${BACKUP_DEST_DIR}/${BACKUP_FILE_NM}"
    echo ""

}

##
## Before the backup
run_as_before() {

    ##
    ## Prepair to backup
    if [ ! -e ${BACKUP_DEST_DIR} ]; then
        echo "Creating backup directory (${BACKUP_DEST_DIR}).."
        mkdir -p ${BACKUP_DEST_DIR}
    fi

    ##
    ## Run drush
    cd ${BACKUP_DRUPAL_DIR}
    drush archive-dump --overwrite --destination=${BACKUP_DEST_DIR}/${BACKUP_FILE_NM}


    RESULT=$?

    if [ $RESULT -ne 0 ]; then
        echo "Failed (${RESULT})"
    else
        echo "Success"
    fi

    exit ${RESULT}

}

##
## After the backup
run_as_after() {

    ## DO NOTHING
    exit 0

}


##
## Managing arguments
while getopts :ba opts
do
    case $opts in
        b)
            BEFORE_FLG="true"
            run_as_before
            ;;
        a)
            BEFORE_FLG="false"
            run_as_after
            ;;
        :|\?)
            usage
            exit 1
            ;;
    esac
done

if [ "${BEFORE_FLG}" = "" ]; then
    usage
    exit 1
fi
