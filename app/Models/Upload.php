<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['uploadable_type', 'uploadable_id', 'disk', 'original_name', 'path', 'file_size', 'mime_type'])]
class Upload extends Model
{
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }
}
