# _Shoe Stores App_

#### _An app for search shoe brands and stores, Week 4 Code Review, 3.04.2016_

### By _**Joseph Karasek**_

## Description

_This web app is designed to collect information on shoe brands, shoe stores, and which stores carry which brands. This is the final code review exercise for the Epicodus Level 2 PHP class._

_The goal of this code review is to show understanding and competency with php and the Silex micro-framework, including the ability to create, store, and delete instants of a given class, use of databases to store information using SQL, and creating and using join tables and searches._

#### Program Requirements:

* Write a program to list out local shoe stores and the brands of shoes they carry. Make a Store class and a Brand class.
* Create a database called shoes and a testing database called shoes_test, and remember to follow proper naming conventions for your tables and columns. As you create your tables, copy all MySQL commands into your readme file.
* Build full CRUD functionality for Stores. Create, Read (all and singular), Update, Delete (all and singular).
* Allow a user to create Brands that are assigned to a Store. Don't worry about building out updating or deleting for brands.
* There is a many-to-many relationship between brands and shoe stores, so many shoe stores can carry many brands and a brand of shoes can be carried in many stores. Create a join table to store these relationships.
* When a user is viewing a single store, list out all of the brands that have been added so far to that store and allow them to add a brand to that store. Create a method to get the brands sold at a store, and use a join statement in it.
* When a user is viewing a single Brand, list out all of the Stores that carry that brand and allow them to add a Store to that Brand. Use a join statement in this method too.
* When you are finished with the assignment, make sure to export your databases and commit the .sql files for both the app database and the test database.

#### Objectives

* Do the database table and column names follow proper naming conventions?
* Are all tests passing?
* Did you write the test methods and make them pass before starting on Silex routes for each class?
* Does your Store class have all CRUD methods implemented in your app? That includes: Create, Read (all and singular) Update and Delete (all and singular).
* Are you able to view all the brands sold at a single store, as well as all the stores selling a particular brand?
* Is the many-to-many relationship set up correctly in the database?
* Did you use join statements?
* Are the commands on how to setup the database in the README? Did you include the .sql files?
* Were Twig template files used for all Silex pages?
* Is your logic easy to understand?
* Did you use descriptive variable names?
* Does your code have proper indentation and spacing?
* Did you include a README with a description of the program, setup instructions, a copyright, a license, and your name?
* Is the project tracked in Git, and did you regularly make commits with clear messages that finish the phrase "This commit will…"?

## Setup/Installation Requirements

1. _Fork and clone this repository from_ [gitHub](https://github.com/joekarasek/epicodus-php-address_book.git).
2. Navigate to the root directory of the project in which ever CLI shell you are using and run the command: __composer install__ .
3. Create a local server in the /web directory within the project folder using the command: __php -S localhost:8000__ (assuming you are using a mac).
4. Open the directory http://localhost:8000 in any standard web browser.

## Known Bugs

_This application is not fully designed and may have unknown bugs._

_Currently, instants of Contact are assigned a random 6 digit ID. There is a tiny chance that multiple contacts may share an ID, deleting one of those contacts will cause the first (in order of creation) contact with that ID to be deleted._

## Support and contact details

_If you have any questions, concerns, or feedback, please contact the author through_ [gitHub](https://github.com/joekarasek/epicodus-php-address_book.git).

## Technologies Used

_This web application was created using the_  [Silex micro-framework](http://silex.sensiolabs.org/)_, as well _[Twig](http://twig.sensiolabs.org/), a template engine for php.

### License

MIT License.

Copyright (c) 2016 **_Joseph Karasek_**
