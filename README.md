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
2. After download, run composer install from the root directory.
3. Change the database credentials in the <code>.env</code> file in the root directory to your DB connection. The name of the database should be "symfony_api". 
4. Create a database and schema (assuming current directory is the root directory of the project)  
<code>$ bin/console doctrine:database:create; bin/console doctrine:schema:create</code>
5. Point your webserver to the <code>/public</code> directory or navigate to the directory and start a simple php web server like <code>$php -S localhost:8000</code>
6. Now you can use any rest client to create and retrieve data from the endpoints mentioned below.

## Endpoints

Endpoints return the content type "application/json" except if content negotiation fails. In that case it return an error message in the format "text/html".

|Route|Method|Description|Example|
|-----|------|-----------|-------|
|<code>/locations/{id}</code>|GET|*retrieve a location with the id {id} (uuid v4)*|{"id":"cf80a36f-2c43-45c0-80af-8bf3675d63a8","title":"Berghain","latitude":"41.40338","longitude":"2.17403","events":{}}|
|<code>/events/{id}</code>|GET|*retrieve an event with the id {id} (uuid v4)*|{"id":"90e519c7-bc90-45bb-aa34-c1f954721f29","title":"Konny Kleinkunstpunk","location":{"id":"d6cdc05c-ded3-412c-9dcc-3d957bb5d422","title":"KVU Berlin","latitude":"52.53579","longitude":"13.45193","events":{}},"posts":{}}|
|<code>/events</code>|GET|*retrieve all events*|[{"id":"03257ad2-8792-49d2-92c9-90cf871ae9ba","title":"Kit Kat Club","location":{"id":"8d49a877-d266-4675-ae42-4b5985beaf8a","title":"Berghain","latitude":"52.51082","longitude":"13.44235","events":{}},"posts":{}},{"id":"134bb51f-f6cc-40af-9be3-e9beb9e8850d","title":"Konny Kleinkunstpunk","location":{"id":"3ddce740-c200-4893-9bdb-5599f1f1a9e9","title":"KVU Berlin","latitude":"52.53579","longitude":"13.45193","events":{}},"posts":{}}]|
|<code>/locations/{id}/events</code>|GET|*retrieve all events from the location with the id {id} (uuid v4)|[{"id":"htgtr432-8792-49d2-92c9-90cgh71ae9ba","title":"Singabend der Katholischen Kirche","location":{"id":"8d49a877-d266-4675-ae42-4b5985beaf8a","title":"Berghain","latitude":"52.51082","longitude":"13.44235","events":{}},"posts":{}}, [{"id":"03257ad2-8792-49d2-92c9-90cf871ae9ba","title":"Kit Kat Club","location":{"id":"8d49a877-d266-4675-ae42-4b5985beaf8a","title":"Berghain","latitude":"52.51082","longitude":"13.44235","events":{}},"posts":{}}]|
|<code>/events/{id}/posts</code>|GET|*retrieve all posts for the event with the id {id} (uuid v4)|[{"id":"226c7c33-5368-4a0c-958a-b46a6c9dffd5","title":"Tolle Party","content":"Hab die ganze Nacht getanzt :-)"},{"id":"ea412818-a378-4813-994b-e58ba6adfa73","title":"Geiles Event","content":"Rhabarber, Rhararber"}]|
|<code>/events/{eventid}/posts/{postid}</code>|GET|*retrieve the post with the id {postid} for the event with the id {eventid} (uuid v4)|{"id":"226c7c33-5368-4a0c-958a-b46a6c9dffd5","title":"Tolle Party","content":"Hab die ganze Nacht getanzt :-)"}|
|<code>/events?max-distance-from={latitude},{longitude},{distance}</code>|*retrieve all events within a {distance} (int) Km radius from the point with the coordinates {latitude} (float <= 90.00000, >= -90.00000, 5 digits precision ) and {longitude} (float <= 180.00000, >= -180.00000, 5 digits precision)||
|<code>/locations</code>|POST|*create a location at a given geolocation, returns the location of the created resource in the header field "Location"|{"title":"Berghain Berlin","latitude": 52.51082,"longitude": 13.44235}|
|<code>/locations/{id}/events</code>|POST|*create an event at the location with the id {id} (uuid v4)|{"title":"Arena Club"}|
|<code>  /events/{id}/posts</code>|*creates a post for the event with the id {id} (uuid v4)*|{"title":"Tolle Party","content":"Mann hab ich gefeiert, habt ihr die Show gesehen?"}|

## Tests 

1. In order to run the test suite, change the database credentials in the <code>.env.test</code> file in the root directory to your DB connection. The name of the database should be "symfony_api_test".
2. Run <code>bin/console doctrine:database:create --env test; bin/console doctrine:schema:create --env test</code> from the root directory
2. Run <code>bin/phpunit</code> from the root directory.

# Problems I ran into, things, I would want to fix
First the most obvious:
- I didn't find the time to incorporate HATEOAS links event though I prepared for it. I would have gone with Hal but used something else than the standard HATEOAS+JmsSerializer bundle as I know it to have performance problems when it comes to larger entities.
- I didn't find time to incorporate versioning - I would have gone with the mimetype way as it seems to be the most restful way and is easy to incorporate in conent negotiation (<link>https://www.baeldung.com/rest-versioning</link>, <link>https://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api#restful</link>).
- I didn't like the way symfony forms, which I used to validate my entities required my entity members to be nullable and without type hints, which put them in a potentially invalid state. I solved this with DTOs like this guy suggested (<link>https://blog.martinhujer.cz/symfony-forms-with-request-objects/</link>).
- I didn't find time to write proper tests. i wrote a few functional tests but even they only tested the "happy" path. More tests to test what happened on incorrect input should be written".
- I didn't find the time to write unit tests. Ideally test coverage should be at 100% before you unleash something at the public.
- I liked the idea of using integers internally in the DB as primary key and then using UUIDs for the rest of the world, unfortunately a bug prevented me from doing that (apparently it's not possible at the moment using two value generators in the same entity - I could have created the UUID manually though...) and I didn't pursue the matter any further because of time concerns.
- This API documentation will probably do the trick but could look nicer - If I had the time I would fork <link>https://github.com/harmbandstra/swagger-ui-bundle</link> and fix a few small things to make it work out of the box in Symfony 4. Swagger looks great!.
- Last but not least: In a real project I might rather use Symfony Components as I see fit and use more vanilla PHP. Symfony is great for prototyping as one can create something really fast but I find debugging becomes difficult if you don't know the architecture of Symfony intimately. Also performace is gained by not installing a large bundle every time one needs a small piece of functionality (one of the reasons why I didn't one of the many available geolocation bundles but did that in one SQL call.
