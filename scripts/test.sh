#!/bin/bash

###############################################################################
##
## Run Full Test Suite
##
## Use this script to execute full test suite. Only failed PHPUnit tests result
## in a non-zero exit code.
##
## Running this script without parameters will run all test suites, you can
## specify an individual test suite by passing one of the options listed below.
##
## This script:
## - Executes PHPUnit (Pass/Fail)
## - Executes PHPCS (Advisory)
## - Executes PHPMD (Advisory)
## - Executes ESLint (Advisory)
## - Executes Mocha, Chai + Nightmare (Pass/Fail)
##
## Usage:
## - ./scripts/test.sh (runs all tests)
## - ./scripts/test.sh help (displays usage help)
## - ./scripts/test.sh [phpunit|phpcs|phpmd|eslint|mocha] (specific an individual test suite)
##
###############################################################################

###############################################################################
# Variables
###############################################################################
readonly GRN="\033[0;32m"
readonly YLW="\033[1;33m"
readonly RED='\033[0;31m'
readonly NC='\033[0m'
readonly COMPOSE_FILE="${BASH_SOURCE%/*}/../docker-compose.yml"

RUN=$1

if [ "$RUN" = "help" ]; then
    echo "Usage: ./scripts/test.sh [phpunit|phpcs|phpmd|eslint|mocha]";
    exit 0;
fi

SUCCESS=0;

if [ -z "$RUN" ] || [ "$RUN" = "lint" ]; then
    echo -e "${NC}Executing PHP Lint Tests...".

    docker-compose -f ${COMPOSE_FILE} exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/parallel-lint app/"

    if [ $? -eq 0 ]; then
        echo -e "${GRN}PHP Lint Passed."
    else
        echo -e "${RED}PHP Lint Failed."
        SUCCESS=1
    fi

    # Reset out
    echo -e "${NC}"
fi

if [ -z "$RUN" ] || [ "$RUN" = "phpunit" ]; then
    echo -e "${NC}Executing PHPUnit...".
    docker-compose -f ${COMPOSE_FILE} exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/phpunit --configuration phpunit.xml"

    if [ $? -eq 0 ]; then
        echo -e "${GRN}PHPUnit Passed."
    else
        echo -e "${RED}PHPUnit Failed."
        SUCCESS=1
    fi

    # Reset out
    echo -e "${NC}"
fi

if [ -z "$RUN" ] || [ "$RUN" = "phpcs" ]; then
    echo -e "${NC}Executing PHP Code Sniffer...".

    docker-compose -f ${COMPOSE_FILE} exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/phpcs -s"

    if [ $? -eq 0 ]; then
        echo -e "${GRN}PHPCS Passed."
    else
        echo -e "${RED}PHPCS Failed."
    fi

    # Reset out
    echo -e "${NC}"
fi

if [ -z "$RUN" ] || [ "$RUN" = "phpmd" ]; then
    echo -e "${NC}Executing PHP Mess Detector...".

    docker-compose -f ${COMPOSE_FILE} exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/phpmd app,config,public,resources,routes,tests text phpmd.rulesets.xml"

    if [ $? -eq 0 ]; then
        echo -e "${GRN}PHPMD Passed."
    else
        echo -e "${RED}PHPMD Failed."
    fi

    # Reset out
    echo -e "${NC}"
fi

if [ -z "$RUN" ] || [ "$RUN" = "eslint" ]; then
    echo -e "${NC}Executing ESLint...".

    docker-compose -f ${COMPOSE_FILE} exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && npm run --silent lint"

    if [ $? -eq 0 ]; then
        echo -e "${GRN}ESLint Passed."
    else
        echo -e "${RED}ESLint Failed."
    fi

    # Reset out
    echo -e "${NC}"
fi

if [ -z "$RUN" ] || [ "$RUN" = "mocha" ]; then
    echo -e "${NC}Executing Mocha and Chai...".

    docker-compose -f ${COMPOSE_FILE} exec webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && npm --silent test"

    if [ $? -eq 0 ]; then
        echo -e "${GRN}Mocha, Chai and Nightmare Passed."
    else
        echo -e "${RED}Mocha, Chai and Nightmare Failed."
        SUCCESS=1
    fi

    # Reset out
    echo -e "${NC}"
fi

exit $SUCCESS;
