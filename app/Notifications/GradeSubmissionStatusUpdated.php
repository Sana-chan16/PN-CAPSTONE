<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\GradeSubmission;

class GradeSubmissionStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $gradeSubmission;
    protected $status;

    public function __construct(GradeSubmission $gradeSubmission, string $status)
    {
        $this->gradeSubmission = $gradeSubmission;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->status);
        $subject = "Grade Submission {$status}";
        
        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your grade submission has been {$this->status}.")
            ->line("Class: {$this->gradeSubmission->classModel->class_name}")
            ->line("Semester: {$this->gradeSubmission->semester}")
            ->line("Term: {$this->gradeSubmission->term}")
            ->line("Academic Year: {$this->gradeSubmission->academic_year}")
            ->action('View Details', url('/student/grade-submissions'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'grade_submission_status',
            'grade_submission_id' => $this->gradeSubmission->id,
            'status' => $this->status,
            'message' => "Your grade submission has been {$this->status}.",
            'class_name' => $this->gradeSubmission->classModel->class_name,
            'semester' => $this->gradeSubmission->semester,
            'term' => $this->gradeSubmission->term,
            'academic_year' => $this->gradeSubmission->academic_year
        ];
    }
} 