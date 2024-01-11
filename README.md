
# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Clone the repository

   https://github.com/Yosiak-alv/SolidRecordRH.git

Switch to the repo folder

    cd SolidRecordRH

Install all the dependencies using composer

    composer install

Install all the FrontEnd Dependencies with your package manager (ex: npm)

    npm install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve
    npm run dev

You can now access the server at http://localhost:8000
