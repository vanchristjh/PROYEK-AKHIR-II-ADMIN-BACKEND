<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return view('guru.settings.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Get current preferences or initialize empty array
        $preferences = is_array($user->preferences) ? $user->preferences : [];
        
        // Update preferences based on form data
        $preferences['email_notifications'] = $request->has('email_notifications');
        $preferences['assignment_reminders'] = $request->has('assignment_reminders');
        $preferences['new_submissions'] = $request->has('new_submissions');
        
        // Additional preferences if they were submitted
        if ($request->has('theme')) {
            $preferences['theme'] = $request->theme;
        }
        
        // Save preferences to user
        $user->preferences = $preferences;
        $user->save();
        
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan');
    }

    /**
     * Display privacy settings page.
     */
    public function privacy()
    {
        $user = auth()->user();
        return view('guru.settings.privacy', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
