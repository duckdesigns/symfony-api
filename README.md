#Symfony JSON Api

#references

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

