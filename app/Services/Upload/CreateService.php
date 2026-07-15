<?php

namespace App\Services\Upload;

use App\Enums\FileSource;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateService
{
    public function execute(array $data): array
    {
        /** @var UploadedFile $file */
        $file = $data['file'];
        $disk = config('filesystems.default');

        $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

        $path = match ($disk) {
            // 's3'    => $file->storeAs('uploads', $fileName, 's3'),
            's3'    => $file->storeAs('uploads', $fileName, 's3', ['visibility' => 'public']),
            'public' => $file->storeAs('uploads', $fileName, 'public'),
            default => $file->storeAs('uploads', $fileName, 'local'),
        };

        $upload = File::create([
            'disk'          => $disk,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'file_size'     => $file->getSize(),
            'mime_type'     => $file->getMimeType(),
            'source'        => FileSource::SOURCE_UPLOAD,
        ]);

        return [
            'id'            => $upload->id,
            'original_name' => $upload->original_name,
            'path'          => $upload->path,
            'url'           => match ($disk) {
                // 's3'     => Storage::disk('s3')->temporaryUrl($upload->path, now()->addMinutes(5)),
                's3'     => Storage::disk('s3')->url($upload->path),
                'public' => Storage::disk('public')->url($upload->path),
                default  => null,
            },
            'file_size'     => $upload->file_size,
            'mime_type'     => $upload->mime_type,
            'disk'          => $upload->disk,
        ];
    }
}
