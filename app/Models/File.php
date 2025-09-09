<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'is_folder',
        'created_by',
        'parent_id',
        'last_accessed_at',
    ];

    protected $casts = [
        'is_folder' => 'boolean',
        'last_accessed_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(File::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(File::class, 'parent_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculates the total size of a folder by summing the sizes of all its descendant files.
     * This method is optimized to prevent N+1 query problems.
     *
     * @return int The total size in bytes.
     */
    public function getFolderSize(): int
    {
        if (!$this->is_folder) {
            return (int) $this->size;
        }

        return (int) $this->getAllDescendants()->where('is_folder', false)->sum('size');
    }

    /**
     * Efficiently retrieves all descendants of the current folder.
     *
     * @return Collection
     */
    private function getAllDescendants(): Collection
    {
        $descendants = collect();
        $children = $this->children()->get();
        $folderIds = $children->where('is_folder', true)->pluck('id');

        $descendants = $descendants->concat($children);

        while ($folderIds->isNotEmpty()) {
            $children = File::whereIn('parent_id', $folderIds)->get();
            $folderIds = $children->where('is_folder', true)->pluck('id');
            $descendants = $descendants->concat($children);
        }

        return $descendants;
    }
}

