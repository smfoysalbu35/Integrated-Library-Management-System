<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/example1', 'SslCommerzPaymentController@exampleEasyCheckout');
Route::get('/example2', 'SslCommerzPaymentController@exampleHostedCheckout');

Route::post('/pay', 'SslCommerzPaymentController@index');
Route::post('/pay-via-ajax', 'SslCommerzPaymentController@payViaAjax');

Route::post('/success', 'SslCommerzPaymentController@success');
Route::post('/fail', 'SslCommerzPaymentController@fail');
Route::post('/cancel', 'SslCommerzPaymentController@cancel');

Route::post('/ipn', 'SslCommerzPaymentController@ipn');
/**
 * Patron web routes
 */
Route::namespace('patron')->prefix('patron-web')->group(function() {
    Route::get('/', 'AuthController@index')->name('patron-web.login.index')->middleware('guest:patron');
    Route::post('/login', 'AuthController@login')->name('patron-web.login')->middleware('guest:patron');
    Route::post('/logout', 'AuthController@logout')->name('patron-web.logout')->middleware('patron');

    Route::get('/register', 'RegisterController@index')->name('patron-web.register.index')->middleware('guest:patron');
    Route::post('/register', 'RegisterController@register')->name('patron-web.register')->middleware('guest:patron');

    Route::middleware('patron')->group(function() {
        Route::prefix('home')->group(function() {
            Route::get('/', 'HomeController@index')->name('patron-web.home');
            Route::get('/reservations', 'HomeController@list')->name('patron-web.home.reservations.list');
            Route::post('/reservations', 'HomeController@store')->name('patron-web.home.reservations.store');
        });

        Route::get('/patron-attendance-monitoring', 'PatronAttendanceLogController@index')->name('patron-web.patron-attendance-monitoring.index');
        Route::get('/borrows', 'BorrowController@index')->name('patron-web.borrows.index');
        Route::get('/return-books', 'ReturnBookController@index')->name('patron-web.return-books.index');
        Route::get('/reservations', 'ReservationController@index')->name('patron-web.reservations.index');
        Route::get('/penalties', 'PenaltyController@index')->name('patron-web.penalties.index');

        Route::prefix('transactions')->group(function() {
            Route::get('/', 'TransactionController@index')->name('patron-web.transactions.index');
            Route::get('/{id}', 'TransactionController@show')->name('patron-web.transactions.show');
        });
    });
});

/**
 * Admin routes
 */
Route::get('/', 'AuthController@index')->name('login.index')->middleware('guest');
Route::post('/login', 'AuthController@login')->name('login')->middleware('guest');
Route::post('/logout', 'AuthController@logout')->name('logout')->middleware('auth');

Route::get('/register', 'RegisterController@index')->name('register.index')->middleware('guest');
Route::post('/register', 'RegisterController@register')->name('register')->middleware('guest');

Route::resource('/patron-attendance-monitoring', 'PatronAttendanceLogController')->only(['index', 'store']);

