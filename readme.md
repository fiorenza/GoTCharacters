# Game of Thrones Characters

## About the project

I choose to use Symfony Console bundle to develop the command line. I did this because Symfony is the framework that I used to work, also, it is a very extendable framework that allow us to improve and upgrade the applications in many ways.

I added a Docker container to run the project, here is the steps to run the command:

1) Go to folder `docker`
2) Start the container `docker-compose up`
3) Enter in the container `docker exec -it challenge-php bash`
4) Install the project dependencies: `composer install`
5) Run the command: `php bin/console app:send-character-quotes`, If you don't want to get all characters you can pass a limit after the command: `php bin/console app:send-character-quotes 10`, this will import only 10 characters


About the API to get quotes, their documentation say that we can get all quotes for a character if you don't pass the limit, but the API is returning only one, so in the part of the code that I get the quotes I added a `/100` at the end of URL, this need to be removed once the API return all quotes.

About the difficulties of the test. I had never worked with GraphQL before, so this was the most difficult part of the test, I read their documentation, read about some libraries to use with PHP, but looks like that these libraries create the queries only to get data from GraphQL APIs, not to send. I used the examples that you sent, inspected the structure by Insomnia, and I created the query to send the items. I believe that exists some way of use a library to improve the queries, I am pretty sure that with more time and more experience this can be done in a better way, so is the first improvement that must be done in this command.

Next Steps:
1) Improvements the GraphQL integration
2) Send API token to .env file