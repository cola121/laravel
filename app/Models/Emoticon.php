<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emoticon extends Model
{
    //
    protected $table = 'emoticon';
    protected $dateFormat = 'U';

    protected $primaryKey = 'em_id';


    public function saveEmoji($data)
    {
        //$this->title = $data['title'];
        $this->text = $data['text'];
      //  $this->full_image = $data['full_image'];
        $this->raw_image = $data['raw_image'];
        $this->category = $data['category'];
        $this->edit_type = $data['edit_type'];
        $this->save();
    }

}
