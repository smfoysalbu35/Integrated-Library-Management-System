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
1. System Maintenance:
i.	User Management.
ii.	Manage Grade Level.
iii.	Section Management.
iv.	Manage Location.
v.	Manage Close Date.

2. Cataloging:
i.	Book catalog.
ii.	Accession.
iii.	Author.
iv.	Subject.

3. Manage Patron:
i.	Patron Type.
ii.	Patron. (add a patron)
iii.	Patron Account.

4. Attendance Monitoring.
5. Online Public Access Catalog (OPAC).

6. Circulation:
i.	Borrow book.
ii.	Return. (include penalty when patron exceed the due date of the book)
iii.	Reservation.
iv.	Penalty List.
v.	Payment for penalty.
vi.	Transaction List.

7. Generate Reports:
i.	Attendance Monitoring.
ii.	Top Library User.
iii.	Top Borrower.
iv.	Top Borrowed Book.
v.	Author List.
vi.	Subject List.
vii.	Paid Penalty.
viii.	Unpaid Penalty.
ix.	Library Clearance
x.	Library Holdings.
xi.	Accession Record.
xii.	Acquisition Record.

#### Patron website features:
i.	Make Reservation.
ii.	Attendance Monitoring Record.
iii.	Borrow Record.
iv.	Return Record.
v.	Reservation Record.
vi.	Penalty Record.
vii.	Transaction Record.
