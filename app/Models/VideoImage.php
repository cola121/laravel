<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoImage extends Model
{
    //
    protected $table = 'video_images';
    public $timestamps = false;

    protected $primaryKey = 'image_id';

    const SMALL_IMAGE_LINK = 'https://img3.doubanio.com/view/photo/albumicon/public/';
    const BIG_IMAGE_LINK = 'https://img3.doubanio.com/view/photo/photo/public/';

}
