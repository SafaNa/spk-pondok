<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Master\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuardianController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()?->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $guardians = Guardian::withCount('students')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('guardians.index', compact('guardians'));
    }

    public function create()
    {
        return view('guardians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'username'      => 'required|string|max:100|unique:guardians,username',
            'password'      => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'relationship'  => 'required|in:father,mother,guardian,sibling',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'nik'           => 'nullable|string|max:16',
            'job'           => 'nullable|string|max:100',
            'address'       => 'nullable|string',
            'student_ids'   => 'nullable|array',
            'student_ids.*' => [
                'exists:students,id',
                function ($attribute, $value, $fail) {
                    $exists = \Illuminate\Support\Facades\DB::table('student_guardian')
                        ->where('student_id', $value)
                        ->exists();
                    if ($exists) {
                        $student = \App\Models\Master\Student::find($value);
                        $name = $student ? $student->name : 'tersebut';
                        $fail("Santri {$name} sudah memiliki wali lain.");
                    }
                },
            ],
        ]);

        $plainPassword = $request->password;

        $guardian = Guardian::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'password'     => Hash::make($plainPassword),
            'relationship' => $request->relationship,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'nik'          => $request->nik,
            'job'          => $request->job,
            'address'      => $request->address,
        ]);

        $guardian->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.guardians.index')
            ->with('success', 'Data wali berhasil ditambahkan.')
            ->with('created_guardian_name', $guardian->name)
            ->with('created_guardian_username', $guardian->username)
            ->with('created_guardian_password', $plainPassword);
    }

    public function edit(Guardian $guardian)
    {
        $guardian->load('students.room');
        $selectedStudentsJson = $guardian->students->map(fn($s) => [
            'id'   => $s->id,
            'name' => $s->name,
            'nis'  => $s->nis ?? '-',
            'room' => $s->room?->name,
        ])->toJson();
        return view('guardians.edit', compact('guardian', 'selectedStudentsJson'));
    }

    public function update(Request $request, Guardian $guardian)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'username'      => ['required', 'string', 'max:100', Rule::unique('guardians', 'username')->ignore($guardian->id)],
            'password'      => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'relationship'  => 'required|in:father,mother,guardian,sibling',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'nik'           => 'nullable|string|max:16',
            'job'           => 'nullable|string|max:100',
            'address'       => 'nullable|string',
            'student_ids'   => 'nullable|array',
            'student_ids.*' => [
                'exists:students,id',
                function ($attribute, $value, $fail) use ($guardian) {
                    $exists = \Illuminate\Support\Facades\DB::table('student_guardian')
                        ->where('student_id', $value)
                        ->where('guardian_id', '!=', $guardian->id)
                        ->exists();
                    if ($exists) {
                        $student = \App\Models\Master\Student::find($value);
                        $name = $student ? $student->name : 'tersebut';
                        $fail("Santri {$name} sudah memiliki wali lain.");
                    }
                },
            ],
        ]);

        $data = $request->only(['name', 'username', 'relationship', 'phone', 'email', 'nik', 'job', 'address']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $guardian->update($data);
        $guardian->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.guardians.index')
            ->with('success', 'Data wali berhasil diperbarui.');
    }

    public function searchStudents(Request $request)
    {
        $q = $request->input('q', '');
        $guardianId = $request->input('guardian_id');

        $students = Student::with('room')
            ->where(function ($query) use ($q) {
                $qLower = strtolower($q);
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $qLower . '%'])
                      ->orWhereRaw('LOWER(nis) LIKE ?', ['%' . $qLower . '%']);
            })
            ->whereDoesntHave('guardians', function ($q) use ($guardianId) {
                if ($guardianId) {
                    $q->where('guardians.id', '!=', $guardianId);
                }
            })
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn($s) => [
                'id'   => $s->id,
                'name' => $s->name,
                'nis'  => $s->nis ?? '-',
                'room' => $s->room?->name,
            ]);

        return response()->json($students);
    }

    public function resetPassword(Request $request, Guardian $guardian)
    {
        $request->validate([
            'new_password' => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        $plainPassword = $request->new_password;
        $guardian->update(['password' => Hash::make($plainPassword)]);

        return redirect()->route('admin.guardians.index')
            ->with('success', 'Password wali ' . $guardian->name . ' berhasil direset.')
            ->with('reset_guardian_name', $guardian->name)
            ->with('reset_guardian_username', $guardian->username)
            ->with('reset_guardian_password', $plainPassword);
    }

    public function destroy(Guardian $guardian)
    {
        $guardian->delete();
        return redirect()->route('admin.guardians.index')
            ->with('success', 'Data wali berhasil dihapus.');
    }
}
