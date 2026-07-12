<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Master\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function index()
    {
        $guardians = Guardian::withCount('students')->latest()->paginate(15);
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
            'password'      => 'required|string|min:6',
            'relationship'  => 'required|in:father,mother,guardian,sibling',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'nik'           => 'nullable|string|max:16',
            'job'           => 'nullable|string|max:100',
            'address'       => 'nullable|string',
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $guardian = Guardian::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'relationship' => $request->relationship,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'nik'          => $request->nik,
            'job'          => $request->job,
            'address'      => $request->address,
        ]);

        $guardian->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.guardians.index')
            ->with('success', 'Data wali berhasil ditambahkan.');
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
            'password'      => 'nullable|string|min:6',
            'relationship'  => 'required|in:father,mother,guardian,sibling',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'nik'           => 'nullable|string|max:16',
            'job'           => 'nullable|string|max:100',
            'address'       => 'nullable|string',
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
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
        $students = Student::with('room')
            ->where(function ($query) use ($q) {
                $qLower = strtolower($q);
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $qLower . '%'])
                      ->orWhereRaw('LOWER(nis) LIKE ?', ['%' . $qLower . '%']);
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

    public function destroy(Guardian $guardian)
    {
        $guardian->delete();
        return redirect()->route('admin.guardians.index')
            ->with('success', 'Data wali berhasil dihapus.');
    }
}
