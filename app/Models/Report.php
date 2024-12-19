<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Response_progress;
class Report extends Model
{
   
    protected $fillable = [
        'description',
        'type',
        'province',
        'regency',
        'subdistrict',
        'village',
        'voting',
        'viewers',
        'image',
        'statement',
        'user_id',
    ];

    protected $casts = [
      'voting' => 'array',
    ];

    public function response() {
        return $this->hasOne(Response::class, 'report_id', 'id');
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Comment(){
        return $this->hasMany(Comment::class);
    }

    public function report() {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    public function responseProgresses()
{
    return $this->hasMany(Response_progress::class, 'report_id');
}

    
}
