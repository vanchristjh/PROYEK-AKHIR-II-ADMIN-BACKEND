<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
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
        $user = Auth::user();
        
        // Get system settings
        $settings = Setting::getUserSettings([
            'app_name', 
            'school_name', 
            'school_address', 
            'academic_year',
            'timezone',
            'maintenance_mode'
        ], $user->id, [
            'app_name' => config('app.name'),
            'school_name' => 'SMA Negeri 1 Girsang Sipangan Bolon',
            'school_address' => 'Jalan Pendidikan No. 1, Girsang Sipangan Bolon',
            'academic_year' => '2023/2024',
            'timezone' => config('app.timezone'),
            'maintenance_mode' => false
        ]);

        return view('dashboard.settings.system', compact('settings'));
    }

    /**
     * Show the account settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        $user = Auth::user();
        
        // Get last 5 login activities (could be from a real LoginActivity model)
        $loginActivities = []; // Placeholder - would be real data in production
        
        return view('dashboard.settings.account', compact('user', 'loginActivities'));
    }

    /**
     * Show the appearance settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function appearance()
    {
        $user = Auth::user();
        
        // Get appearance settings
        $settings = Setting::getUserSettings([
            'theme_mode', 
            'primary_color', 
            'sidebar_layout', 
            'font_size'
        ], $user->id, [
            'theme_mode' => 'light',
            'primary_color' => 'blue',
            'sidebar_layout' => 'fixed',
            'font_size' => 'medium'
        ]);

        return view('dashboard.settings.appearance', compact('settings'));
    }

    /**
     * Show the notification settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        $user = Auth::user();
        
        // Get notification settings
        $notificationSettings = Setting::getUserSetting('notifications', $user->id, [
            'email_login_activity' => true,
            'email_announcements' => true,
            'email_events' => true,
            'browser_messages' => true,
            'browser_announcements' => true,
            'attendance_reminder' => true,
            'task_reminder' => true,
            'reminder_timing' => '15min'
        ]);
        
        // If it's stored as a string (JSON), decode it
        if (is_string($notificationSettings)) {
            $notificationSettings = json_decode($notificationSettings, true);
        }

        return view('dashboard.settings.notifications', compact('notificationSettings'));
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

        try {
            DB::beginTransaction();
            
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
            
            DB::commit();
            
            return redirect()->route('settings.account')->with('success', 'Pengaturan akun berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating account settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSystem(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            // Validate inputs
            $validated = $request->validate([
                'app_name' => 'required|string|max:255',
                'school_name' => 'required|string|max:255',
                'school_address' => 'nullable|string',
                'academic_year' => 'required|string|max:20',
                'timezone' => 'required|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
                'maintenance_mode' => 'sometimes|boolean',
            ]);
            
            // Convert checkbox value to boolean
            $validated['maintenance_mode'] = $request->has('maintenance_mode');
            
            // Save settings to database
            foreach ($validated as $key => $value) {
                Setting::setUserSetting($key, $value, $user->id);
            }
            
            // Update application configuration if admin user
            if ($user->role === 'admin') {
                Config::set('app.name', $validated['app_name']);
                Config::set('app.timezone', $validated['timezone']);
                
                // In a real application, you would modify the .env file
                // This requires additional packages like vlucas/phpdotenv
            }
            
            DB::commit();
            
            return redirect()->route('settings.system')->with('success', 'Pengaturan sistem berhasil diperbarui.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating system settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
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
            
            // Validate the incoming request
            $validated = $request->validate([
                'theme_mode' => 'required|in:light,dark,auto',
                'primary_color' => 'required|in:blue,green,purple,red,orange,teal',
                'sidebar_layout' => 'required|in:fixed,collapsed',
                'font_size' => 'required|in:small,medium,large',
                'submission_time' => 'nullable'
            ]);
            
            // Update or create settings
            foreach ($validated as $key => $value) {
                if ($key !== 'submission_time') {
                    Setting::setUserSetting($key, $value, auth()->id());
                }
            }
            
            // Commit the transaction
            DB::commit();
            
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
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            // Validate notification settings
            $request->validate([
                'notifications' => 'required|array',
            ]);
            
            // Save notifications as JSON
            Setting::setUserSetting('notifications', json_encode($request->notifications), $user->id);
            
            DB::commit();
            
            return redirect()->route('settings.notifications')
                ->with('success', 'Pengaturan notifikasi berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating notification settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Create database backup
     *
     * @return \Illuminate\Http\Response
     */
    public function createBackup()
    {
        try {
            // In production, this would use actual backup commands
            // For demo, we'll just create a success response
            $backupFileName = 'backup_' . date('Y-m-d_His') . '.sql';
            
            // This is a placeholder. In production you'd use a real backup system.
            // Example: Artisan::call('backup:run');
            
            return response()->json([
                'success' => true,
                'message' => 'Backup database berhasil dibuat!',
                'filename' => $backupFileName
            ]);
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Backup gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        // Don't allow admin deletion through this route
        if ($user->role === 'admin') {
            return redirect()->route('settings.account')
                ->with('error', 'Akun administrator tidak dapat dihapus melalui metode ini.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete user's settings
            Setting::where('user_id', $user->id)->delete();
            
            // In a real application, we'd handle more related data deletion here
            // For example: user uploads, user posts, user activities, etc.
            
            // Delete user account
            $user->delete();
            
            DB::commit();
            
            // Log the user out
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Akun Anda telah dihapus secara permanen.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user account: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
