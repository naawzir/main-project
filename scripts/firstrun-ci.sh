#!/bin/bash

###############################################################################
##
## Setup Project For First Run
##
## This script checks for the existance of a .env file in the project root.
## If its not found, it runs a set of one-time-run commands to setup your
## environment
##
## This script:
## - Checks it needs to run by checking the existance of a .env file
## - Creates a .env file
## - Generates a laravel key and adds to .env
## - Sets up the database
## - Applies database access credentials to .env
## - Runs Laravel migrations on database
##
###############################################################################

###############################################################################
# Variables
###############################################################################
readonly GRN="\033[0;32m"
readonly YLW="\033[1;33m"
readonly RED='\033[0;31m'
readonly NC='\033[0m'

ENV_PATH="${BASH_SOURCE%/*}/../.env"

if [ ! -f $ENV_PATH ]; then

    echo -e "${NC}Env file not found, executing first run setup."

    echo -e "${NC}Waiting on boot...".

    sleep 10

    echo -e "${NC}Setting up database...".

    docker-compose -f "${BASH_SOURCE%/*}/docker-compose-jenkins.yml" exec -T webserver //bin/bash -c "mysql --user=\"root\" --password=\"\" --execute=\"CREATE DATABASE site_dev_tcp;GRANT ALL PRIVILEGES ON * . * TO 'root'@'127.0.0.1';FLUSH PRIVILEGES;\""

    if [ $? -eq 0 ]; then
        echo -e "${GRN}MySQL setup.".
        echo -e "${NC}Setting up env file."

        # ISG Marketplace
        cp "$ENV_PATH.example" $ENV_PATH

        docker-compose -f "${BASH_SOURCE%/*}/docker-compose-jenkins.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site && composer install --no-progress --no-interaction --optimize-autoloader --no-ansi && php artisan key:generate"

        if [ $? -eq 0 ]; then
            docker-compose -f "${BASH_SOURCE%/*}/docker-compose-jenkins.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site && php artisan migrate && php artisan db:seed" # && php artisan passport:keys"

            if [ $? -eq 0 ]; then
                echo -e "${GRN}Finished!"
                echo -e "${GRN}READY TO START DEVELOPMENT ======"
                exit 0;
            else
                echo -e "${RED}Unable to run database migrations - aborting!"
                exit 1;
            fi
        else
            echo -e "${RED}Unable to setup laravel key - aborting!"
            exit 1;
        fi
    else
       echo -e "${RED}Unable to setup MySQL - aborting!"
       exit 1;
    fi
else
    echo -e "${NC}Project already setup."
    echo -e "${GRN}READY TO START DEVELOPMENT ======"
    exit 0;
fi