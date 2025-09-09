<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneTrash extends Command
{
    protected $signature = 'trash:prune';

    protected $description = 'Permanently delete files from trash that are older than 30 days.';

    // Menjalankan perintah konsol untuk memangkas file sampah lama
    public function handle()
    {
        $this->info('Starting to prune old trashed files...');
        $cutoffDate = now()->subDays(30);
        $deletedCount = 0;

        // Ambil semua file yang sudah saatnya dihapus permanen
        $filesToPrune = File::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();

        foreach ($filesToPrune as $file) {
            // Hapus file fisik jika bukan folder
            if (! $file->is_folder) {
                Storage::disk('private')->delete($file->path);
            }
            $file->forceDelete();
            $deletedCount++;
        }

        $this->info("Pruning complete. Permanently deleted {$deletedCount} items.");

        return 0;
    }
}
