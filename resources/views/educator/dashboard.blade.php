@extends('layouts.educator_layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/training/dashboard.css') }}">

<div class="dashboard-container" style="padding: 20px;">

    <h1 style="margin-bottom: 20px; color: #333; font-weight: 300;">Dashboard</h1>
    <hr>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: #fff; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <p class="stat-number">{{ $schoolsCount }}</p>
            <p>Total No. of Schools</p>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <p class="stat-number">{{ $classesCount }}</p>
            <p>Total No. of Classes</p>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <p class="stat-number">{{ $studentsCount }}</p>
            <p>Total No. of Students</p>
        </div>
    </div>

    <h1 style="font-weight:300; margin-bottom: 20px; color: #333;">Student by Batch Analytics</h1>
    <hr>
    <!-- Charts -->
    <div style="display: flex; flex-direction: column; gap: 30px;">
        <!-- Batch Chart -->
        <div style="background: #fff; width: 95%; border-radius: 12px; padding: 30px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.10); max-width: 900px; margin: 0 auto 30px auto; display: flex; flex-direction: column; align-items: center;">
            <h3 style="text-align: center; margin-bottom: 24px; color: #333; font-size: 1.5em; font-weight: 500;">Students by Batch and Gender</h3>
            <div style="height: 340px; width: 100%; max-width: 700px; display: flex; align-items: center; justify-content: center;">
                <canvas id="batchChart"></canvas>
            </div>
        </div>

        <h1 style="font-weight:300; color: #333;">Sex by Batch Analytics</h1>
        <hr style="margin-top: -20px;">
        <div class="options">
            <div style="background: #fff; width: 95%; border-radius: 12px; padding: 30px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.10); max-width: 600px; margin: 30px auto 0 auto; display: flex; flex-direction: column; align-items: center;">
                <div style="width:100%; display:flex; flex-direction:column; align-items:center; margin-bottom: 10px;">
                    <select id="batchFilter" style="width:135px; padding: 10px 14px; border-radius: 8px; border: 1px solid #ddd; background: #f8f9fa; font-size:1em; color:#333; outline:none; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        <option value="all">All Batches</option>
                        @foreach($batchCounts->keys() as $batch)
                            <option value="{{ $batch }}">Batch {{ $batch }}</option>
                        @endforeach
                    </select>
                </div>
                <h3 style="color: #333; margin-bottom: 20px; font-size: 1.5em; font-weight: 500; text-align: center;">Sex Distribution <span id='pieBatchTitle' style='font-size:0.7em; color:#888;'></span></h3>
                <div style="height: 320px; width: 320px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="genderChart"></canvas>
                </div>
                <div id="pieLegend" style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 6px;"><span style="display:inline-block;width:18px;height:18px;background:#22bbea;border-radius:50%;"></span> <span style="color:#333;">Male</span></div>
                    <div style="display: flex; align-items: center; gap: 6px;"><span style="display:inline-block;width:18px;height:18px;background:#ff9933;border-radius:50%;"></span> <span style="color:#333;">Female</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Items Section -->
    <h1 style="font-weight:300; margin-bottom: 20px; color: #333;">Recent Activity from training</h1>
    <hr>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <!-- Recent Students -->
        <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <h3 style="color: #333; margin-bottom: 15px; font-size: 1.2em; font-weight: 500;">Recent Students</h3>
            <div class="recent-list">
                @foreach($recentStudents as $student)
                    <div class="recent-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #eee;">
                        <i class="fas fa-user" style="color: #22bbea;"></i>
                        <div style="flex: 1;">
                            <strong style="display: block; color: #333;">{{ $student->user_fname }} {{ $student->user_lname }}</strong>
                            <small style="color: #666;">Batch {{ $student->studentDetail->batch ?? 'N/A' }}</small>
                        </div>
                        <span class="recent-date" style="color: #888; font-size: 0.9em;">{{ $student->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Schools -->
        <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <h3 style="color: #333; margin-bottom: 15px; font-size: 1.2em; font-weight: 500;">Recent Schools</h3>
            <div class="recent-list">
                @foreach($recentSchools as $school)
                    <div class="recent-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #eee;">
                        <i class="fas fa-school" style="color: #22bbea;"></i>
                        <div style="flex: 1;">
                            <strong style="display: block; color: #333;">{{ $school->name }}</strong>
                            <small style="color: #666;">{{ $school->department }} - {{ $school->course }}</small>
                        </div>
                        <span class="recent-date" style="color: #888; font-size: 0.9em;">{{ $school->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Classes -->
        <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <h3 style="color: #333; margin-bottom: 15px; font-size: 1.2em; font-weight: 500;">Recent Classes</h3>
            <div class="recent-list">
                @foreach($recentClasses as $class)
                    <div class="recent-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #eee;">
                        <i class="fas fa-chalkboard" style="color: #22bbea;"></i>
                        <div style="flex: 1;">
                            <strong style="display: block; color: #333;">{{ $class->class_name }}</strong>
                            <small style="color: #666;">{{ $class->school->name ?? 'N/A' }}</small>
                        </div>
                        <span class="recent-date" style="color: #888; font-size: 0.9em;">{{ $class->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Students by Batch Chart (grouped by gender) ---
    const batchCtx = document.getElementById('batchChart');
    if (batchCtx) {
        const batches = {!! json_encode($batchCounts->keys()) !!};
        const studentsByGenderByBatch = {!! json_encode($studentsByGenderByBatch) !!};
        const maleCounts = batches.map(batch => studentsByGenderByBatch[batch]?.male ?? 0);
        const femaleCounts = batches.map(batch => studentsByGenderByBatch[batch]?.female ?? 0);
        new Chart(batchCtx, {
            type: 'bar',
            data: {
                labels: batches,
                datasets: [
                    {
                        label: 'Male',
                        data: maleCounts,
                        backgroundColor: '#22bbea',
                    },
                    {
                        label: 'Female',
                        data: femaleCounts,
                        backgroundColor: '#ff9933',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true },
                    title: {
                        display: true,
                        font: { size: 16 }
                    }
                },
                scales: {
                    x: { stacked: false, title: { display: true, text: 'Batch' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Students' } }
                },
                layout: { padding: { left: 10, right: 10 } }
            }
        });
    }

    // --- Pie Chart for Sex Distribution (with batch filter) ---
    const genderCtx = document.getElementById('genderChart');
    let genderChart;
    
    // Initial gender data for all batches
    const genderData = {
        all: {
            male: {{ $maleCount }},
            female: {{ $femaleCount }}
        },
        @foreach($batchCounts->keys() as $batch)
        '{{ $batch }}': {
            male: {{ $genderByBatch[$batch]['male'] ?? 0 }},
            female: {{ $genderByBatch[$batch]['female'] ?? 0 }}
        },
        @endforeach
    };

    function updateGenderChart(batchValue) {
        const data = genderData[batchValue];
        
        if (genderChart) {
            genderChart.destroy();
        }

        genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [data.male, data.female],
                    backgroundColor: ['#22bbea', '#ff9933']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: batchValue === 'all' ? 'All Batches' : `Batch ${batchValue}`,
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }

    // Initialize chart with all batches
    if (genderCtx) {
        updateGenderChart('all');

        // Add event listener for batch filter
        document.getElementById('batchFilter').addEventListener('change', function(e) {
            updateGenderChart(e.target.value);
        });
    }
});
</script>
@endpush

@endsection

