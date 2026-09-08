<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DueAssignment extends Model
{
    protected $fillable = ['due_id', 'user_id', 'amount'];

    protected $casts = ['amount' => 'integer'];

    public function due()
    {
        return $this->belongsTo(Due::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
