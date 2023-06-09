# MyTheresa Product API

## Introduction

This is a mini rest api that provides a single endpoint to fetch a list of seeded products, filter by category and price. Also applies discount were applicable

## Table of Contents
1. <a href="#how-it-works">How it works</a>
2. <a href="#technology-stack">Technology Stack</a>
3. <a href="#application-features">Application Features</a>
4. <a href="#api-endpoints">API Endpoints</a>
5. <a href="#setup">Setup</a>
6. <a href="#explanation">Explanation Of Decision Taken</a>
7. <a href="#author">Author</a>
8. <a href="#license">License</a>

## Technology Stack
  - [PHP](https://www.php.net)
  - [Laravel](https://laravel.com)
  - [MySQL](https://www.mysql.com)
  - [Docker](https://docs.docker.com/desktop/)
  ### Testing tools
  - [PHPUnit](https://phpunit.de) 

## Application Features
* Ability to view list of products
* Ability to filter products using category query param
* Ability to filter products using priceLessThan query param
* Ability to see applied discount details on product price
* Ability to paginate result using per_page quary param

## API Endpoints
### Base URL = http://localhost:5454/

#### NOTE : per_page has a default value of 5 as required in requirement

Method | Route | Description | Query Params
--- | --- | ---|---
`GET` | `/api/products` | View all products available |  priceLessThan, category, per_page |

For examples of payloads, response and available query. Visit [The Product API Postman Collection](https://documenter.getpostman.com/view/11352884/2s8YzRz3Cc)

## Setup
This instruction will get the project working on your local machine for DEVELOPMENT and TESTING purposes.

  #### Dependencies
  - [Docker](https://docs.docker.com/desktop/)
 
  #### Getting Started
  - Install and setup docker
  - Open terminal and run the following commands
    ```
    //clone repository
    $ git clone git@github.com:harmlessprince/mytheresa-assessment.git
    
    //move into clone repository
    $ cd mytheresa-assessment
    
    //create enviroment file
    $ touch .env
    
    //copy enviroment variables from .env.example to .env(DON'T CHANGE ANY VALUE except you have expertise in docker)
    $ cp .env.example .env
    
    //Build docker files 
    $ docker-compose build mytheresa
    
    //start application to see logs in none detached mode (recommended)
    $ docker-compose up
    
    //Or start application in detached mode (No Log, Not Recommended)
    $ docker-compose up -d
    
    NOTE: Open a new terminal and run below command if you start your app in none detached mode
    
    //Generate APP Key
    $ docker-compose exec mytheresa php artisan key:generate
    
    //migrate database and seed data 
    $ docker-compose exec mytheresa php artisan migrate --seed
    ```
    
    If all goes well 
  - Visit http://localhost:5454/ on your browser to view laravel home
  - Visit http://localhost:8200/ on your browser to view database using phpmyadmin
  

  ### Testing
  ```
  $ docker-compose exec mytheresa php artisan test
  ```
  If correctly setup, all tests should pass
  
  #### Start Application
  
  ```
  $ docker-compose up
  ```
  
  #### Stop Application
  
  ```
  $ docker-compose down
  ```
## Explanation
    1. Invokable Controller: Since our controller only performs a single action, It makes sense to use an invokable 
       class, which also helps us maintain the Single Responsibility Principle in SOLID. 
       Also, note the naming convention used.
    2. Product Discount Libary is a mini class that calculates discounts according to the product supplied.
        a. The Constructor: 
           In the product discount library constructor, we initiated the "discounts" variable to 
           hold available discounts; this is done to have a single instance of the discount value while calculating 
           the "discount" of the product supplied. If for any reason, these values are moved to the database level, 
           the discounts table will only be queried once to get the available discounts, which in turn reduces the 
           number of the database query to just 2(getting products and discounts).
           
        b. pickMaxDiscount Method: 
           According to the requirement, The maximum discount should always be applied 
           when more than one discount is available. By filtering the discounts using SKU and category we get an array 
           of discount which the laravel collection max helper is used to pick the maximum discount percentage value if 
           discounts returned is more than one otherwise it returns null.
    3. A constant called CURRENCY is created on the product model since it is stated the currency will always be in euros(EUR) 
        
## Author
 Name: Adewuyi Taofeeq <br>
 Email: realolamilekan@gmail.com <br>
 LinkenIn:  <a href="#license">Adewuyi Taofeeq Olamikean</a> <br>

## License
ISC
