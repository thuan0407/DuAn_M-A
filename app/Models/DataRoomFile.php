<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataRoomFile extends Model
{
    protected $fillable = [
        'uploaded_by',
        'deal_id',
        'document_type',
        'file_name',
        'file_path',
        'allow_download',
    ];

    protected $casts = [
        'allow_download' => 'boolean',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}