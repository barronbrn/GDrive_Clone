<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\File; // Assuming you want to save the final file to your File model
use Illuminate\Support\Facades\Auth;

class ChunkUploadController extends Controller
{
    // Initialize the upload process
    public function initiate(Request $request)
    {
        $request->validate([
            'filename' => 'required|string|max:255',
            'total_size' => 'required|integer',
            'total_chunks' => 'required|integer',
            'parent_id' => 'nullable|exists:files,id,created_by,'.Auth::id(),
        ]);

        $uploadId = (string) Str::uuid();
        $tempDir = 'temp/chunks/' . $uploadId;

        // Create a temporary directory for this upload
        Storage::disk('private')->makeDirectory($tempDir);

        // Store initial upload info (optional, but good for tracking)
        Storage::disk('private')->put($tempDir . '/info.json', json_encode([
            'filename' => $request->filename,
            'total_size' => $request->total_size,
            'total_chunks' => $request->total_chunks,
            'parent_id' => $request->parent_id,
            'uploaded_chunks' => 0,
            'status' => 'in_progress',
        ]));

        return response()->json([
            'upload_id' => $uploadId,
            'message' => 'Upload initiated successfully.'
        ]);
    }

    // Receive and store individual chunks
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|string',
            'chunk_index' => 'required|integer',
            'file_chunk' => 'required|file',
        ]);

        $uploadId = $request->upload_id;
        $chunkIndex = $request->chunk_index;
        $fileChunk = $request->file('file_chunk');

        $tempDir = 'temp/chunks/' . $uploadId;

        if (!Storage::disk('private')->exists($tempDir)) {
            return response()->json(['error' => 'Upload ID not found or directory missing.'], 404);
        }

        // Store the chunk
        $chunkPath = $tempDir . '/' . $chunkIndex . '.part';
        Storage::disk('private')->put($chunkPath, file_get_contents($fileChunk->getRealPath()));

        // Update info.json (optional, for tracking progress on server)
        $infoPath = $tempDir . '/info.json';
        $info = json_decode(Storage::disk('private')->get($infoPath), true);
        $info['uploaded_chunks']++;
        Storage::disk('private')->put($infoPath, json_encode($info));

        return response()->json([
            'message' => 'Chunk ' . $chunkIndex . ' uploaded successfully.',
            'uploaded_chunks' => $info['uploaded_chunks']
        ]);
    }

    // Finalize the upload: reassemble chunks and save the file
    public function finalize(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|string',
            'total_chunks' => 'required|integer',
        ]);

        $uploadId = $request->upload_id;
        $totalChunks = $request->total_chunks;
        $tempDir = 'temp/chunks/' . $uploadId;
        $infoPath = $tempDir . '/info.json';

        if (!Storage::disk('private')->exists($tempDir) || !Storage::disk('private')->exists($infoPath)) {
            return response()->json(['error' => 'Upload ID not found or info missing.'], 404);
        }

        $info = json_decode(Storage::disk('private')->get($infoPath), true);

        // Verify all chunks are present
        if ($info['uploaded_chunks'] < $totalChunks) {
            return response()->json(['error' => 'Not all chunks have been uploaded yet.'], 400);
        }

        $finalFilename = $info['filename'];
        $finalFilePath = 'files/' . Auth::id() . '/' . $finalFilename;
        $fullPath = Storage::disk('private')->path($finalFilePath);

        // Ensure the destination directory exists
        Storage::disk('private')->makeDirectory(dirname($finalFilePath));

        // Reassemble chunks
        $outputFile = fopen($fullPath, 'wb');
        if (!$outputFile) {
            return response()->json(['error' => 'Could not open destination file for writing.'], 500);
        }

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $tempDir . '/' . $i . '.part';
            if (!Storage::disk('private')->exists($chunkPath)) {
                fclose($outputFile);
                Storage::disk('private')->deleteDirectory($tempDir); // Clean up
                return response()->json(['error' => 'Missing chunk ' . $i . ' during reassembly.'], 500);
            }
            $chunkContent = Storage::disk('private')->get($chunkPath);
            fwrite($outputFile, $chunkContent);
        }
        fclose($outputFile);

        // Save file info to database
        File::create([
            'name' => $finalFilename,
            'path' => $finalFilePath,
            'mime_type' => Storage::disk('private')->mimeType($finalFilePath),
            'size' => Storage::disk('private')->size($finalFilePath),
            'is_folder' => false,
            'created_by' => Auth::id(),
            'parent_id' => $info['parent_id'],
        ]);

        // Clean up temporary chunks directory
        Storage::disk('private')->deleteDirectory($tempDir);

        return response()->json([
            'message' => 'File ' . $finalFilename . ' uploaded and reassembled successfully.',
            'file_path' => $finalFilePath
        ]);
    }
}
