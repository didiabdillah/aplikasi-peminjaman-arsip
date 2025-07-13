<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'code',
        'title',
        'description',
        'category',
        'location',
        'shelf_number',
        'box_number',
        'year',
        'status',
        'condition',
        'created_by',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'year' => 'integer',
    ];

    /**
    * Check if archive is available for loan
    */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
    * Check if archive is currently borrowed
    */
    public function isBorrowed()
    {
        return $this->status === 'borrowed';
    }

    /**
    * Get the user who created this archive
    */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
    * Get all loans for this archive
    */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
    * Get current active loan (if any)
    */
    public function currentLoan()
    {
        return $this->hasOne(Loan::class)->where('status', 'borrowed');
    }

    /**
    * Scope to filter available archives
    */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
    * Scope to filter by category
    */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
    * Scope to search archives
    */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
            ->orWhere('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
