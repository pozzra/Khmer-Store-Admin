<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return Inertia::render('Students/Index', [
            'students' => $students
        ]);
    }

    public function create()
    {
        // Render the create student page
        return Inertia::render('Students/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'gender'     => ['required', 'in:male,female'],
            'phone'      => ['required', 'string', 'max:20'],
            'generation' => ['required', 'string', 'max:50'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'address'    => ['required', 'string', 'max:255'],
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return Inertia::render('Students/edit', [
            'student' => $student
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'gender'     => ['required', 'in:male,female'],
            'phone'      => ['required', 'string', 'max:20'],
            'generation' => ['required', 'string', 'max:50'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:students,email,' . $id],
            'address'    => ['required', 'string', 'max:255'],
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}