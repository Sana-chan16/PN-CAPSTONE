<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNPh-SAMS</title>
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
</head>
<body>
    <div class="top-bar">
        <img class="PN-logo" src="{{ asset('images/PN-logo.png') }}" alt="PN Logo">
    </div>

    <div class="container">
        <aside class="sidebar">
            <ul class="menu">
                <li>
                    <a href="{{ route('training.dashboard') }}" class="nav-link">
                        <img src="{{ asset('images/Dashboard.png') }}" alt="Dashboard"> 
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('students.info') }}" class="nav-link">
                        <img src="{{ asset('images/mu.png') }}" alt="Manage Users"> 
                        <span>Students Info</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('manage.students') }}" class="nav-link">
                        <img src="{{ asset('images/ms.png') }}" alt="Manage Students"> 
                        <span>Manage Students</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('grade.submission') }}" class="nav-link">
                        <img src="{{ asset('images/gs.png') }}" alt="Grade Submission"> 
                        <span>Grade Submission</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('analytics') }}" class="nav-link">
                        <img src="{{ asset('images/analytics.png') }}" alt="Analytics"> 
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('intervention.status') }}" class="nav-link">
                        <img src="{{ asset('images/is.png') }}" alt="Intervention Status"> 
                        <span>Intervention Status</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}" class="nav-link">
                        <img src="{{ asset('images/me.png') }}" alt="Profile"> 
                        <span>Profile</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="content">
            @yield('content')
        </main>
    </div>

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            height: 100px;
            background: #22bbea;
            display: flex;
            align-items: center;
            padding: 10px 20px;
        }

        .PN-logo {
            width: 300px;
            height: auto;
            margin-right: 10px;
        }

        .container {
            display: flex;
            flex-grow: 1;
        }

        .sidebar {
            width: 250px;
            background: white;
            border-right: 3px solid #22bbea;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .menu li {
            padding: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px;
            font-size: 16px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: #e3f2fd;
            color: #22bbea;
        }

        .nav-link img {
            width: 24px;
            margin-right: 10px;
        }

        .content {
            flex-grow: 1;
            background: #fff;
            padding: 20px;
        }

        /* Active link styling */
        .nav-link.active {
            background: #22bbea;
            color: white;
        }

        .nav-link.active:hover {
            background: #1a9bc8;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get current path
            const currentPath = window.location.pathname;
            
            // Find and highlight active link
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
