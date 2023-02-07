<?php

namespace Wqa\PdfExtract\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExtractArea extends Model
{
    protected $table = 'pdf_extract_area';

    protected $fillable = [
        'section_id',
        'name',
    ];
}
