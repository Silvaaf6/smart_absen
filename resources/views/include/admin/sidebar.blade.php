<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="/home" aria-expanded="false"><i
                            data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Kehadiran</span></li>
                @if (Auth::user()->hasRole('admin'))
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('jabatan.index') }}" aria-expanded="false">
                            <i class="icon-briefcase" style="font-size: 20px; "></i>

                            {{-- <i data-feather="tag" class="feather-icon"></i> --}}
                            <span class="hide-menu">Jabatan</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('pegawai.*') ? 'selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('pegawai.index') }}" aria-expanded="false">
                            <i class="icon-people" style="font-size: 20px;"></i>
                            <span class="hide-menu">Pegawai</span>
                        </a>
                    </li>
                @endif
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('kehadiran.index') }}" aria-expanded="false">
                        <i class="icon-book-open" style="font-size: 20px; "></i>
                        {{-- <i data-feather="message-square" class="feather-icon" style="display: none;"></i> --}}
                        <span class="hide-menu">Absensi</span>
                    </a>
                </li>

                <li class="list-divider"></li>

                <li class="nav-small-cap"><span class="hide-menu"></span>Cuti</li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('pengajuan_cuti.index') }}" aria-expanded="false">
                        <i class="icon-notebook" style="font-size: 20px; "></i>
                        {{-- <i data-feather="message-square" class="feather-icon" style="display: none;"></i> --}}
                        <span class="hide-menu">Pengajuan Cuti</span>
                    </a>
                </li>

                <li class="list-divider"></li>

                {{-- <li class="nav-small-cap"><span class="hide-menu">Piket</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('jadwal_piket.index')}}" aria-expanded="false">
                        <i class="icon-book-open" style="font-size: 20px; "></i>
                        <i data-feather="message-square" class="feather-icon" style="display: none;"></i>
                        <span class="hide-menu">Jadwal Piket</span>
                    </a>
                </li> --}}

                @role('admin')
                    <li class="nav-small-cap"><span class="hide-menu"></span>Laporan</li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('laporan.index') }}" aria-expanded="false">
                            <i class="icon-folder-alt" style="font-size: 20px; "></i>
                            <i data-feather="message-square" class="feather-icon" style="display: none;"></i>
                            <span class="hide-menu">Laporan</span>
                        </a>
                    </li>
                @endrole
                @role('user')
                    <li class="nav-small-cap"><span class="hide-menu"></span>Profil</li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('pegawai.show', Auth::user()->id) }}" aria-expanded="false">
                            <i class="icon-user" style="font-size: 20px;"></i>
                            <span class="hide-menu">Profil Saya</span>
                        </a>
                    </li>
                @endrole
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
