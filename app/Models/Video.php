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

    /**
     * ¹ØÁª¾çÕÕ±í
     */
    public function getVideoImages()
    {
        return $this->hasMany('App\Models\VideoImage', 'video_id');
    }

    public function getTvRecomment()
    {
        return $this->where('channel', 'tv')->limit(10)->orderBy('year', 'desc')->get();
    }
}
