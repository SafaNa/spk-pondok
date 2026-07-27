<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Master\Student;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Room;
use App\Models\Master\Rayon;
use App\Models\Master\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            $user = Auth::user();
            
            // Allow Department Officers to view (index, show)
            if ($user?->isDepartmentOfficer()) {
                if (in_array(request()->route()->getActionMethod(), ['index', 'show'])) {
                    return $next($request);
                }
            }

            // For other methods, require Admin
            if (!$user?->isAdmin()) {
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
    //         return redirect()->route('admin.students.index')->with('success', 'Students data imported successfully!');
    //     } catch (\Exception $e) {
    //         \Illuminate\Support\Facades\Log::error('Import Error: ' . $e->getMessage());
    //         return redirect()->route('admin.students.index')->with('error', 'Failed to import data: ' . $e->getMessage());
    //     }
    // }

    public function index(Request $request)
    {
        $activeAcademicYear = \App\Models\Master\AcademicYear::where('status', 'active')->first();
        
        $query = Student::with(['rayon', 'room', 'formalEducation', 'religiousEducation'])
            ->withCount(['licenses as approved_leaves_count' => function ($q) use ($activeAcademicYear) {
                $q->where('status', 'approved');
                if ($activeAcademicYear) {
                    $q->where('academic_year_id', $activeAcademicYear->id);
                }
            }]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('education_level')) {
            $query->where('formal_education_level_id', $request->education_level);
        }

        if ($request->filled('rayon')) {
            $query->where('rayon_id', $request->rayon);
        }

        if ($request->filled('room')) {
            $query->where('room_id', $request->room);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('region')) {
            $region = $request->region;
            
            if (str_starts_with($region, 'Kec. ')) {
                $districtName = substr($region, 5);
                $query->whereHas('district', function($q) use ($districtName) {
                    $q->where('name', $districtName);
                })->whereHas('city', function($q) {
                    $q->where('name', 'like', '%SUMENEP%');
                });
            } elseif ($region === 'Kabupaten Pamekasan') {
                $query->whereHas('city', function($q) {
                    $q->where('name', 'like', '%PAMEKASAN%');
                });
            } elseif ($region === 'Kabupaten Sampang') {
                $query->whereHas('city', function($q) {
                    $q->where('name', 'like', '%SAMPANG%');
                });
            } elseif ($region === 'Kabupaten Bangkalan') {
                $query->whereHas('city', function($q) {
                    $q->where('name', 'like', '%BANGKALAN%');
                });
            } elseif ($region === 'Luar Madura') {
                $query->whereHas('city', function($q) {
                    $q->where('name', 'not like', '%SUMENEP%')
                      ->where('name', 'not like', '%PAMEKASAN%')
                      ->where('name', 'not like', '%SAMPANG%')
                      ->where('name', 'not like', '%BANGKALAN%');
                });
            }
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        $rayons = Rayon::orderBy('name')->get();
        $rooms = Room::with('rayon')->orderBy('name')->get();
        $educationLevels = EducationLevel::where('type', 'formal')->orderBy('name')->get();

        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'total_rayons' => Rayon::count(),
            'total_rooms' => Room::count(),
        ];

        $rawStats = Student::select('city_code', 'district_code', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('city_code')
            ->groupBy('city_code', 'district_code')
            ->with(['city', 'district'])
            ->get();

        $sumenepStats = [];
        $pamekasanTotal = 0;
        $sampangTotal = 0;
        $bangkalanTotal = 0;
        $luarMaduraTotal = 0;

        foreach ($rawStats as $stat) {
            if (!$stat->city) continue;

            $cityName = strtoupper($stat->city->name);

            if (str_contains($cityName, 'SUMENEP')) {
                if ($stat->district) {
                    $label = 'Kec. ' . ucwords(strtolower($stat->district->name));
                    if (!isset($sumenepStats[$label])) $sumenepStats[$label] = 0;
                    $sumenepStats[$label] += $stat->total;
                }
            } elseif (str_contains($cityName, 'PAMEKASAN')) {
                $pamekasanTotal += $stat->total;
            } elseif (str_contains($cityName, 'SAMPANG')) {
                $sampangTotal += $stat->total;
            } elseif (str_contains($cityName, 'BANGKALAN')) {
                $bangkalanTotal += $stat->total;
            } else {
                $luarMaduraTotal += $stat->total;
            }
        }

        arsort($sumenepStats);

        $finalStats = [];
        
        foreach ($sumenepStats as $label => $total) {
            $finalStats[$label] = $total;
        }

        if ($pamekasanTotal > 0) $finalStats['Kabupaten Pamekasan'] = $pamekasanTotal;
        if ($sampangTotal > 0) $finalStats['Kabupaten Sampang'] = $sampangTotal;
        if ($bangkalanTotal > 0) $finalStats['Kabupaten Bangkalan'] = $bangkalanTotal;
        if ($luarMaduraTotal > 0) $finalStats['Luar Madura'] = $luarMaduraTotal;

        $chartData = [
            'labels' => array_keys($finalStats),
            'series' => array_values($finalStats),
        ];
        
        $regionOptions = array_keys($finalStats);

        return view('students.index', compact(
            'students', 'rayons', 'rooms', 'educationLevels', 
            'stats', 'chartData', 'activeAcademicYear', 'regionOptions'
        ));
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
        $student->load('guardians');
        $rooms = Room::with('rayon')->get();
        $rayons = \App\Models\Master\Rayon::orderBy('name')->get();
        $formalLevels = EducationLevel::where('type', 'formal')->get();
        $religiousLevels = EducationLevel::where('type', 'religious')->get();
        $provinces = Province::orderBy('name')->pluck('name', 'code');

        return view('students.edit', compact(
            'student', 'rooms', 'rayons', 'formalLevels', 'religiousLevels', 'provinces'
        ));
    }

    public function show(Student $student)
    {
        $student->load([
            'formalEducation', 
            'religiousEducation', 
            'rayon', 
            'room',
            'guardians',
            'violationRecords.violationType.category',
            'licenses.leaveCategory',
            'licenses.leaveReason'
        ]);

        $activeAcademicYear = \App\Models\Master\AcademicYear::where('status', 'active')->first();
        
        $approved_leaves_count = 0;
        if ($activeAcademicYear) {
            $approved_leaves_count = $student->licenses
                ->where('status', 'approved')
                ->where('academic_year_id', $activeAcademicYear->id)
                ->count();
        }

        return view('students.show', compact('student', 'activeAcademicYear', 'approved_leaves_count'));
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
            // Wali
            'wali_name'         => 'nullable|string|max:100',
            'wali_username'     => 'required_with:wali_name|nullable|string|max:50|unique:guardians,username',
            'wali_password'     => ['required_with:wali_name', 'nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'wali_phone'        => 'nullable|string|max:20',
            'wali_email'        => 'nullable|email|max:100',
            'wali_relationship' => 'nullable|in:father,mother,guardian,sibling',
        ]);

        if ($request->hasFile('photo')) {
            \Illuminate\Support\Facades\Log::info('Photo detected in store request');
            $validated['photo'] = \App\Services\ImageService::processAndSaveAvatar($request->file('photo'), 'students');
            \Illuminate\Support\Facades\Log::info('Photo stored at: ' . $validated['photo']);
        } else {
            \Illuminate\Support\Facades\Log::info('No photo detected in store request');
            if ($request->allFiles()) {
                \Illuminate\Support\Facades\Log::info('Files found but not photo: ' . json_encode(array_keys($request->allFiles())));
            } else {
                \Illuminate\Support\Facades\Log::info('No files found in request');
            }
        }

        $student = Student::create($validated);

        $createdGuardian = null;
        if ($request->filled('wali_name')) {
            $guardian = Guardian::create([
                'name'         => $request->wali_name,
                'username'     => $request->wali_username,
                'password'     => Hash::make($request->wali_password),
                'phone'        => $request->wali_phone,
                'email'        => $request->wali_email,
                'relationship' => $request->wali_relationship ?? 'father',
            ]);
            $student->guardians()->attach($guardian->id);
            $createdGuardian = [
                'name'     => $guardian->name,
                'username' => $guardian->username,
                'password' => $request->wali_password,
            ];
        }

        $redirect = redirect()->route('admin.students.index')
            ->with('success', 'Data santri berhasil ditambahkan');

        if ($createdGuardian) {
            $redirect = $redirect
                ->with('created_guardian_name', $createdGuardian['name'])
                ->with('created_guardian_username', $createdGuardian['username'])
                ->with('created_guardian_password', $createdGuardian['password']);
        }

        return $redirect;
    }

    public function update(Request $request, Student $student)
    {
        $existingWaliId = $request->input('wali_id');

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
            // Wali
            'wali_name'         => [
                'nullable', 'string', 'max:100',
                function ($attribute, $value, $fail) use ($student, $existingWaliId) {
                    if ($value && !$existingWaliId && $student->guardians()->exists()) {
                        $fail('Santri ini sudah memiliki wali. Tidak bisa menambahkan wali baru.');
                    }
                }
            ],
            'wali_username'     => [
                'required_with:wali_name', 'nullable', 'string', 'max:50',
                Rule::unique('guardians', 'username')->ignore($existingWaliId),
            ],
            'wali_password'     => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'wali_phone'        => 'nullable|string|max:20',
            'wali_email'        => 'nullable|email|max:100',
            'wali_relationship' => 'nullable|in:father,mother,guardian,sibling',
        ]);

        if ($request->hasFile('photo')) {
            \Illuminate\Support\Facades\Log::info('Photo detected in update request');
            if ($student->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = \App\Services\ImageService::processAndSaveAvatar($request->file('photo'), 'students');
            \Illuminate\Support\Facades\Log::info('Photo stored at: ' . $validated['photo']);
        } else {
            \Illuminate\Support\Facades\Log::info('No photo detected in update request');
        }

        $student->update($validated);

        if ($request->filled('wali_name')) {
            $waliData = [
                'name'         => $request->wali_name,
                'username'     => $request->wali_username,
                'phone'        => $request->wali_phone,
                'email'        => $request->wali_email,
                'relationship' => $request->wali_relationship ?? 'father',
            ];
            if ($request->filled('wali_password')) {
                $waliData['password'] = Hash::make($request->wali_password);
            }

            if ($existingWaliId) {
                Guardian::where('id', $existingWaliId)->update($waliData);
            } else {
                if (!$request->filled('wali_password')) {
                    $waliData['password'] = Hash::make('guardian123');
                }
                $guardian = Guardian::create($waliData);
                $student->guardians()->attach($guardian->id);
            }
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->route('admin.students.index')
            ->with('success', 'Data santri berhasil dihapus');
    }

    // public function export()
    // {
    //     // Re-implement with English logic later
    // }
}
