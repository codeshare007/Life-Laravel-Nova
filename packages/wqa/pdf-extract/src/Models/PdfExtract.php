<?php

namespace Wqa\PdfExtract\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExtract extends Model
{
    protected $fillable = [
        'filename',
        'name',
        'description',
    ];
}
