<?php

namespace App\Models\AppNotificationModels;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = 'notifications';
    protected $fillable = ['type', 'notifiable_type', 'notifiable_id', 'data'];
    public $timestamps = false;
}
