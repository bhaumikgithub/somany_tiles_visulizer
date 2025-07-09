<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    public function transcribeAzure(Request $request)
    {
        if (!$request->hasFile('audio')) {
            return response()->json(['error' => 'No audio uploaded'], 400);
        }

        $audio = $request->file('audio');
        $filename = uniqid() . '.wav';
        $path = $audio->storeAs('temp_audio', $filename);
        $filePath = storage_path('app/' . $path);

        $subscriptionKey = '45dedbad-0179-4757-b19d-63684f4b8ff0';
        $region = 'centralindia';

        $endpoint = "https://$region.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=en-US";

        try {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                'Content-Type' => 'audio/wav',
                'Accept' => 'application/json',
            ])->attach(
                'audio', file_get_contents($filePath), $filename
            )->post($endpoint);

            Storage::delete($path);

            if ($response->successful()) {
                $text = $response->json()['DisplayText'] ?? '';
                return response()->json(['text' => $text]);
            } else {
                return response()->json(['error' => 'Azure STT failed', 'details' => $response->body()], 500);
            }
        } catch (\Exception $e) {
            Storage::delete($path);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
