<?php

namespace Wqa\PdfExtract\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExtractConditions extends Model
{
    protected $table = 'pdf_extract_conditions';

    protected $fillable = [
        'pdf_id',
        'body_system',
        'page',
        'name',
        'content',
        'solutions',
        'solutions_nb',
        'linked_body_system',
        'status',
    ];

    public function pdf()
    {
        return $this->belongsTo(PdfExtract::class);
    }
}
