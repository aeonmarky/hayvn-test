## HAVYN Interview Development Task

### Stack

- PHP/Laravel
- Mysql

### Running

Go to the project root directory (./hayvn-test) and run ./vendor/bin/sail up to start the application

### Configuring Aggregated Messages API endpoint

On the project root directory, open the file .env and change the value of AGGREGATED_MESSAGES_URI

### Messages Endpoint

Messages endpoint is located at http://localhost/api/message

### Notes

- A mock aggregated-messages endpoint is located at http://localhost/api/aggregated-messages
- Database seeder for messages can be executed using the command ./vendor/bin/sail artisan db:seed --class=MessageSeeder at the project root directory
- The seeder will populate the messages table and will be processed by a background console command that listens to new records/messages and sends the aggregated messages to the destination API endpoint
- Environment variables such as Database connection details are located at <project root directory|hyavn-test>/.env file