Route::get('/opac', 'OpacController@index')->name('opac.index');

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::resource('/users', 'UserController')->except(['show']);

    Route::get('/close-dates/get', 'CloseDateController@get')->name('close-dates.get');
    Route::get('/close-dates/search', 'CloseDateController@search')->name('close-dates.search');
    Route::apiResource('/close-dates', 'CloseDateController');

    Route::get('/grade-levels/get', 'GradeLevelController@get')->name('grade-levels.get');
    Route::get('/grade-levels/search', 'GradeLevelController@search')->name('grade-levels.search');
    Route::apiResource('/grade-levels', 'GradeLevelController');

    Route::get('/sections/get', 'SectionController@get')->name('sections.get');
    Route::get('/sections/search', 'SectionController@search')->name('sections.search');
    Route::apiResource('/sections', 'SectionController');

    Route::get('/locations/get', 'LocationController@get')->name('locations.get');
    Route::get('/locations/search', 'LocationController@search')->name('locations.search');
    Route::apiResource('/locations', 'LocationController');

    Route::get('/books/get', 'BookController@get')->name('books.get');
    Route::get('/books/search', 'BookController@search')->name('books.search');
    Route::apiResource('/books', 'BookController');

    Route::get('/authors/get', 'AuthorController@get')->name('authors.get');
    Route::get('/authors/search', 'AuthorController@search')->name('authors.search');
    Route::apiResource('/authors', 'AuthorController');

    Route::get('/book-authors/search', 'BookAuthorController@search')->name('book-authors.search');
    Route::apiResource('/book-authors', 'BookAuthorController');

    Route::get('/subjects/get', 'SubjectController@get')->name('subjects.get');
    Route::get('/subjects/search', 'SubjectController@search')->name('subjects.search');
    Route::apiResource('/subjects', 'SubjectController');

    Route::get('/book-subjects/search', 'BookSubjectController@search')->name('book-subjects.search');
    Route::apiResource('/book-subjects', 'BookSubjectController');

    Route::get('/accessions/get', 'AccessionController@get')->name('accessions.get');
    Route::get('/accessions/search', 'AccessionController@search')->name('accessions.search');
    Route::get('/accessions/count', 'AccessionController@count')->name('accessions.count');
    Route::apiResource('/accessions', 'AccessionController');

    Route::get('/patron-types/get', 'PatronTypeController@get')->name('patron-types.get');
    Route::get('/patron-types/search', 'PatronTypeController@search')->name('patron-types.search');
    Route::apiResource('/patron-types', 'PatronTypeController');

    Route::resource('/patrons', 'PatronController')->except(['show']);

    Route::resource('/patron-accounts', 'PatronAccountController')->only(['index', 'destroy']);

    Route::post('/borrows/patron-record', 'BorrowController@getPatronRecord')->name('borrows.patron-record');
    Route::resource('/borrows', 'BorrowController')->only(['index', 'create', 'store']);

    Route::resource('/return-books', 'ReturnBookController')->only(['index', 'create', 'store']);

    Route::post('/reservations/patron-record', 'ReservationController@getPatronRecord')->name('reservations.patron-record');
    Route::resource('/reservations', 'ReservationController')->only(['index', 'create', 'store']);

    Route::post('/penalties/patron-record', 'PenaltyController@getPatronRecord')->name('penalties.patron-record');
    Route::get('/penalties', 'PenaltyController@index')->name('penalties.index');

    Route::resource('/payments', 'PaymentController')->only(['index', 'store']);

    Route::get('/example1', 'SslCommerzPaymentController@exampleEasyCheckout');

    Route::post('/pay', 'SslCommerzPaymentController@index');
    Route::post('/pay-via-ajax', 'SslCommerzPaymentController@payViaAjax');

    Route::post('/success', 'SslCommerzPaymentController@success');
    Route::post('/fail', 'SslCommerzPaymentController@fail');
    Route::post('/cancel', 'SslCommerzPaymentController@cancel');

    Route::post('/ipn', 'SslCommerzPaymentController@ipn');


    Route::resource('/transactions', 'TransactionController')->only(['index', 'show']);

    Route::namespace('report')->prefix('report')->group(function() {
        Route::prefix('patron-attendance-monitoring')->group(function() {
            Route::get('/', 'PatronAttendanceLogController@index')->name('report.patron-attendance-monitoring.index');
            Route::post('/', 'PatronAttendanceLogController@store')->name('report.patron-attendance-monitoring.store');
            Route::get('/print', 'PatronAttendanceLogController@print')->name('report.patron-attendance-monitoring.print');
        });

        Route::prefix('top-library-users')->group(function() {
            Route::get('/', 'TopLibraryUserController@index')->name('report.top-library-users.index');
            Route::post('/', 'TopLibraryUserController@store')->name('report.top-library-users.store');
            Route::get('/print', 'TopLibraryUserController@print')->name('report.top-library-users.print');
        });

        Route::prefix('top-borrowers')->group(function() {
            Route::get('/', 'TopBorrowerController@index')->name('report.top-borrowers.index');
            Route::post('/', 'TopBorrowerController@store')->name('report.top-borrowers.store');
            Route::get('/print', 'TopBorrowerController@print')->name('report.top-borrowers.print');
        });

        Route::prefix('top-borrowed-books')->group(function() {
            Route::get('/', 'TopBorrowedBookController@index')->name('report.top-borrowed-books.index');
            Route::post('/', 'TopBorrowedBookController@store')->name('report.top-borrowed-books.store');
            Route::get('/print', 'TopBorrowedBookController@print')->name('report.top-borrowed-books.print');
        });

        Route::prefix('author-lists')->group(function() {
            Route::get('/', 'AuthorListController@index')->name('report.author-lists.index');
            Route::get('/print', 'AuthorListController@print')->name('report.author-lists.print');
        });

        Route::prefix('subject-lists')->group(function() {
            Route::get('/', 'SubjectListController@index')->name('report.subject-lists.index');
            Route::get('/print', 'SubjectListController@print')->name('report.subject-lists.print');
        });

        Route::prefix('paid-penalties')->group(function() {
            Route::get('/', 'PaidPenaltyController@index')->name('report.paid-penalties.index');
            Route::post('/', 'PaidPenaltyController@store')->name('report.paid-penalties.store');
            Route::get('/print', 'PaidPenaltyController@print')->name('report.paid-penalties.print');
        });

        Route::prefix('unpaid-penalties')->group(function() {
            Route::get('/', 'UnpaidPenaltyController@index')->name('report.unpaid-penalties.index');
            Route::post('/', 'UnpaidPenaltyController@store')->name('report.unpaid-penalties.store');
            Route::get('/print', 'UnpaidPenaltyController@print')->name('report.unpaid-penalties.print');
        });

        Route::prefix('transactions')->group(function() {
            Route::get('/', 'TransactionController@index')->name('report.transactions.index');
            Route::post('/', 'TransactionController@store')->name('report.transactions.store');
            Route::get('/print', 'TransactionController@print')->name('report.transactions.print');

            Route::get('/{id}', 'TransactionController@show')->name('report.transactions.show');
            Route::get('/{id}/print-details', 'TransactionController@printDetail')->name('report.transactions.print-details');
        });

        Route::prefix('library-clearance')->group(function() {
            Route::get('/', 'LibraryClearanceController@index')->name('report.library-clearance.index');
            Route::post('/', 'LibraryClearanceController@store')->name('report.library-clearance.store');
            Route::get('/print', 'LibraryClearanceController@print')->name('report.library-clearance.print');

            Route::get('/{id}', 'LibraryClearanceController@show')->name('report.library-clearance.show');
            Route::get('/{id}/print-details', 'LibraryClearanceController@printDetail')->name('report.library-clearance.print-details');
        });

        Route::prefix('library-holdings')->group(function() {
            Route::get('/', 'LibraryHoldingController@index')->name('report.library-holdings.index');
            Route::get('/print', 'LibraryHoldingController@print')->name('report.library-holdings.print');
        });

        Route::prefix('accession-records')->group(function() {
            Route::get('/', 'AccessionRecordController@index')->name('report.accession-records.index');
            Route::get('/print', 'AccessionRecordController@print')->name('report.accession-records.print');
        });

        Route::prefix('acquisitions')->group(function() {
            Route::get('/', 'AcquisitionController@index')->name('report.acquisitions.index');
            Route::post('/', 'AcquisitionController@store')->name('report.acquisitions.store');
            Route::get('/print', 'AcquisitionController@print')->name('report.acquisitions.print');
        });
    });

    Route::get('/user-logs', 'UserLogController@index')->name('user-logs.index');
    Route::get('/patron-account-logs', 'PatronAccountLogController@index')->name('patron-account-logs.index');
});
