<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actors extends Model
{
    //
    protected $table = 'actors';
    public $timestamps = false;

    protected $primaryKey = 'actor_id';
    protected $fillable = ['u_type'];


    public function returnActorName($name)
    {
        if ($name) {
            $name = explode(';', $name);
            return $this->whereIn('db_id', $name)->get();
        }

    }
}
