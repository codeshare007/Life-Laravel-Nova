<?php

namespace Wqa\PdfExtract\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExtractData extends Model
{
    protected $table = 'pdf_extract_data';

    protected $fillable = [
        'pdf_id',
        'area_id',
        'page',
        'column',
        'content',
    ];

    public function pdf()
    {
        return $this->belongsTo(PdfExtract::class);
    }

    public function area()
    {
        return $this->belongsTo(PdfExtractArea::class);
    }
}
