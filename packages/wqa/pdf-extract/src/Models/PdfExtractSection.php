<?php

namespace Wqa\PdfExtract\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExtractSection extends Model
{
    protected $table = 'pdf_extract_section';

    protected $fillable = [
        'name',
    ];
}
