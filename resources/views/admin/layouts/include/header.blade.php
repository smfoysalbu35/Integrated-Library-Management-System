<header class="main-header">

    <a href="#" class="logo">
        <span class="logo-mini"><b>ILMS</b></span>
        <span class="logo-lg"><b>I.L Management</b></span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset(auth()->guard()->user()->image) }}" class="user-image" alt="{{ auth()->guard()->user()->first_name }}">
                        <span class="hidden-xs">{{ auth()->guard()->user()->first_name . ' ' . auth()->guard()->user()->last_name }}</span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ asset(auth()->guard()->user()->image) }}" class="img-circle" alt="{{ auth()->guard()->user()->first_name }}">
                            <p>{{ auth()->guard()->user()->first_name . ' ' . auth()->guard()->user()->last_name }} - {{ auth()->guard()->user()->user_type === 1 ? 'Administrator' : 'Library Assistant' }}</p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('users.edit', ['user' => auth()->guard()->user()->id]) }}" class="btn btn-default btn-flat">Edit Account</a>
                            </div>
                            <div class="pull-right">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf

                                    <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
