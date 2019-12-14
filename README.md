# discovery-heap
Personal scrappy project for testing, discovery and mistakes

## Tech
- PHPUnit
- ElasticSearch
- Router ( Bramus )
- Templates ( Twig )
- ORM ( Eloquent )

## Improvements to be done
- Initialize tables ( and data ) upon initial setup
- Show session data on top navigation when user is logged in
- Organize templates into sub-directories

## Collections of steps and some ideas
- Initialize tables for database
- Initialize necessary empty directories ( like `log` and `database`), or look into alternatives to commit empty directories

## Unit tests
Running exampe unit test:
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/AppTest.php
```
