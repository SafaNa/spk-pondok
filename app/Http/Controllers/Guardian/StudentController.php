<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Master\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\Province;

class StudentController extends Controller
{
    public function show(Student $student)
    {
        /** @var \App\Models\Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        abort_unless($guardian->students()->where('students.id', $student->id)->exists(), 403);

        $student->load([
            'rayon', 'room', 'formalEducation', 'religiousEducation',
            'province', 'city', 'district', 'village',
            'licenses.leaveCategory', 'licenses.leaveReason', 'licenses.academicYear',
            'violationRecords.violationType.category',
        ]);

        $activeAcademicYear = \App\Models\Master\AcademicYear::where('status', 'active')->first();

        $approvedLeavesCount = 0;
        if ($activeAcademicYear) {
            $approvedLeavesCount = $student->licenses
                ->where('status', 'approved')
                ->where('academic_year_id', $activeAcademicYear->id)
                ->count();
        }

        return view('guardian.students.show', compact('guardian', 'student', 'activeAcademicYear', 'approvedLeavesCount'));
    }

    public function edit(Student $student)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        abort_unless($guardian->students()->where('students.id', $student->id)->exists(), 403);

        $student->load(['province', 'city', 'district', 'village', 'rayon', 'room', 'formalEducation', 'religiousEducation']);
        $provinces = Province::orderBy('name')->pluck('name', 'code');

        return view('guardian.students.edit', compact('guardian', 'student', 'provinces'));
    }

    public function update(Request $request, Student $student)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        abort_unless($guardian->students()->where('students.id', $student->id)->exists(), 403);

        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'nik'                => 'nullable|string|max:16',
            'photo'              => 'nullable|image|max:2048',
            'birth_place'        => 'required|string|max:100',
            'birth_date'         => 'required|date',
            'province_code'      => 'nullable|exists:indonesia_provinces,code',
            'city_code'          => 'nullable|exists:indonesia_cities,code',
            'district_code'      => 'nullable|exists:indonesia_districts,code',
            'village_code'       => 'nullable|exists:indonesia_villages,code',
            'address'            => 'nullable|string|max:500',
            'father_name'        => 'nullable|string|max:100',
            'father_education'   => 'nullable|string|max:100',
            'father_occupation'  => 'nullable|string|max:100',
            'mother_name'        => 'nullable|string|max:100',
            'mother_education'   => 'nullable|string|max:100',
            'mother_occupation'  => 'nullable|string|max:100',
            'phone'              => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        } else {
            unset($validated['photo']);
        }

        $student->update($validated);

        return redirect()->route('guardian.students.edit', $student)
            ->with('success', 'Data santri berhasil diperbarui.');
    }
}
