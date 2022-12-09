## HAVYN Interview Development Task

### Stack

- PHP/Laravel
- Mysql

### Running

- On the project root, run the following command (this may take several minutes to complete). 
```angular2html
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php81-composer:latest \
composer install --ignore-platform-reqs
```

-  Run the command `./vendor/bin/sail up` at the project root directory to start the application. Once the application's containers have been started you may check the app if running at: http://localhost.


### Configuring Aggregated Messages API endpoint

On the project root directory, open the file `.env` and change the value of `AGGREGATED_MESSAGES_URI` and go back to terminal where sail is running and restart sail by pressing `Ctrl+C` and run it again using `./vendor/bin/sail up` in order for the changes to take effect.

Debug logs are written at <project_root>/storage/logs/laravel.log

### Messages Endpoint

Messages endpoint is located at: `http://localhost/api/message`

### Notes

- A mock aggregated-messages endpoint is located at: `http://localhost/api/aggregated-messages`
- Database seeder for messages can be executed using the command ./vendor/bin/sail artisan db:seed --class=MessageSeeder at the project root directory
- The seeder will populate the messages table and will be processed by a background console command that listens to new records/messages and sends the aggregated messages to the destination API endpoint
- Environment variables such as Database connection details are located at <project root directory|hyavn-test>/.env file
- Payload response for messages API returns ordered UUID

```angular2html
{
"uuid": "97eda42b-5686-47b4-a4fd-e30f6f63121c"
}
```
- The background worker that processs the messages is located at: `<project_root>/app/Console/Commands/SendAggregateCommand.php`
- Logs are stored at <project_root>/storage/logs/laravel.log
