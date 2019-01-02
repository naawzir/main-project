#!/bin/bash

###############################################################################
##
## Clean empty report file
##
## This script is used by Jenkins to make sure that only the correct test
## output is passed through and empty files are ignored.
##
## This script:
## - Removes empty phpmd.xml file in the report directory
##
###############################################################################

ls report

if [[ -f report/phpmd.xml && ! -s report/phpmd.xml ]]
then
  echo "File exists and is empty. Removing PHPMD file"
  rm report/phpmd.xml
else
  echo "File does not exist or has test failures."
fi
