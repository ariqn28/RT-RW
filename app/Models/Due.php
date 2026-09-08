<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'amount', 'due_date', 'payment_info', 'payment_methods', 'qris_image_path', 'bank_name', 'account_number', 'account_holder', 'bifast_number', 'cash_payment_info', 'is_active'];

    protected $casts = ['due_date' => 'date', 'payment_methods' => 'array', 'is_active' => 'boolean'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignments()
    {
        return $this->hasMany(DueAssignment::class);
    }

    public function amountFor(?int $userId): int
    {
        $assignment = $this->assignments->firstWhere('user_id', $userId);

        return $assignment?->amount ?? $this->amount;
    }

    public function scopeVisibleTo($query, int $userId)
    {
        return $query->where(function ($query) use ($userId) {
            $query->whereDoesntHave('assignments')
                ->orWhereHas('assignments', fn ($assignment) => $assignment->where('user_id', $userId));
        });
    }
}