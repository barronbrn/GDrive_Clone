<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'parent_id' => 'nullable|exists:files,id,created_by,' . Auth::id(),
        ]);

        $query = File::where('created_by', Auth::id())
            ->where('parent_id', $request->parent_id);

        $files = $query->orderBy('is_folder', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return FileResource::collection($files);
    }

    /**1
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|max:20480', // 20MB limit
            'parent_id' => 'nullable|exists:files,id,created_by,' . Auth::id(),
        ]);

        $uploadedFile = $request->file('file_upload');
        $path = $uploadedFile->store('files/' . Auth::id(), 'private');

        $file = File::create([
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'is_folder' => false,
            'created_by' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);

        return new FileResource($file);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        $this->authorize('view', $file);

        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not implemented for this version
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        $file->delete();

        return response()->noContent();
    }
}