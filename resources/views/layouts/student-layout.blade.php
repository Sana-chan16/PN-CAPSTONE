@import 'bootstrap/scss/bootstrap'; // If using SCSS


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Dashboard' }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap">
    <style>
        :root {
            --primary-color: #22bbea;
            --light-color: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
        }

        .top-bar {
            background-color: var(--primary-color);
            height: 80px;
        }

        .PN-logo {
            height: 50px;
            width: auto;
        }

        .sidebar {
            width: 250px;
            background-color: #fff;
            position: fixed;
            top: 80px;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1040;
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }

        .sidebar .menu-link {
            padding: 12px 20px;
            color: #333;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-left: 3px solid transparent;
        }

        .sidebar .menu-link:hover,
        .sidebar .menu-link.active {
            background-color: var(--light-color);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .content {
            margin-left: 250px;
            margin-top: 80px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .mobile-toggle {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .content{
                text-align:center;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1030;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0;
                margin-top: 130px;
            }

            .mobile-toggle {
                display: flex;
                position: fixed;
                top: 80px;
                left: 0;
                right: 0;
                background-color: var(--primary-color);
                z-index: 1035;
                justify-content: space-between;
                padding: 10px 20px;
                align-items: center;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='black' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <nav class="navbar navbar-expand top-bar fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/PN-logo.png') }}" alt="PN Logo" class="PN-logo">
            </a>
        </div>
    </nav>

    <!-- Mobile Toggle -->
    <div class="mobile-toggle d-md-none">
        <button class="navbar-toggler text-white" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul class="list-unstyled menu">
            <li>
                <a href="#" class="menu-link">
                    <img src="{{ asset('images/Dashboard.png') }}" alt="Dashboard" class="menu-icon">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-link">
                    <img src="{{ asset('images/gs.png') }}" alt="Grade Submission" class="menu-icon">
                    <span>Grade Submission</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-link">
                    <img src="{{ asset('images/gstatys.png') }}" alt="Grade Status" class="menu-icon">
                    <span>Grade Status</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            const toggleSidebar = () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            };

            sidebarToggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            const menuLinks = document.querySelectorAll('.menu-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        toggleSidebar();
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
</body>
</html>
