<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AIController extends Controller
{
   public function transcribeAzure(Request $request)
    {
        if (!$request->hasFile('audio')) {
            return response()->json(['error' => 'No audio uploaded'], 400);
        }

        $audio = $request->file('audio');
        $filename = Str::uuid() . '.wav';
        $path = $audio->storeAs('temp_audio', $filename);
        $filePath = storage_path('app/' . $path);

        $subscriptionKey = 'L7NtvAGILkurrlKtrKnViALWuR2HaknlTkFEhi69bf0k6ffgF9AsJQQJ99BGACGhslBXJ3w3AAAYACOGc7PE';
        $region = 'centralindia';
        $endpoint = "https://$region.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=en-US";

        try {
            $stream = fopen($filePath, 'r');

            $response = Http::withOptions([
                'verify' => $this->getSSLVerifier()
            ])->withHeaders([
                'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                'Content-Type' => 'audio/wav',
                'Accept' => 'application/json',
            ])->withBody($stream, 'audio/wav')
            ->post($endpoint);

            fclose($stream);
            Storage::delete($path);

            if ($response->successful()) {
                $text = $response->json()['DisplayText'] ?? '';
                return response()->json(['text' => $text]);
            } else {
                logger()->error('Azure response status', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'error' => 'Azure STT failed',
                    'details' => $response->body()
                ], 500);
            }

        } catch (\Exception $e) {
            Storage::delete($path);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


     /**
     * Get SSL Verifier (can be modified based on environment needs).
     */
    protected function getSSLVerifier(): bool
    {
        // Get the value of MY_CUSTOM_VAR from the .env file
        $customVar = config('app.curl'); // 'default_value' is the fallback in case MY_CUSTOM_VAR is not set
        return !(($customVar === "localhost"));
    }
}
