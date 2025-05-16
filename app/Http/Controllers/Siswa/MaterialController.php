<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the materials.
     */
    public function index()
    {
        // Add your logic to retrieve materials for students
        return view('siswa.materials.index');
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        return view('siswa.materials.create');
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        // Add logic to store a material
    }

    /**
     * Display the specified material.
     */
    public function show(string $id)
    {
        return view('siswa.materials.show', compact('id'));
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(string $id)
    {
        return view('siswa.materials.edit', compact('id'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, string $id)
    {
        // Add logic to update a material
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(string $id)
    {
        // Add logic to delete a material
    }
}
