<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = ['office_name', 'whatsapp', 'phone', 'email', 'address', 'chat_greeting'];
}