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
        $name = explode(';', $name);

        if (is_array($name)) {
            $names = implode(',', $name);
        } else {
            $names = $name;
        }
var_dump($names);
        return $this->find([$names]);

    }
}
