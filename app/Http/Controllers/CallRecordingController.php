<?php

namespace App\Http\Controllers;

use App\Models\CallRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CallRecordingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'audio' => 'required|file', // MediaRecorder might not have webm mimetype on all browsers, but let's check
            'duration' => 'required|string',
        ]);

        if ($request->hasFile('audio')) {
            $path = $request->file('audio')->store('call_recordings', 'public');

            $recording = CallRecording::create([
                'user_id' => auth()->id() ?? 1,
                'file_path' => $path,
                'duration' => $request->duration,
            ]);

            return response()->json([
                'id' => $recording->id,
                'path' => Storage::url($path),
            ]);
        }

        return response()->json(['error' => 'No audio file provided'], 400);
    }

    public function show($id)
    {
        $recording = CallRecording::findOrFail($id);
        
        if (!Storage::disk('public')->exists($recording->file_path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($recording->file_path);
        
        // Determinar el MIME type según la extensión si es posible, de lo contrario audio/webm
        $extension = pathinfo($recording->file_path, PATHINFO_EXTENSION);
        $mime = $extension == 'ogg' ? 'audio/ogg' : 'audio/webm';

        return response($file, 200)->header('Content-Type', $mime);
    }
}
