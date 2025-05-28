<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Dashboard' }}</title>
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">

    <style>
    body {
        margin: 0;  
        font-family: 'Poppins', sans-serif;
        background-color: #f1f5f9;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .top-bar {
        background-color: #22bbea;
        padding: 0 20px;
        display: flex;
        align-items: center;
        height: 80px;
        flex-shrink: 0;
        position: sticky;
        top: 0;
        z-index: 1001;
        width: 100vw;
        left: 0;
        right: 0;
        box-sizing: border-box;
    }

    .PN-logo {
        height: 40px;
    }

    .container {
        display: flex;
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    .sidebar {
        background-color: #ffffff;
        width: 250px;
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
        transition: transform 0.3s ease;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        height: 100vh;
        z-index: 1000;
        overflow-y: auto;
        padding-top: 80px;
    }

    .menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu li {
        padding: 12px 20px;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: background-color 0.3s;
        border-radius: 8px;
        margin: 0 10px;
    }

    .menu li a {
        color: #333333;
        text-decoration: none;
        width: 100%;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .menu li img {
        width: 24px;
        height: 24px;
    }

    .menu li:hover {
        background-color: #f1f5f9;
    }

    .menu li.active {
        background-color: #f1f5f9;
    }

    .content {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #f8f9fa;
        margin-left: 250px;
        transition: margin-left 0.3s ease;
        min-height: 100vh;
        padding-top: 0;
    }

    .user-info {
        margin-left: auto;
        color: #333;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logout-btn {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 5px;
    }

    .logout-btn:hover {
        color: #ff9933;
    }

    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 5px;
        margin-right: 15px;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
    }

    /* Media Queries */
    @media screen and (max-width: 1024px) {
        .content {
            margin-left: 200px;
        }

        .sidebar {
            width: 200px;
        }

        .menu li {
            padding: 10px 15px;
        }

        .menu li a {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 768px) {
        .mobile-menu-toggle {
            display: block;
        }

        .top-bar {
            position: sticky;
            top: 0;
            z-index: 1001;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 999;
            transform: translateX(-100%);
            height: 100vh;
            padding-top: 80px;
        }
        .sidebar.active {
            transform: translateX(0);
        }
        .content {
            margin-left: 0;
            padding: 15px;
            padding-top: 0;
            min-height: unset;
            height: 100dvh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .overlay.active {
            display: block;
        }

        .user-info {
            font-size: 14px;
            gap: 10px;
        }

        .PN-logo {
            height: 35px;
        }
    }

    @media screen and (max-width: 480px) {
        .top-bar {
            padding: 0 10px;
            height: 70px;
        }

        .user-info {
            font-size: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .PN-logo {
            height: 30px;
        }

        .content {
            padding: 10px;
        }

        .menu li {
            padding: 8px 12px;
        }

        .menu li a {
            font-size: 13px;
        }

        .menu li img {
            width: 20px;
            height: 20px;
        }
    }

    @media screen and (max-width: 600px) {
        .content {
            min-height: unset;
            height: 100dvh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
    </style>
</head>
<body>
    <div class="top-bar">
        <button class="mobile-menu-toggle" onclick="toggleSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                <path d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        <img class="PN-logo" src="{{ asset('images/PN-logo.png') }}" alt="PN Logo">

        @auth
            @php
                $user = Auth::user();
            @endphp

            <div class="user-info">
                @php
                    $userId = Auth::user()->user_id;
                    $imageExtensions = ['jpg', 'jpeg', 'png'];
                    $profileImage = null;
                    foreach ($imageExtensions as $ext) {
                        $candidate = 'storage/profile_images/profile_' . $userId . '.' . $ext;
                        if (file_exists(public_path($candidate))) {
                            $profileImage = asset($candidate);
                            break;
                        }
                    }
                @endphp
                <img src="{{ $profileImage ?? asset('images/default-profile.png') }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;margin-right:8px;border:2px solid #22bbea;" alt="Profile Image">
                Logged in as:
                <span style="color:white;">
                    {{ $user->user_fname }} {{ $user->user_mInitial }} {{ $user->user_lname }} {{ $user->suffix }}
                </span>
                <span style="white-space: nowrap;"><span style="color: #222;">| Role:</span> <span style="color: white;">{{ ucfirst($user->user_role) }}</span></span>
                <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: flex; align-items: center; margin: 0;">
                    @csrf
                    <button type="button" class="logout-btn" style="padding: 0 5px; display: flex; align-items: center;" onclick="confirmLogout()">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
                        </svg>
                    </button>
                </form>
            </div>
        @endauth
    </div>

    <div class="container">
        <div class="overlay" onclick="toggleSidebar()"></div>
        <aside class="sidebar">
            @auth
                @php $role = strtolower(Auth::user()->user_role ?? ''); @endphp
                @if($role === 'student')
                    <ul class="menu">
                        <li class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('student.dashboard') }}">
                                <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard">
                                Dashboard
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('student.grade-status') ? 'active' : '' }}">
                            <a href="{{ route('student.grade-status') }}">
                                <img src="{{ asset('images/gs.png') }}" alt="Grade Status">
                                Grade Status
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('student.profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('student.profile.edit') }}">
                                <img src="{{ asset('images/me.png') }}" alt="Profile">
                                Profile
                            </a>
                        </li>
                    </ul>
                @endif
            @endauth
        </aside>

        <main class="content">
            @yield('content')
        </main>
    </div>

    <script>
    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            document.getElementById('logout-form').submit();
        }
    }

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !mobileMenuToggle.contains(event.target) && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
    </script>

    @stack('scripts')
</body>
</html> 