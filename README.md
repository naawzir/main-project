# TCP

## Setup

### Database

The new database will share data with the marketplace. To reflect the production database setup
the TCP and marketplace databases have to run on the same server.

The docker image has been modified to include the marketplace database.

#### Requirements

* Docker & Docker Compose
* A copy of the marketplace git repository
* The `IAMPROPERTY_ROOT` environment variable set to the path to the marketplace, whereever that is on your machine

#### Initial setup

There is some manual setup you will need to run the first time you run the new image, and anytime you recreate your database.

1. Run `docker-compose up -d` to start the docker environment
2. Using a mysql client create the marketplace database, normally called `isg`.
3. Run `docker-compose run artisan migrate` to setup the marketplace database

