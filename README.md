## Integrated Library Management System.

#### Installation Process:
1. Run `composer install` to install all dependencies.
2. Rename/Copy `.env.example` to `.env`
3. Create a database and add your database details in `.env` file.
```
DB_CONNECTION=[mysql]
DB_HOST=[127.0.0.1]
DB_PORT=[3306]
DB_DATABASE=[DB_NAME]
DB_USERNAME=[USERNAME]
DB_PASSWORD=[PASSWORD]
```
4. Run `php artisan key:generate` to generate app key.
5. Run `php artisan migrate` to migrate the database.
6. Run `php artisan serve`.

#### Project Description:
#### An Integrated Library Management System that use for library circulation and transaction. It also provide a patron website (with different authentication) that display the patron's past and current transaction, and allow the patron to make an online book reservation. Built with HTML, CSS, jQuery-AJAX, Laravel, PHPUnit, and MySQL.


#### Admin features:
1. System Maintenance
    1. Manage User
    2. Manage Grade Level
    3. Section Management
    4. Manage Location
    5. Manage Close Date

2. Cataloging
    1. Book catalog.
    2. Accession
    3. Author
    4. Subject

3. Manage Patron
    1. Patron Type
    2. Patron
    3. Patron Account

4. Attendance Monitoring
5. Online Public Access Catalog (OPAC)

6. Circulation
    1. Borrow book
    2. Return (include penalty when patron exceed the due date of the book)
    3. Reservation
    4. Penalty List
    5. Payment for penalty
    6. Transaction List

7. Generate Reports
    1. Attendance Monitoring
    2. Top Library User
    3. Top Borrower
    4. Top Borrowed Book
    5. Author List
    6. Subject List
    7. Paid Penalty
    8. Unpaid Penalty
    9. Library Clearance
    10. Library Holdings
    11. Accession Record
    12. Acquisition Report


#### Patron website features:
1. Make Reservation
2. Attendance Monitoring Record
3. Borrow Record
4. Return Record
5. Reservation Record
6. Penalty Record
7. Transaction Record
