@extends('layouts.nav')

@section('content')
    <section class="dashboard-content">
        <div class="dashboard-header">
            <h1>Training Dashboard</h1>
            <p class="welcome-message">Welcome back, {{ Auth::user()->user_fname }}!</p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Trainees</h3>
                    <p class="stat-number">{{ $totalTrainees ?? 0 }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>Active Courses</h3>
                    <p class="stat-number">{{ $activeCourses ?? 0 }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Upcoming Sessions</h3>
                    <p class="stat-number">{{ $upcomingSessions ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-section">
                <h2>Quick Actions</h2>
                <div class="quick-actions">
                    <a href="{{ route('training.courses.create') }}" class="action-button">
                        <i class="fas fa-plus"></i>
                        <span>Create New Course</span>
                    </a>
                    <a href="{{ route('training.sessions.schedule') }}" class="action-button">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Schedule Session</span>
                    </a>
                    <a href="{{ route('training.reports') }}" class="action-button">
                        <i class="fas fa-chart-bar"></i>
                        <span>View Reports</span>
                    </a>
                </div>
            </div>

            <div class="dashboard-section">
                <h2>Recent Activities</h2>
                <div class="activity-list">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-{{ $activity->icon }}"></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-description">{{ $activity->description }}</p>
                                <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="no-activities">No recent activities</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Upcoming Training Sessions</h2>
            <div class="sessions-table">
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingSessionsList as $session)
                            <tr>
                                <td>{{ $session->course->name }}</td>
                                <td>{{ $session->date->format('M d, Y') }}</td>
                                <td>{{ $session->start_time->format('h:i A') }} - {{ $session->end_time->format('h:i A') }}</td>
                                <td>{{ $session->location }}</td>
                                <td><span class="status-badge {{ $session->status }}">{{ $session->status }}</span></td>
                                <td>
                                    <a href="{{ route('training.sessions.view', $session->id) }}" class="action-link">View</a>
                                    <a href="{{ route('training.sessions.edit', $session->id) }}" class="action-link">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-sessions">No upcoming sessions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
        }

        .dashboard-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }

        .welcome-message {
            color: #666;
            font-size: 16px;
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
            margin-bottom: 30px;
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

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .activity-icon {
            font-size: 18px;
            color: #22bbea;
            margin-right: 15px;
        }

        .activity-details {
            flex: 1;
        }

        .activity-description {
            margin: 0;
            color: #333;
        }

        .activity-time {
            font-size: 12px;
            color: #666;
        }

        .sessions-table {
            overflow-x: auto;
        }

        .sessions-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .sessions-table th,
        .sessions-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .sessions-table th {
            background: #f8f9fa;
            font-weight: 500;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.scheduled {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-badge.in-progress {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-badge.completed {
            background: #e8f5e9;
            color: #388e3c;
        }

        .action-link {
            color: #22bbea;
            text-decoration: none;
            margin-right: 10px;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .no-activities,
        .no-sessions {
            color: #666;
            text-align: center;
            padding: 20px;
        }
    </style>
@endsection
