<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = [];
        
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        // Only include these fields if they exist in the users table
        // After running the migration, you can uncomment these lines
        // if ($request->has('nisn')) {
        //     $data['nisn'] = $request->nisn;
        // }
        
        // if ($request->has('nip')) {
        //     $data['nip'] = $request->nip;
        // }
        
        $user->update($data);
        
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}