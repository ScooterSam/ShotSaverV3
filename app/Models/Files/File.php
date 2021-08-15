<?php

namespace App\Models\Files;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Str;

class File extends Model
{
    use HasFactory, IsViewable, IsFavourable;

    protected $guarded = ['id'];

    protected $casts = [
        'meta'    => 'json',
        'private' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(FileView::class);
    }

    public function favourites()
    {
        return $this->hasMany(FileFavourite::class);
    }

    function formatSizeUnits(): string
    {
        $bytes = $this->size_in_bytes;

        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * Generate the url for this file.
     *
     * @return string|null
     */
    public function getThumbUrl(): ?string
    {
        if ($this->thumb === null) {
            return null;
        }

        $url = Storage::url($this->thumb);

        if ($this->private) {
            $url = Storage::temporaryUrl($this->thumb, now()->addMinutes(10));
        }

        return $url;
    }

    /**
     * Generate the url for this file.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {

        $url = Storage::url($this->path);

        if ($this->private) {
            $url = Storage::temporaryUrl($this->path, now()->addMinutes(10));
        }

        return $url;
    }

    /**
     * Get the code file contents
     *
     * @return string
     * @throws FileNotFoundException
     */
    public function codeFileContents(): string
    {
        if ($this->type !== 'code' && $this->type !== 'text') {
            throw new Exception('This file is not a code file.');
        }

        return Storage::get($this->path);

    }

    public function urlKey(): string
    {
        return "{$this->id}_{$this->name}";
    }

    public static function fileFromUrlKey(string $key): File
    {
        if (Str::contains($key, '_')) {
            [$id, $name] = explode('_', $key);

            return File::query()
                ->where('id', $id)
                ->where('name', $name)
                ->first();
        }

        return File::query()
            ->where('id', $key)
            ->first();
    }
}
