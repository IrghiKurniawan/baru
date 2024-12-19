<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    //
    
    protected $fillable = [
        'report_id','response_status','response_progress'
    ];
    
  // Model Response
public function report()
{
    return $this->belongsTo(Report::class, 'report_id');
}


    public function User(){
        return $this->belongsTo(User::class);
    }

    public function responseProgresses()
    {
        return $this->hasMany(Response_progress::class);
    }
}
