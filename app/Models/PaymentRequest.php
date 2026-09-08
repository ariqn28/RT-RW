<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $fillable = ['due_id', 'user_id', 'payment_method', 'status'];

    public function due()
    {
        return $this->belongsTo(Due::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
