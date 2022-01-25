<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\User\UserRepositoryInterface',
            'App\Repositories\User\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\UserLog\UserLogRepositoryInterface',
            'App\Repositories\UserLog\UserLogRepository'
        );

        $this->app->bind(
            'App\Repositories\CloseDate\CloseDateRepositoryInterface',
            'App\Repositories\CloseDate\CloseDateRepository'
        );

        $this->app->bind(
            'App\Repositories\GradeLevel\GradeLevelRepositoryInterface',
            'App\Repositories\GradeLevel\GradeLevelRepository'
        );

        $this->app->bind(
            'App\Repositories\Section\SectionRepositoryInterface',
            'App\Repositories\Section\SectionRepository'
        );

        $this->app->bind(
            'App\Repositories\Location\LocationRepositoryInterface',
            'App\Repositories\Location\LocationRepository'
        );

        $this->app->bind(
            'App\Repositories\Book\BookRepositoryInterface',
            'App\Repositories\Book\BookRepository'
        );

        $this->app->bind(
            'App\Repositories\Accession\AccessionRepositoryInterface',
            'App\Repositories\Accession\AccessionRepository'
        );

        $this->app->bind(
            'App\Repositories\Author\AuthorRepositoryInterface',
            'App\Repositories\Author\AuthorRepository'
        );

        $this->app->bind(
            'App\Repositories\BookAuthor\BookAuthorRepositoryInterface',
            'App\Repositories\BookAuthor\BookAuthorRepository'
        );

        $this->app->bind(
            'App\Repositories\Subject\SubjectRepositoryInterface',
            'App\Repositories\Subject\SubjectRepository'
        );

        $this->app->bind(
            'App\Repositories\BookSubject\BookSubjectRepositoryInterface',
            'App\Repositories\BookSubject\BookSubjectRepository'
        );

        $this->app->bind(
            'App\Repositories\PatronType\PatronTypeRepositoryInterface',
            'App\Repositories\PatronType\PatronTypeRepository'
        );

        $this->app->bind(
            'App\Repositories\Patron\PatronRepositoryInterface',
            'App\Repositories\Patron\PatronRepository'
        );

        $this->app->bind(
            'App\Repositories\PatronAccount\PatronAccountRepositoryInterface',
            'App\Repositories\PatronAccount\PatronAccountRepository'
        );

        $this->app->bind(
            'App\Repositories\PatronAccountLog\PatronAccountLogRepositoryInterface',
            'App\Repositories\PatronAccountLog\PatronAccountLogRepository'
        );

        $this->app->bind(
            'App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface',
            'App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepository'
        );

        $this->app->bind(
            'App\Repositories\Borrow\BorrowRepositoryInterface',
            'App\Repositories\Borrow\BorrowRepository'
        );

        $this->app->bind(
            'App\Repositories\ReturnBook\ReturnBookRepositoryInterface',
            'App\Repositories\ReturnBook\ReturnBookRepository'
        );

        $this->app->bind(
            'App\Repositories\Reservation\ReservationRepositoryInterface',
            'App\Repositories\Reservation\ReservationRepository'
        );

        $this->app->bind(
            'App\Repositories\Penalty\PenaltyRepositoryInterface',
            'App\Repositories\Penalty\PenaltyRepository'
        );

        $this->app->bind(
            'App\Repositories\Transaction\TransactionRepositoryInterface',
            'App\Repositories\Transaction\TransactionRepository'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
