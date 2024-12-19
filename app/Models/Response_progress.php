<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/ResponseProgress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response_progress extends Model
{
    protected $fillable = [
        'response_id',
        'histories'
    ];

    // Optional: untuk casting JSON
    protected $casts = [
        'histories' => 'array'
    ];

    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    // Method untuk menambah history
    public function addHistory($progressText)
    {
        $histories = $this->histories ?? [];
        
        $newHistory = [
            'response_progress' => $progressText,
            'created_at' => now()->toDateTimeString()
        ];

        $histories[] = $newHistory;
        
        $this->histories = $histories;
        $this->save();
    }
}