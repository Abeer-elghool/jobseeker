# Job Seeker Application

This application allows job seekers to apply for jobs, and admins to manage job postings. The application supports the following features:

## Prerequisites
 - PHP >= 8.1 
 - Composer
 - MySQL

## Starting the Application

```bash
php artisan app:start
```

This command will:

 - Install composer dependencies
 - Run database migrations
 - Generate the application key
 - Start the queue worker
 - Create the storage link
 - Start the development server

## Set up the environment:
```bash
cp .env.example .env
``` 

## API Documentation:
To access the API endpoints, import the Api Collection.postman_collection.json file into Postman. This file contains all the API endpoints for interacting with the application.


