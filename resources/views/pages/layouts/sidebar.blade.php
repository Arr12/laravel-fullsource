<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->photo }}" onerror="this.src='/dist/img/user2-160x160.jpg'"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block d-flex flex-column">
                    <p class="m-0 p-0">{{ auth()->check() ? auth()->user()->name : '' }}</p>
                    <span style="font-size: 12px;">{{ auth()->check() ? auth()->user()->getRoleNames()[0] : '' }}</span>
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                            {{-- <span class="right badge badge-danger">1</span> --}}
                        </p>
                    </a>
                </li>
                {{-- @if (auth()->user()->getRoleNames('Administrator') || auth()->user()->getRoleNames('Personalia')) --}}
                @role('Administrator')
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                {{-- <span class="right badge badge-danger">1</span> --}}
                            </p>
                        </a>
                    </li>
                @endrole
                {{-- @endif --}}
                {{-- @if (auth()->user()->getRoleNames('Administrator') || auth()->user()->getRoleNames('Personalia') || auth()->user()->getRoleNames('Security')) --}}
                @role('Administrator|Personalia|Security')
                    <li class="nav-item">
                        <a href="{{ route('role.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Roles
                                {{-- <span class="right badge badge-danger">1</span> --}}
                            </p>
                        </a>
                    </li>
                @endrole
                {{-- @endif --}}
                <li class="nav-item">
                    <button id="logout" type="button" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Logout
                        </p>
                    </button>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
