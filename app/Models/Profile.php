<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'First_Name',
        'Last_Name',
        'Street_Address',
        'Postal',
        'Country',
        'Email',
        'Phone',
        'date',
        'Upload_Resume'
    ];

    //user post piviot for savedJobs
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function deadlineTimestamp()
    {
        return Carbon::parse($this->deadline)->timestamp;
    }

    public function remainingDays()
    {
        $deadline = $this->deadline;
        $timestamp = Carbon::parse($deadline)->timestamp - Carbon::now()->timestamp;
        return $timestamp;
    }

    public function getSkills()
    {
        return explode(',', $this->skills);
    }
}
