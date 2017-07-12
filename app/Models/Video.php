<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $table = 'video';
    protected $dateFormat = 'U';

    protected $primaryKey = 'video_id';
    protected $fillable = ['year', 'duration'];
}
