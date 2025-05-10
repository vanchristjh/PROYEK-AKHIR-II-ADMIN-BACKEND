<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiswaProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('siswa.profile.show', compact('user'));
    }
}
