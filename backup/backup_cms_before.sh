#!/bin/bash

SCRIPT_DIR=$(cd $(dirname $0);pwd) PARENT_DIR=$(cd $(dirname $0);cd ..;pwd)
SCRIPT_NM=backup_cms.sh

/bin/bash -xe ${SCRIPT_DIR}/${SCRIPT_NM} -b
REULST=$?

exit ${RESULT}
