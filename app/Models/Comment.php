<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment','report_id','user_id'
    ];

    public function Report(){
        return $this->belongsTo(Report::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
    
}
