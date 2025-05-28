@extends('layouts.nav')
@section('content')
<h2>Subject Grade Analytics</h2>
@foreach($analytics as $index => $data)
    <div style="margin-bottom: 40px;">
        <h4>{{ $data['subject_name'] }}</h4>
        <canvas id="chart-{{ $index }}" width="400" height="200"></canvas>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Chart(document.getElementById('chart-{{ $index }}'), {
                type: 'bar',
                data: {
                    labels: ['Passed', 'Failed', 'INC', 'NC', 'DR'],
                    datasets: [{
                        label: 'Count',
                        data: [{{ $data['passed'] }}, {{ $data['failed'] }}, {{ $data['inc'] }}, {{ $data['nc'] }}, {{ $data['dr'] }}],
                        backgroundColor: [
                            '#28a745', '#dc3545', '#ff9900', '#00bcd4', '#6c757d'
                        ]
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });
        });
        </script>
    </div>
@endforeach
<!-- Add Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection 