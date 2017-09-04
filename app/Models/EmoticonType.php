<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmoticonType extends Model
{
    protected $table = 'emoticon_type';
    protected $dateFormat = 'U';

    protected $primaryKey = 'type_id';


    public function saveEmoji($data)
    {

        $this->text = $data['name'];
        $this->raw_image = $data['raw_image'];
        $this->category = $data['category'];
        $this->edit_type = $data['edit_type'];
        $this->save();
    }
}
