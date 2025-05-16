<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;
use Exception;

class SettingController extends Controller
{
    public function index()
    {
        // Check if settings table exists first
        if (!Schema::hasTable('settings')) {
            // If table doesn't exist, return empty settings array
            return view('admin.settings.index', ['settings' => []]);
        }
        
        try {
            // Fetch all settings from the database and convert to key-value array
            $settingsCollection = Setting::all();
            $settings = [];
            
            foreach ($settingsCollection as $setting) {
                $settings[$setting->key] = $setting->value;
            }
            
            return view('admin.settings.index', compact('settings'));
        } catch (Exception $e) {
            // Handle any other errors gracefully
            return view('admin.settings.index', ['settings' => []]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'school_code' => 'nullable|string|max:50',
            'school_year' => 'nullable|string|max:20',
            'school_semester' => 'nullable|string|in:Ganjil,Genap',
            'school_address' => 'nullable|string',
            'school_email' => 'nullable|email|max:255',
            'school_phone' => 'nullable|string|max:20',
            'school_website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'theme_color' => 'nullable|string|max:20',
        ]);

        // Check if settings table exists, if not, create it
        if (!Schema::hasTable('settings')) {
            // Create the settings table directly using Schema
            Schema::create('settings', function ($table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // Process settings
        foreach ($request->except(['_token', '_method', 'school_logo', 'school_banner']) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        // Process logo upload
        if ($request->hasFile('school_logo')) {
            $path = $request->file('school_logo')->store('public/settings');
            $relativePath = str_replace('public/', '', $path);
            
            Setting::updateOrCreate(
                ['key' => 'school_logo'],
                ['value' => $relativePath]
            );
        }

        // Process banner upload
        if ($request->hasFile('school_banner')) {
            $path = $request->file('school_banner')->store('public/settings');
            $relativePath = str_replace('public/', '', $path);
            
            Setting::updateOrCreate(
                ['key' => 'school_banner'],
                ['value' => $relativePath]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
