<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FileResource::collection(File::query()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400' // 100MB Max
        ]);

        $path = $request->file('file')->store('private');

        $file = File::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => $request->file('file')->getSize(),
            'created_by' => 1, // Ganti dengan user yang terotentikasi
            'parent_id' => null, // Logika untuk folder bisa ditambahkan di sini
        ]);

        return new FileResource($file);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $file->update([
            'name' => $request->name
        ]);

        return new FileResource($file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        Storage::delete($file->path);
        $file->delete();

        return response()->noContent();
    }
}
