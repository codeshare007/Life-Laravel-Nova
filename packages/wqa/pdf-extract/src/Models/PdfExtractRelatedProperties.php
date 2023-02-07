<?php

namespace Wqa\PdfExtract\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExtractRelatedProperties extends Model
{
    protected $table = 'pdf_extract_related_properties';

    protected $fillable = [
        'pdf_id',
        'page',
        'name',
        'solutions',
        'status',
    ];

    public function pdf()
    {
        return $this->belongsTo(PdfExtract::class);
    }
}
