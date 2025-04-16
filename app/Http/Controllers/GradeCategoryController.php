<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GradeCategory;

class GradeCategoryController extends Controller
{
    /**
     * Display a listing of the grade categories.
     */
    public function index()
    {
        $categories = GradeCategory::orderBy('name')->paginate(10);
        return view('dashboard.grades.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new grade category.
     */
    public function create()
    {
        return view('dashboard.grades.categories.create');
    }

    /**
     * Store a newly created grade category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        GradeCategory::create($request->all());
        return redirect()->route('grade-categories.index')
            ->with('success', 'Kategori nilai berhasil ditambahkan');
    }

    /**
     * Display the specified grade category.
     */
    public function show(GradeCategory $gradeCategory)
    {
        return view('dashboard.grades.categories.show', compact('gradeCategory'));
    }

    /**
     * Show the form for editing the specified grade category.
     */
    public function edit(GradeCategory $gradeCategory)
    {
        return view('dashboard.grades.categories.edit', compact('gradeCategory'));
    }

    /**
     * Update the specified grade category in storage.
     */
    public function update(Request $request, GradeCategory $gradeCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $gradeCategory->update($request->all());
        return redirect()->route('grade-categories.index')
            ->with('success', 'Kategori nilai berhasil diperbarui');
    }

    /**
     * Remove the specified grade category from storage.
     */
    public function destroy(GradeCategory $gradeCategory)
    {
        $gradeCategory->delete();
        return redirect()->route('grade-categories.index')
            ->with('success', 'Kategori nilai berhasil dihapus');
    }
}
