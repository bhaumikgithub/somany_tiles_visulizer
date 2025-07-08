<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AIController extends Controller
{
    public function transcribe(Request $request)
    {
        // Check if audio file is uploaded
        if (!$request->hasFile('audio')) {
            return response()->json(['error' => 'No audio file provided'], 400);
        }

        // Store uploaded audio temporarily in storage/app/temp_audio/
        $file = $request->file('audio');
        $filename = uniqid() . '.mp3';
        $path = $file->storeAs('temp_audio', $filename);

        // Full file path
        $fullPath = storage_path('app/' . $path);

        // Send audio to local Flask Whisper server
        try {
            $response = Http::attach(
                'audio', file_get_contents($fullPath), $filename
            )->post('http://127.0.0.1:5000/transcribe');

            logger('FLASK RESPONSE: ' . json_encode($response->json()));

            // Clean up temp file
            Storage::delete($path);

            // Return response from Flask back to frontend
            return response()->json($response->json());
        } catch (\Exception $e) {
            // Clean up even on error
            Storage::delete($path);
            return response()->json(['error' => 'Flask server error: ' . $e->getMessage()], 500);
        }
    }
}
