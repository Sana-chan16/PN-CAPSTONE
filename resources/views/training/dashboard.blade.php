@extends('layouts.nav')

@section('content')
    <section class="dashboard-content">
        <div class="dashboard-header">
            <h1>Welcome to PNPh-SAMS</h1>
            <p class="welcome-message">Your Student Management System</p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Students</h3>
                    <p class="stat-number">0</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Schools</h3>
                    <p class="stat-number">0</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Active Interventions</h3>
                    <p class="stat-number">0</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-section">
                <h2>Quick Actions</h2>
                <div class="quick-actions">
                    <a href="#" class="action-button">
                        <i class="fas fa-user-graduate"></i>
                        <span>View Students</span>
                    </a>
                    <a href="#" class="action-button">
                        <i class="fas fa-edit"></i>
                        <span>Manage Students</span>
                    </a>
                    <a href="#" class="action-button">
                        <i class="fas fa-file-alt"></i>
                        <span>Grade Submission</span>
                    </a>
                </div>
            </div>

            <div class="dashboard-section">
                <h2>System Status</h2>
                <div class="status-list">
                    <div class="status-item">
                        <div class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-details">
                            <p class="status-description">Student Management Module</p>
                            <span class="status-indicator">Ready</span>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-details">
                            <p class="status-description">Grade Submission Module</p>
                            <span class="status-indicator">Ready</span>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-details">
                            <p class="status-description">Intervention Module</p>
                            <span class="status-indicator">Ready</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .dashboard-content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .dashboard-header h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-message {
            color: #666;
            font-size: 18px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 24px;
            color: #22bbea;
            margin-right: 15px;
        }

        .stat-info h3 {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 5px 0 0;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .dashboard-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dashboard-section h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .action-button i {
            font-size: 24px;
            margin-bottom: 8px;
            color: #22bbea;
        }

        .status-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .status-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .status-icon {
            font-size: 18px;
            color: #4CAF50;
            margin-right: 15px;
        }

        .status-details {
            flex: 1;
        }

        .status-description {
            margin: 0;
            color: #333;
        }

        .status-indicator {
            font-size: 12px;
            color: #4CAF50;
            font-weight: 500;
        }
    </style>
@endsection
