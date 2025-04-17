@extends('layouts.nav')

@section('content')
    <section class="intervention">
        <div class="header-container">
            <h1>Student Intervention</h1>
        </div>

        <div class="intervention-form-container">
            <form class="intervention-form">
                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" placeholder="Enter student ID">
                </div>

                <div class="form-group">
                    <label for="intervention_type">Intervention Type</label>
                    <select id="intervention_type" name="intervention_type">
                        <option value="">Select Intervention Type</option>
                        <option value="academic">Academic Support</option>
                        <option value="behavioral">Behavioral Support</option>
                        <option value="social">Social Support</option>
                        <option value="emotional">Emotional Support</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="intervention_date">Intervention Date</label>
                    <input type="date" id="intervention_date" name="intervention_date">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Describe the intervention"></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit Intervention</button>
                    <button type="reset" class="reset-btn">Reset</button>
                </div>
            </form>
        </div>
    </section>

    <style>
        .intervention {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header-container {
            margin-bottom: 30px;
            text-align: center;
        }

        .header-container h1 {
            font-size: 28px;
            color: #333;
        }

        .intervention-form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .intervention-form {
            display: grid;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .submit-btn,
        .reset-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .reset-btn {
            background-color: #f44336;
            color: white;
        }

        .reset-btn:hover {
            background-color: #d32f2f;
        }
    </style>
@endsection
