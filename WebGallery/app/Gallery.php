<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';
    protected $fillable = [
        'name', 'created_by', 'public', 'like', 'unlike', 'view', 'items', 'info',
        ];
}
