<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'amount', 'due_date', 'payment_info', 'payment_methods', 'qris_image_path', 'is_active'];

    protected $casts = ['due_date' => 'date', 'payment_methods' => 'array', 'is_active' => 'boolean'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}