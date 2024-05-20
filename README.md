Laravel DDD Api

Hi!

This project is a simple Book Store API interface made with Laravel using DDD - Domain Driven Design.

Observations:

    - Laravel Version: 9.52.16;
    
    - PHP Version: 8.0.30;
    
    - Auth implemented using Laravel Sanctum;
    
        - You can register, login and logout;
        
    - Database operations uses MySql;

    - Business layer is at src/Domains;
    
    - Many to Many relationship between "Books" and "Stores" using the table "BookStore" as a pivot table;
    
    - Extensive feature tests implemented using PHP Unit;
    
To install it:

    - Clone the project;

    - Open a terminal, navigate to the project folder and run the command "composer install";

    - Create a database;

    - Set up your .env file referencing the database you created;

    - In the terminal, run the command "php artisan migrate";

    - If you wish, at this point you can already run the command "php artisan test" to run all the tests available in the project;
