<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display the help index page.
     */    public function index()
    {
        $user = auth()->user();
        return view('guru.help.index', compact('user'));
    }
    
    /**
     * Display the tutorial page.
     */
    public function tutorial()
    {
        $user = auth()->user();
        return view('guru.help.tutorial', compact('user'));
    }
}
