# discovery-heap
Personal scrappy project for testing, discovery and mistakes

## Tech
- PHPUnit
- Search ( ElasticSearch )
- Router ( Bramus )
- Templates ( Twig )
- ORM ( Eloquent )

## Next tech
- Message Broker ( RabbitMQ )
- Use of GitHub Actions
- Something using Publish/Subscribe ( RabbitMQ or Redis )

## Improvements to be done
- Initialize tables ( and data ) upon initial setup
- Show session data on top navigation when user is logged in
- Organize templates into sub-directories ( where necessary )
- Move all interactions with database to models and use ORM
- Look into possibility of some migration scripts using current ORM ( noticed some mentions that there could be some restrictions using Eloquent outside Laravel )
- Update remaining routes to use route - controller/method
- Create method to load templates and handle errors during that action
- Add more use to ElasticSearch, more elaborate search for articles
- Add more testes for Unit Tests

## Unit tests
Running example unit test:
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/AppTest.php
```
## ElasticSearch
For debugging to see all indexed documents, use:
```
http://localhost:9200/discovery-elastic-index/_search?pretty=true&q=*:*
```
