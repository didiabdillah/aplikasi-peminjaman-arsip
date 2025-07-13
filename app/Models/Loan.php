<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'user_id',
        'archive_id',
        'loan_date',
        'due_date',
        'return_date',
        'purpose',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
    * Check if loan is overdue
    */
    public function isOverdue()
    {
        return $this->status === 'borrowed' &&
        $this->due_date < Carbon::now()->toDateString();
    }

    /**
    * Check if loan is returned
    */
    public function isReturned()
    {
        return $this->status === 'returned';
    }

    /**
    * Get the borrower (user)
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
    * Get the borrowed archive
    */
    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    /**
    * Get the admin who approved this loan
    */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
    * Scope to filter active loans
    */
    public function scopeActive($query)
    {
        return $query->where('status', 'borrowed');
    }

    /**
    * Scope to filter overdue loans
    */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
        ->where('due_date', '<', Carbon::now()->toDateString());
    }

    /**
    * Scope to filter by user
    */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
    * Calculate loan duration in days
    */
    public function getDurationAttribute()
    {
        $endDate = $this->return_date ?? Carbon::now();
        return Carbon::parse($this->loan_date)->diffInDays($endDate);
    }
}
