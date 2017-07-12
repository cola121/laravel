<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultiPurpose extends Model
{
    //
    protected $table = 'multipurpose';
    protected $dateFormat = 'U';
    protected $fillable = ['created_at', 'num_a'];

}
