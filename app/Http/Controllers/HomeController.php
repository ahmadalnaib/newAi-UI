<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Inertia\Inertia;
use App\Enums\ElementType;
use App\Enums\FrameworkType;
use App\Models\AiUse;
use App\Models\TailwindCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Termwind\Components\Element;

class HomeController extends Controller
{
    //

    public function __invoke()
    {
        $userIp = request()->ip();
        $sessionId = request()->session()->getId();

        // Check if the IP address is already in the database
        $hasAccess = !AiUse::where('ip_address', $userIp)->where('session_id', $sessionId)->exists();
        $plans = Plan::all();


        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'plans' => $plans,
            'elementTypes' => ElementType::toArray(),
            'frameworkTypes' => FrameworkType::toArray(),
            'hasAccess' => $hasAccess,


        ]);
    }


    public function generateWithFreeAI(Request $request)
    {
        ini_set('max_execution_time', 3600);

        $userIp = $request->ip();
        $sessionId = $request->session()->getId();


        $request->validate([
            'prompt' => '',
            'elementType' => '',
        ]);

        try {

            $prompt = "Generate a {$request->frameworkType} {$request->elementType} based on the following user instructions: {$request->prompt}.
            build just component with the following requirements:
            1. Provide only the {$request->frameworkType} code.
            2. Ensure that when incorporating images (e.g., hero image, product image), either use descriptive text as a placeholder or apply a gray background with a centered icon to indicate the image location in the HTML.
            This makes it clear that the placeholder can adapt to either a text-based or visual representation, depending on the design scenario.
            3.make only the component, not the entire page.
            4. Do not use the string '```html'.
            5. Do not include any comments in the code.";


            $response = Http::withHeaders([
                'x-api-key' => env('CLAUDE_API_KEY'),
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json'
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-5-haiku-20241022',
                'max_tokens' => 8192,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);

            if ($response->ok()) {
              
                $responseData = $response->json();
                $generatedText = $responseData['content'][0]['text'];
                // Log::info($generatedText);
                $componentFromAi = $generatedText;
                AiUse::create([
                    'ip_address' => $userIp,
                    'session_id' => $sessionId,
                ]);
                return Inertia::render('Home', [
                    'componentFromAi' => $componentFromAi,


                ]);
            } else {
                throw new \Exception('Claude AI failed to get response');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Claude AI failed']);
        }
    }
}
