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
    


