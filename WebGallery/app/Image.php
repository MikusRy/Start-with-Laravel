<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'gallery_id', 'file_name', 'pic_name', 'created_by', 'public', 'like', 'unlike', 'view', 'info',
    ];
}
