<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\GradeLevel' => 'App\Policies\GradeLevelPolicy',
        'App\Models\Section' => 'App\Policies\SectionPolicy',
        'App\Models\Location' => 'App\Policies\LocationPolicy',
        'App\Models\CloseDate' => 'App\Policies\CloseDatePolicy',
        'App\Models\Book' => 'App\Policies\BookPolicy',
        'App\Models\Accession' => 'App\Policies\AccessionPolicy',
        'App\Models\Author' => 'App\Policies\AuthorPolicy',
        'App\Models\BookAuthor' => 'App\Policies\BookAuthorPolicy',
        'App\Models\Subject' => 'App\Policies\SubjectPolicy',
        'App\Models\BookSubject' => 'App\Policies\BookSubjectPolicy',
        'App\Models\PatronType' => 'App\Policies\PatronTypePolicy',
        'App\Models\Patron' => 'App\Policies\PatronPolicy',
        'App\Models\PatronAccount' => 'App\Policies\PatronAccountPolicy',
        'App\Models\Borrow' => 'App\Policies\BorrowPolicy',
        'App\Models\ReturnBook' => 'App\Policies\ReturnBookPolicy',
        'App\Models\Reservation' => 'App\Policies\ReservationPolicy',
        'App\Models\Penalty' => 'App\Policies\PenaltyPolicy',
        'App\Models\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\UserLog' => 'App\Policies\UserLogPolicy',
        'App\Models\PatronAccountLog' => 'App\Policies\PatronAccountLogPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
