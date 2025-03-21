<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    /**
     * Display the API documentation page.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiDocs()
    {
        return view('dashboard.documentation.api');
    }

    /**
     * Download the API integration guide.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadGuide()
    {
        $file = public_path('downloads/API_INTEGRATION.md');
        
        // If the file doesn't exist in public directory, get it from root
        if (!File::exists($file)) {
            $file = base_path('API_INTEGRATION.md');
            
            // If the file doesn't exist in the root directory either, return an error
            if (!File::exists($file)) {
                return redirect()->back()->with('error', 'API Integration guide not found.');
            }
            
            // Create the downloads directory if it doesn't exist
            if (!File::exists(public_path('downloads'))) {
                File::makeDirectory(public_path('downloads'), 0755, true);
            }
            
            // Copy the file to the public directory for future use
            File::copy($file, public_path('downloads/API_INTEGRATION.md'));
        }
        
        return Response::download($file, 'SMA_API_Integration_Guide.md');
    }
} 