@echo off
SETLOCAL ENABLEEXTENSIONS
::##############################################################################
::#
::# Run Full Test Suite
::#
::# Use this script to execute full test suite. Only failed PHPUnit tests result
::# in a non-zero EXIT code.
::#
::# Running this script without parameters will run all test suites, you can
::# specIFy an individual test suite by passing one of the options listed below.
::#
::# This script:
::# - Executes PHPUnit (Pass/Fail)
::# - Executes PHPCS (Advisory)
::# - Executes PHPMD (Advisory)
::# - Executes ESLint (Advisory)
::# - Executes Mocha, Chai + Nightmare (Pass/Fail)
::#
::# Usage:
::# - ./scripts/test.sh (runs all tests)
::# - ./scripts/test.sh help (displays usage help)
::# - ./scripts/test.sh [phpunit|phpcs|phpmd|eslint|mocha] (specIFic an individual test suite)
::#
::##############################################################################

::##############################################################################
:: Variables
::##############################################################################
SET "FOLDERPATH=%~dp0"

SET "RUN=%1"

IF "%RUN%"=="help" (
    echo Usage: ./scripts/test.bat [lint|phpunit^|phpcs^|phpmd^|eslint^|mocha]
    EXIT /B 0
)

SET SUCCESS=0

IF "%RUN%"=="" (
    SET RUNPHPLINT=1
    SET RUNPHPUNIT=1
    SET RUNPHPMD=1
    SET RUNPHPCS=1
    SET RUNESLINT=1
    SET RUNMOCHA=1
)

IF "%RUN%"=="lint" SET RUNPHPLINT=1
IF "%RUN%"=="phpunit" SET RUNPHPUNIT=1
IF "%RUN%"=="phpmd" SET RUNPHPMD=1
IF "%RUN%"=="phpcs" SET RUNPHPCS=1
IF "%RUN%"=="eslint" SET RUNESLINT=1
IF "%RUN%"=="mocha" SET RUNMOCHA=1

IF defined RUNPHPLINT (
    echo "Executing PHP Lint Tests..."

    docker-compose -f "%FOLDERPATH%docker-compose.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/parallel-lint app/"

    IF ERRORLEVEL 0 (
        echo PHP Lint Passed.
    ) else (
        echo PHP Lint Failed.
        SET SUCCESS=1
    )
)

IF defined RUNPHPUNIT (
    echo Executing PHPUnit...
    docker-compose -f "%FOLDERPATH%docker-compose.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/phpunit --configuration phpunit.xml"

    IF ERRORLEVEL 0 (
        echo PHPUnit Passed.
    ) else (
        echo PHPUnit Failed.
        SET SUCCESS=1
    )
)

IF defined RUNPHPCS (
    echo Executing PHP Code Sniffer...

    docker-compose -f "%FOLDERPATH%/docker-compose.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/phpcs -s"

    IF ERRORLEVEL 0 (
        echo PHPCS Passed.
    ) else (
        echo PHPCS Failed.
    )
)

IF defined RUNPHPMD (
    echo Executing PHP Mess Detector...

    docker-compose -f "%FOLDERPATH%/docker-compose.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && vendor/bin/phpmd app,config,public,resources,routes,tests text phpmd.rulesets.xml"

    IF ERRORLEVEL 0 (
        echo PHPMD Passed.
    ) else (
        echo PHPMD Failed.
    )
)

IF defined RUNESLINT (
    echo Executing ESLint...

    docker-compose -f "%FOLDERPATH%/docker-compose.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && npm run --silent lint"

    IF ERRORLEVEL 0 (
        echo ESLint Passed.
    ) else (
        echo ESLint Failed.
    )
)

IF defined RUNMOCHA (
    echo Executing Mocha and Chai...

    docker-compose -f "%FOLDERPATH%/docker-compose.yml" exec -T webserver //bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site/ && npm --silent test"

    IF ERRORLEVEL 0 (
        echo Mocha, Chai and Nightmare Passed.
    ) else (
        echo Mocha, Chai and Nightmare Failed.
        SET SUCCESS=1
    )
)

EXIT /B %SUCCESS%
