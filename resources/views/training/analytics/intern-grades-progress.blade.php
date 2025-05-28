@extends('layouts.nav')

@section('content')
<div class="page-container">
    <h1>Internship Grades Progress</h1>
    <hr>
    
    <div class="chart-container">
        <canvas id="internGradesChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<script>
    const chartData = @json($chartData);

    const ctx = document.getElementById('internGradesChart').getContext('2d');

    const internGradesChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Students'
                    },
                    ticks: {
                        precision: 0 // Display whole numbers for student counts
                    },
                    max: 5 // Set the maximum value for the y-axis
                },
                x: {
                    title: {
                        display: true,
                        text: 'Subject'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true, // Show legend to differentiate subjects
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Internship Grades Distribution by Final Status per Subject'
                }
            }
        }
    });
</script>

<style>
.page-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-container h1 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.page-container hr {
    border: 0;
    height: 1px;
    background: #ddd;
    margin-bottom: 20px;
}

.chart-container {
    position: relative;
    height: 400px; /* Set a fixed height or use responsive design */
    width: 100%;
}
</style>
@endpush 