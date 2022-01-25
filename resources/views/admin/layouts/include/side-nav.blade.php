<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset(auth()->guard()->user()->image) }}" class="img-circle" alt="{{ auth()->guard()->user()->first_name }}">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->guard()->user()->first_name . ' ' . auth()->guard()->user()->last_name }}</p>
                <a href="#"></i> {{ auth()->guard()->user()->user_type === 1 ? 'Administrator' : 'Library Assistant' }}</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">

            <li @if(route('dashboard') == url()->current()) class="active" @endif>
                <a href="{{ route('dashboard') }}">
                    <span>Dashboard</span>
                </a>
            </li>

            @if(auth()->guard()->user()->user_type === 1)
                @php $routeNames = [route('users.index'), route('users.create'), route('grade-levels.index'), route('sections.index'), route('locations.index'), route('close-dates.index')]; @endphp
                <li @if(in_array(url()->current(), $routeNames)) class="active treeview" @else class="treeview" @endif>
                    <a href="#">
                        <span>System Maintenance</span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(route('users.index') == url()->current() || route('users.create') == url()->current()) class="active" @endif><a href="{{ route('users.index') }}">User</a></li>
                        <li @if(route('grade-levels.index') == url()->current()) class="active" @endif><a href="{{ route('grade-levels.index') }}">Grade Level</a></li>
                        <li @if(route('sections.index') == url()->current()) class="active" @endif><a href="{{ route('sections.index') }}">Section</a></li>
                        <li @if(route('locations.index') == url()->current()) class="active" @endif><a href="{{ route('locations.index') }}">Location</a></li>
                        <li @if(route('close-dates.index') == url()->current()) class="active" @endif><a href="{{ route('close-dates.index') }}">Close Date</a></li>
                    </ul>
                </li>

                @php $routeNames = [route('books.index'), route('accessions.index'), route('authors.index'), route('subjects.index')]; @endphp
                <li @if(in_array(url()->current(), $routeNames)) class="active treeview" @else class="treeview" @endif>
                    <a href="#">
                        <span>Cataloging</span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(route('books.index') == url()->current()) class="active" @endif><a href="{{ route('books.index') }}">Book</a></li>
                        <li @if(route('accessions.index') == url()->current()) class="active" @endif><a href="{{ route('accessions.index') }}">Accession</a></li>
                        <li @if(route('authors.index') == url()->current()) class="active" @endif><a href="{{ route('authors.index') }}">Author</a></li>
                        <li @if(route('subjects.index') == url()->current()) class="active" @endif><a href="{{ route('subjects.index') }}">Subject</a></li>
                    </ul>
                </li>

                @php $routeNames = [route('patron-types.index'), route('patrons.index'), route('patrons.create'), route('patron-accounts.index')]; @endphp
                <li @if(in_array(url()->current(), $routeNames)) class="active treeview" @else class="treeview" @endif>
                    <a href="#">
                        <span>Manage Patron</span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(route('patron-types.index') == url()->current()) class="active" @endif><a href="{{ route('patron-types.index') }}">Patron Type</a></li>
                        <li @if(route('patrons.index') == url()->current() || route('patrons.create') == url()->current()) class="active" @endif><a href="{{ route('patrons.index') }}">Patron</a></li>
                        <li @if(route('patron-accounts.index') == url()->current()) class="active" @endif><a href="{{ route('patron-accounts.index') }}">Patron Account</a></li>
                    </ul>
                </li>
            @endif

            <li @if(route('patron-attendance-monitoring.index') == url()->current()) class="active" @endif>
                <a href="{{ route('patron-attendance-monitoring.index') }}">
                    <span>Attendance Monitoring</span>
                </a>
            </li>

            <li @if(route('opac.index') == url()->current()) class="active" @endif>
                <a href="{{ route('opac.index') }}">
                    <span>OPAC</span>
                </a>
            </li>

            @if(auth()->guard()->user()->user_type === 1)
                @php
                    $routeNames = [
                        route('borrows.index'), route('borrows.create'), route('return-books.index'), route('return-books.create'),
                        route('reservations.index'), route('reservations.create'), route('penalties.index'), route('payments.index'), route('transactions.index')
                    ];
                @endphp
                <li @if(in_array(url()->current(), $routeNames)) class="active treeview" @else class="treeview" @endif>
                    <a href="#">
                        <span>Circulation</span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(route('borrows.index') == url()->current() || route('borrows.create') == url()->current()) class="active" @endif><a href="{{ route('borrows.index') }}">Borrow</a></li>
                        <li @if(route('return-books.index') == url()->current() || route('return-books.create') == url()->current()) class="active" @endif><a href="{{ route('return-books.index') }}">Return</a></li>
                        <li @if(route('reservations.index') == url()->current() || route('reservations.create') == url()->current()) class="active" @endif><a href="{{ route('reservations.index') }}">Reservation</a></li>
                        <li @if(route('penalties.index') == url()->current()) class="active" @endif><a href="{{ route('penalties.index') }}">Penalty</a></li>
                        <li @if(route('payments.index') == url()->current()) class="active" @endif><a href="{{ route('payments.index') }}">Payment</a></li>
                        <li @if(route('transactions.index') == url()->current()) class="active" @endif><a href="{{ route('transactions.index') }}">Transaction</a></li>
                    </ul>
                </li>

                @php
                    $routeNames = [
                        route('report.patron-attendance-monitoring.index'), route('report.top-library-users.index'), route('report.top-borrowers.index'), route('report.top-borrowed-books.index'),
                        route('report.author-lists.index'), route('report.subject-lists.index'), route('report.paid-penalties.index'), route('report.unpaid-penalties.index'),
                        route('report.transactions.index'), route('report.library-holdings.index'), route('report.accession-records.index'), route('report.acquisitions.index')
                    ];
                @endphp
                <li @if(in_array(url()->current(), $routeNames)) class="active treeview" @else class="treeview" @endif>
                    <a href="#">
                        <span>Reports</span>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(route('report.patron-attendance-monitoring.index') == url()->current()) class="active" @endif><a href="{{ route('report.patron-attendance-monitoring.index') }}">Attendance Monitoring</a></li>
                        <li @if(route('report.top-library-users.index') == url()->current()) class="active" @endif><a href="{{ route('report.top-library-users.index') }}">Top Library User</a></li>
                        <li @if(route('report.top-borrowers.index') == url()->current()) class="active" @endif><a href="{{ route('report.top-borrowers.index') }}">Top Borrower</a></li>
                        <li @if(route('report.top-borrowed-books.index') == url()->current()) class="active" @endif><a href="{{ route('report.top-borrowed-books.index') }}">Top Borrowed Book</a></li>
                        <li @if(route('report.author-lists.index') == url()->current()) class="active" @endif><a href="{{ route('report.author-lists.index') }}">Author List</a></li>
                        <li @if(route('report.subject-lists.index') == url()->current()) class="active" @endif><a href="{{ route('report.subject-lists.index') }}">Subject List</a></li>
                        <li @if(route('report.paid-penalties.index') == url()->current()) class="active" @endif><a href="{{ route('report.paid-penalties.index') }}">Paid Penalty</a></li>
                        <li @if(route('report.unpaid-penalties.index') == url()->current()) class="active" @endif><a href="{{ route('report.unpaid-penalties.index') }}">Unpaid Penalty</a></li>
                        <li @if(route('report.library-holdings.index') == url()->current()) class="active" @endif><a href="{{ route('report.library-holdings.index') }}">Library Holdings</a></li>
                        <li @if(route('report.library-clearance.index') == url()->current()) class="active" @endif><a href="{{ route('report.library-clearance.index') }}">Library Clearance</a></li>
                        <li @if(route('report.accession-records.index') == url()->current()) class="active" @endif><a href="{{ route('report.accession-records.index') }}">Accession Record</a></li>
                        <li @if(route('report.acquisitions.index') == url()->current()) class="active" @endif><a href="{{ route('report.acquisitions.index') }}">Acquisition</a></li>
                    </ul>
                </li>




            @if(auth()->guard()->user()->user_type === 2)
                @php $routeNames = [route('borrows.create'), route('return-books.create'), route('reservations.create'), route('payments.index')]; @endphp
                <li @if(in_array(url()->current(), $routeNames)) class="active treeview" @else class="treeview" @endif>
                    <a href="#">
                        <span>Circulation</span>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(route('borrows.create') == url()->current()) class="active" @endif><a href="{{ route('borrows.create') }}">Borrow</a></li>
                        <li @if(route('return-books.create') == url()->current()) class="active" @endif><a href="{{ route('return-books.create') }}">Return</a></li>
                        <li @if(route('reservations.create') == url()->current()) class="active" @endif><a href="{{ route('reservations.create') }}">Reservation</a></li>
                        <li @if(route('SslCommerzPayment.index') == url()->current()) class="active" @endif><a href="{{ route('SslCommerzPayment.index') }}">Payment</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </section>
</aside>
@endif
