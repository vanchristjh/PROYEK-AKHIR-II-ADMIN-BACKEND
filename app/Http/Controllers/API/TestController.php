<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Return a test response for public routes
     *
     * @return \Illuminate\Http\Response
     */
    public function publicTest()
    {
        return response()->json([
            'message' => 'Public API is working!'
        ]);
    }

    /**
     * Return a test response for authenticated routes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authTest(Request $request)
    {
        return response()->json([
            'message' => 'Authentication is working!',
            'user' => $request->user()
        ]);
    }
} 