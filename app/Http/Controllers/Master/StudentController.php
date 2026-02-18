<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Student;
use App\Models\Master\Room;
use App\Models\Master\EducationLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;

// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\StudentImport;
// use App\Exports\StudentTemplateExport;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin() && !auth()->user()->isFinanceSecretary()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }
    // public function downloadTemplate()
    // {
    //     return Excel::download(new StudentTemplateExport, 'template_students.xlsx');
    // }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv',
    //     ]);

    //     try {
    //         Excel::import(new StudentImport, $request->file('file'));
    //         return redirect()->route('students.index')->with('success', 'Students data imported successfully!');
    //     } catch (\Exception $e) {
    //         \Illuminate\Support\Facades\Log::error('Import Error: ' . $e->getMessage());
    //         return redirect()->route('students.index')->with('error', 'Failed to import data: ' . $e->getMessage());
    //     }
    // }

    public function index()
    {
        // Eager load relationships for better performance
        $students = Student::with(['room', 'formalEducation', 'religiousEducation'])->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $rooms = Room::with('rayon')->get(); // Load rayon with rooms for JS filtering if needed
        $rayons = \App\Models\Master\Rayon::orderBy('name')->get();
        $formalLevels = EducationLevel::where('type', 'formal')->get();
        $religiousLevels = EducationLevel::where('type', 'religious')->get();
        $provinces = Province::orderBy('name')->pluck('name', 'code');

        return view('students.create', compact('rooms', 'rayons', 'formalLevels', 'religiousLevels', 'provinces'));
    }

    public function edit(Student $student)
    {
        $rooms = Room::with('rayon')->get();
        $rayons = \App\Models\Master\Rayon::orderBy('name')->get();
        $formalLevels = EducationLevel::where('type', 'formal')->get();
        $religiousLevels = EducationLevel::where('type', 'religious')->get();
        $provinces = Province::orderBy('name')->pluck('name', 'code');

        return view('students.edit', compact('student', 'rooms', 'rayons', 'formalLevels', 'religiousLevels', 'provinces'));
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:students|max:20',
            'name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'birth_place' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'nik' => 'nullable|string|max:16',
            'rayon_id' => 'required|exists:rayons,id',
            'father_name' => 'nullable|string|max:100',
            'father_education' => 'nullable|string|max:100',
            'father_occupation' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'mother_education' => 'nullable|string|max:100',
            'mother_occupation' => 'nullable|string|max:100',
            'entry_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'room_id' => 'required|exists:rooms,id',
            'formal_education_level_id' => 'nullable|exists:education_levels,id',
            'religious_education_level_id' => 'nullable|exists:education_levels,id',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'city_code' => 'required|exists:indonesia_cities,code',
            'district_code' => 'required|exists:indonesia_districts,code',
            'village_code' => 'required|exists:indonesia_villages,code',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,graduated,dropped_out',
        ]);

        if ($request->hasFile('photo')) {
            \Illuminate\Support\Facades\Log::info('Photo detected in store request');
            $validated['photo'] = $request->file('photo')->store('students', 'public');
            \Illuminate\Support\Facades\Log::info('Photo stored at: ' . $validated['photo']);
        } else {
            \Illuminate\Support\Facades\Log::info('No photo detected in store request');
            if ($request->allFiles()) {
                \Illuminate\Support\Facades\Log::info('Files found but not photo: ' . json_encode(array_keys($request->allFiles())));
            } else {
                \Illuminate\Support\Facades\Log::info('No files found in request');
            }
        }

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => [
                'required',
                'max:20',
                Rule::unique('students')->ignore($student->id)
            ],
            'name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'birth_place' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'nik' => 'nullable|string|max:16',
            'rayon_id' => 'required|exists:rayons,id',
            'father_name' => 'nullable|string|max:100',
            'father_education' => 'nullable|string|max:100',
            'father_occupation' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'mother_education' => 'nullable|string|max:100',
            'mother_occupation' => 'nullable|string|max:100',
            'entry_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'room_id' => 'required|exists:rooms,id',
            'formal_education_level_id' => 'nullable|exists:education_levels,id',
            'religious_education_level_id' => 'nullable|exists:education_levels,id',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'city_code' => 'required|exists:indonesia_cities,code',
            'district_code' => 'required|exists:indonesia_districts,code',
            'village_code' => 'required|exists:indonesia_villages,code',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,graduated,dropped_out',
        ]);

        if ($request->hasFile('photo')) {
            \Illuminate\Support\Facades\Log::info('Photo detected in update request');
            if ($student->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students', 'public');
            \Illuminate\Support\Facades\Log::info('Photo stored at: ' . $validated['photo']);
        } else {
            \Illuminate\Support\Facades\Log::info('No photo detected in update request');
        }

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Data santri berhasil dihapus');
    }

    // public function export()
    // {
    //     // Re-implement with English logic later
    // }
}
