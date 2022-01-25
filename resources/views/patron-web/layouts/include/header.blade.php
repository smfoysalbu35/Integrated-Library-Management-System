<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{ route('patron-web.home') }}" class="navbar-brand"><b>Library Management System</b></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li @if(route('patron-web.patron-attendance-monitoring.index') == url()->current()) class="active" @endif><a href="{{ route('patron-web.patron-attendance-monitoring.index') }}">Attendance Monitoring</a></li>
                    <li @if(route('patron-web.borrows.index') == url()->current()) class="active" @endif><a href="{{ route('patron-web.borrows.index') }}">Borrow</a></li>
                    <li @if(route('patron-web.return-books.index') == url()->current()) class="active" @endif><a href="{{ route('patron-web.return-books.index') }}">Return</a></li>
                    <li @if(route('patron-web.reservations.index') == url()->current()) class="active" @endif><a href="{{ route('patron-web.reservations.index') }}">Reservation</a></li>
                    <li @if(route('patron-web.penalties.index') == url()->current()) class="active" @endif><a href="{{ route('patron-web.penalties.index') }}">Penalty</a></li>
                    <li @if(route('patron-web.transactions.index') == url()->current()) class="active" @endif><a href="{{ route('patron-web.transactions.index') }}">Transaction</a></li>
                </ul>
            </div>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ auth()->guard('patron')->user()->patron->image }}" class="user-image" alt="{{ auth()->guard('patron')->user()->patron->first_name }}">

                            <span class="hidden-xs">{{ auth()->guard('patron')->user()->patron->first_name . ' ' . auth()->guard('patron')->user()->patron->last_name }}</span>
                        </a>

                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ auth()->guard('patron')->user()->patron->image }}" class="img-circle" alt="{{ auth()->guard('patron')->user()->patron->first_name }}">
                                <p>{{ auth()->guard('patron')->user()->patron->first_name . ' ' . auth()->guard('patron')->user()->patron->last_name }} - {{ auth()->guard('patron')->user()->patron->patron_type->name }}</p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Edit Account</a>
                                </div>
                                <div class="pull-right">
                                    <form action="{{ route('patron-web.logout') }}" method="POST">
                                        @csrf

                                        <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
