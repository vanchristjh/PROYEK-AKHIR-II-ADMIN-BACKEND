<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GradeItem;
use App\Models\GradeCategory;
use App\Models\Subject;
use App\Models\ClassRoom;

class GradeItemController extends Controller
{
    /**
     * Display a listing of the grade items.
     */
    public function index()
    {
        $gradeItems = GradeItem::with(['category', 'subject'])->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.grades.items.index', compact('gradeItems'));
    }

    /**
     * Show the form for creating a new grade item.
     */
    public function create()
    {
        $categories = GradeCategory::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $classes = ClassRoom::orderBy('name')->get();
        return view('dashboard.grades.items.create', compact('categories', 'subjects', 'classes'));
    }

    /**
     * Store a newly created grade item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:grade_categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:class_rooms,id',
            'max_score' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        GradeItem::create($request->all());
        return redirect()->route('grade-items.index')
            ->with('success', 'Item nilai berhasil ditambahkan');
    }

    /**
     * Display the specified grade item.
     */
    public function show(GradeItem $gradeItem)
    {
        $gradeItem->load(['category', 'subject', 'class']);
        return view('dashboard.grades.items.show', compact('gradeItem'));
    }

    /**
     * Show the form for editing the specified grade item.
     */
    public function edit(GradeItem $gradeItem)
    {
        $categories = GradeCategory::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $classes = ClassRoom::orderBy('name')->get();
        return view('dashboard.grades.items.edit', compact('gradeItem', 'categories', 'subjects', 'classes'));
    }

    /**
     * Update the specified grade item in storage.
     */
    public function update(Request $request, GradeItem $gradeItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:grade_categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:class_rooms,id',
            'max_score' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $gradeItem->update($request->all());
        return redirect()->route('grade-items.index')
            ->with('success', 'Item nilai berhasil diperbarui');
    }

    /**
     * Remove the specified grade item from storage.
     */
    public function destroy(GradeItem $gradeItem)
    {
        $gradeItem->delete();
        return redirect()->route('grade-items.index')
            ->with('success', 'Item nilai berhasil dihapus');
    }
}
