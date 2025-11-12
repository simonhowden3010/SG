# SG Laravel Challenge
Thanks for considering me for the role!

My approach to this was to make this as easy as possible to run. I'll make a terminal command instead of an API endpoint because a CSV was provided specifically. To embellish, I'll output a CSV file as well as output the JSON in the terminal. 

I also added some simple unit tests for completion.

I added the CSV reading code separately in the service incase an API endpoint were to use it later and separated it from CSV related code.

## Building and running the project:
I used docker in a way that should hopefully work on mac and windows but ive only had time to check on mac

There should be no need to create a .env file manually

There is some left over files from the scoffolding done by laravel (e.g. a user model) and because of the speedy docker solution there is a nested app directory (in a proper project this would be fixed to the root directory).

In a terminal, in the root directory of the repo, you can run:
* ./scripts/build

A server should now be running ready to accept the artisan command

I keep the examples.csv file in the storage folder, and output the output.csv also to the storage folder

I also assume we should output all people but just leave missing names etc as a blank 'cell' if it were opened in sheets

## Running the unit test
In a separate terminal, in the root directory of the repo, you can run:
* ./scripts/artisan test

## Running the parser example
* ./scripts/artisan csv:write storage/examples.csv storage/output.csv

### Code notes
The majority of the important code is in the service (App/Services/PeopleService). I use a service 'people' with a console command (Console/ReadCSV) and I use PHPUnit for the tests. 

### Regarding usage of AI
The majority of the Docker-related scripts are from previous work I've done, however some AI assistance was used for the entrypoint script (entrypoint.sh)