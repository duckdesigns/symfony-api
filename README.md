# Symfony JSON Api

A rest API built on Symfony 4 framework and Doctrine ORM returning Json+Hal Responses. It includes endpoints to create and retrieve eventlocations at a given geolocation, events linked to these locations and posts linked to the events.
Furthermore an endpoint is present to retrieve events within a certain distance of a speccified geolocation.

## Requirements

The environment needs to feature:
- Compatibility with Symfony 4 requirements (https://symfony.com/doc/current/reference/requirements.html)
- PHP version min. 7.1.3
- A MySQL Database min. version 5.7

## Installation

1. The project can be downloaded from github <code>$ git clone https://github.com/duckdesigns/symfony-api.git</code>
2. After download, create a database and schema (assuming current directory is the root directory of the project)  <code>$ bin/console doctrine:database:create; bin/console doctrine:schema:create</code>
3. Point your webserver to the <code>/public</code> directory or start a simple php web server like <code>$php -S localhost:8000</code>
4. Now you can use any rest client to create and retrieve data from the endpoints mentioned in the documentation.

## Endpoints



## Tests 


https://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api#restful
add API Versioning

# problem validating entities -> https://blog.martinhujer.cz/symfony-forms-with-request-objects/
https://github.com/ramsey/uuid-doctrine/issues/13


// handle relationships through POST /locations/4324545/events

POST /locations
POST /locations/{id}/events
POST /events/{id}/posts

GET /locations/{id}/events?radius=10km

GET /events/{id}

geolocation https://en.wikipedia.org/wiki/Decimal_degrees -> 5 decimals


add db credentials to .env .env.test

create databse -> doctrine:database:create
doctrine:schema:create

create test database -> doctrine:database:create --env test
doctrine:schema:create --env test

missing versioning in mimetype?

