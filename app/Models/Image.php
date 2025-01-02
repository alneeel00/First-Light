<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $fillable= ['filename','imageable_id','imageable_type'];
    public function employee()
    {
    return $this->belongsTo('App\Models\employee','imageable_id');
    }
    public function imageable()
    {
        return $this->morphTo();
    }
}
