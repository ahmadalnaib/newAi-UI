<?php

namespace App\Http\Controllers;

use HTMLPurifier;
use Unsplash\Photo;
use Inertia\Inertia;
use HTMLPurifier_Config;
use Unsplash\HttpClient;
use App\Enums\ElementType;
use Illuminate\Support\Str;
use App\Enums\FrameworkType;
use App\Models\TailwindCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TailwindCodeController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $tailwindCode = TailwindCode::where('user_id', auth()->id())
            ->latest()
            ->paginate(8); // Show 9 items per page for 3x3 grid layout

        return Inertia::render('Tailwind/Index', [
            'tailwindCode' => $tailwindCode,
            'isSubscribed' => $user->subscribed(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tailwind/Create', [
            'elementTypes' => ElementType::toArray(),
            'frameworkTypes' => FrameworkType::toArray(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tailwind' => 'string|required',
        ]);


        $cleanHtml = $request->tailwind;

        $snippetPreview = substr(strip_tags($cleanHtml), 30, 40); // Get characters from 30 to 70 of the sanitized HTML
        $uuid = Str::uuid();
        $title = 'Snippet: ' . $snippetPreview . '-' . $uuid;
        $description = 'A code snippet created on ' . now()->toDateTimeString();
        $slug = 'code-snippet-'  . $uuid  . now()->timestamp;

        TailwindCode::create([
            'tailwind' => $cleanHtml,
            'slug' => $slug,
            'description' => $description,
            'user_id' => auth()->id(),
            'title' => $title,
        ]);

        return redirect()->route('dashboard');
    }



    public function edit(TailwindCode $tailwindCode)
    {
        if (request()->user()->cannot('update', $tailwindCode)) {
            abort(403);
        }
        return Inertia::render('Tailwind/Edit', [
            'tailwindCode' => $tailwindCode
        ]);
    }


    public function destroy(TailwindCode $tailwindCode)
    {
        if (request()->user()->cannot('delete', $tailwindCode)) {
            abort(403);
        }

        $tailwindCode->delete();

        return redirect()->route('dashboard');
    }

    public function generateSnippetWithAI(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $request->validate([
            'prompt' => '',
            'elementType' => '',
        ]);

        try {
            // Fetch a random photo from Unsplash
            $imagePath = 'images/1.jpg';
            $photoUrl = Storage::url($imagePath);
            $userId = $request->user()->id;
            $prompt = "Generate a {$request->frameworkType} {$request->elementType} based on the following user instructions: {$request->prompt}.
            build just component with the following requirements:
            1. Provide only the {$request->frameworkType} code.
            2. Ensure that when incorporating images (e.g., hero image, product image), either use descriptive text as a placeholder or apply a gray background with a centered icon to indicate the image location in the HTML.
            This makes it clear that the placeholder can adapt to either a text-based or visual representation, depending on the design scenario.
            3. Do not use the string '```html'.
            4. Do not include any comments in the code.
           5. Every request from the same user should not use the same design.
            User ID: {$userId}.
         6.make only the component, not the entire page.
            ";
            

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

                $tailwind = $generatedText;
                // Cache::put('generatedTailwind', $tailwind);
                session(['generatedTailwind' => $tailwind]);
                return Inertia::render('Tailwind/Create', [
                    'generatedTailwind' => $tailwind,
                ]);
            } else {
                throw new \Exception('Claude AI failed to get response');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Claude AI failed']);
        }
    }
    public function editgenerate(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $request->validate([
            'prompt' => 'required|string',
        ]);

        try {
            // Retrieve the generated Tailwind code from the session
            // $originalTailwind = Cache::get('generatedTailwind');
            $originalTailwind = session('generatedTailwind');

            if (!$originalTailwind) {
                throw new \Exception('Original Tailwind code not found in session');
            }

            $prompt = "
            Edit the following Tcode based on the user instructions: {$request->prompt}.
            Original Code:
            {$originalTailwind}
             Requirements:
            1.Do not use the string '```html'.
             2.Do not include any comments in the code.;
             3.Dot not write Here's the updated code with a hero section added.
            ";

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

                // Update the session with the edited Tailwind code
                // Cache::put('generatedTailwind', $generatedText);
                // $originalTailwind = session('generatedTailwind');
                session(['generatedTailwind' => $generatedText]);

                return Inertia::render('Tailwind/Create', [
                    'generatedTailwind' => $generatedText,
                ]);
            } else {
                throw new \Exception('Claude AI failed to get response');
            }
        } catch (\Exception $e) {
            Log::error('Error editing snippet with AI: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Claude AI failed']);
        }
    }
}
