<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    /**
     * Display the settings index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.settings.index');
    }

    /**
     * Show the system settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function system()
    {
        return view('dashboard.settings.system');
    }

    /**
     * Show the account settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        $user = Auth::user();
        return view('dashboard.settings.account', compact('user'));
    }

    /**
     * Show the appearance settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function appearance()
    {
        return view('dashboard.settings.appearance');
    }

    /**
     * Show the notification settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        return view('dashboard.settings.notifications');
    }

    /**
     * Update account settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Update password if provided
        if ($request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak benar.'])->withInput();
            }
            
            $user->password = Hash::make($request->new_password);
        }
        
        // Handle profile photo update
        if ($request->hasFile('profile_photo')) {
            // Validate the uploaded file
            $request->validate([
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }
        
        $user->save();
        
        return redirect()->route('settings.account')->with('success', 'Pengaturan akun berhasil diperbarui.');
    }

    /**
     * Update system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSystem(Request $request)
    {
        // Validate and update system settings
        $request->validate([
            'app_name' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'academic_year' => 'required|string|max:20',
            'timezone' => 'required|string',
            'maintenance_mode' => 'boolean',
        ]);

        // Save system settings (you might want to use a settings package or DB table)
        // For example, using Laravel's built-in config:
        config(['app.name' => $request->app_name]);
        
        // For more permanent storage you'd typically save these in DB
        
        return redirect()->route('settings.system')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    /**
     * Update appearance settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAppearance(Request $request)
    {
        try {
            // Check if settings table exists
            if (!Schema::hasTable('settings')) {
                Log::error('Settings table does not exist. Please run migrations.');
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Database setup incomplete. Please contact administrator.');
            }
            
            // Start a database transaction
            DB::beginTransaction();
            
            // Log the incoming request for debugging
            Log::info('Appearance settings update request', [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            // Validate the incoming request
            $validated = $request->validate([
                'theme_mode' => 'required|in:light,dark,auto',
                'primary_color' => 'required|in:blue,green,purple,red,orange,teal',
                'sidebar_layout' => 'required|in:fixed,collapsed',
                'font_size' => 'required|in:small,medium,large',
                'submission_time' => 'nullable'
            ]);
            
            // Update or create settings
            $settings = [
                'theme_mode' => $validated['theme_mode'],
                'primary_color' => $validated['primary_color'],
                'sidebar_layout' => $validated['sidebar_layout'],
                'font_size' => $validated['font_size']
            ];
            
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key, 'user_id' => auth()->id()],
                    ['value' => $value]
                );
            }
            
            // Commit the transaction
            DB::commit();
            
            // Log success
            Log::info('Appearance settings updated successfully', [
                'user_id' => auth()->id()
            ]);
            
            // Add time parameter to prevent caching issues
            $timestamp = time();
            
            return redirect()
                ->route('settings.appearance', ['t' => $timestamp])
                ->with('success', 'Pengaturan tampilan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log the error
            Log::error('Error updating appearance settings', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateNotifications(Request $request)
    {
        // Validate notification settings
        $request->validate([
            'notifications' => 'required|array',
        ]);

        // Save notification settings to user preferences
        $user = Auth::user();
        $preferences = $user->preferences ?? [];
        
        $preferences['notifications'] = $request->notifications;
        
        $user->preferences = $preferences;
        $user->save();
        
        return redirect()->route('settings.notifications')->with('success', 'Pengaturan notifikasi berhasil diperbarui.');
    }
}
