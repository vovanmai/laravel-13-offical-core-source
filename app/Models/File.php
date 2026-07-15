<?php

namespace App\Models;

use App\Enums\FileSource;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['uploadable_type', 'uploadable_id', 'disk', 'original_name', 'path', 'file_size', 'mime_type', 'source'])]
class File extends Model
{
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'source' => FileSource::class,
        ];
    }

    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }
}
