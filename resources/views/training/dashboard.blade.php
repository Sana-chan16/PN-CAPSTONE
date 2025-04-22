@extends('layouts.nav')

@section('content')

<h1 style="font-weight: 300;">Dashboard</h1>
<hr>

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-number">{{ $totalSchools }}</div>
            <div class="stat-label">No. of Schools</div>
        </div>

        <div class="stat-card">
            <div class="stat-number">{{ $totalClasses }}</div>
            <div class="stat-label">No. of Classes</div>
        </div>

        <div class="stat-card">
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Total No. of Students</div>
        </div>
    </div>

    <div class="analytics-section">
        <div class="chart-container">
            <canvas id="batchChart"></canvas>
        </div>
    </div>

    <div class="tables-section">
        <div class="table-container">
            <h2>Recent Students</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Batch</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStudents as $student)
                        <tr>
                            <td>{{ $student->studentDetail->student_id ?? 'N/A' }}</td>
                            <td>{{ $student->user_fname }} {{ $student->user_mInitial }} {{ $student->user_lname }}</td>
                            <td>{{ $student->studentDetail->batch ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge {{ $student->status === 'active' ? 'active' : 'inactive' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">No recent students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



<canvas id="batchChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('batchChart').getContext('2d');

    const batchLabels = {!! json_encode($batchCounts->keys()) !!};
    const batchData = {!! json_encode($batchCounts->values()) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: batchLabels.map(batch => 'Class ' + batch),
            datasets: [{
                label: 'Number of Students',
                data: batchData,
                backgroundColor: '#4CAF50',
                borderRadius: 1,
                maxBarThickness: 20,
                minBarLength: 3,
                backgroundColor: [
                    '#4CAF50',
                    '#FF9800',
                    '#2196F3',
                    '#E91E63',
                    '#9C27B0',
                    '#00BCD4',
                    '#CDDC39',
                    '#FF5722',
                    '#795548',
                    '#607D8B'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Number of Students per Batch',
                    font: { size: 18 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 70, // 👈 Fix the Y-axis max value here
                ticks: {
                    stepSize: 10 // Optional: show 0, 10, 20... up to 70
                         }
                }
            }
        }
    });
</script>



@endsection
