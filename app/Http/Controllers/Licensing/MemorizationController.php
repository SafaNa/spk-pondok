<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use App\Models\Licensing\StudentMemorization;
use App\Models\Licensing\StudentMemorizationItem;
use App\Models\Licensing\StudentLicense;
use App\Models\Master\Student;
use App\Models\Master\MemorizationType;
use App\Models\Master\AcademicYear;
use Illuminate\Http\Request;

class MemorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_if(! (auth()->user()->isAdmin() || auth()->user()->isMemorizationOfficer()), 403);
            return $next($request);
        });
    }

    /** AJAX: ambil jenjang & hari dari data santri + izin terakhir */
    public function getStudentInfo(Student $student)
    {
        // Map formal education level → MTS / MA / PT
        $formalName     = $student->formalEducation?->name ?? '';
        $educationLevel = null;
        if (preg_match('/SD|SMP/i', $formalName))              $educationLevel = 'MTS';
        elseif (preg_match('/SMA|SMK/i', $formalName))         $educationLevel = 'MA';
        elseif (preg_match('/Perguruan Tinggi|PT/i', $formalName)) $educationLevel = 'PT';

        // Ambil izin pending (yang sedang menunggu validasi hafalan)
        $license = StudentLicense::where('student_id', $student->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        $days = null;
        if ($license) {
            $days = max(1, $license->start_date->diffInDays($license->end_date) + 1);
        }

        $activeYear = AcademicYear::where('status', 'active')->first();
        $hasPending = $activeYear && StudentMemorization::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('is_used', false)
            ->exists();

        return response()->json([
            'education_level' => $educationLevel,
            'days'            => $days,
            'license_found'   => (bool) $license,
            'license_dates'   => $license ? $license->start_date->format('d/m/Y') . ' – ' . $license->end_date->format('d/m/Y') : null,
            'has_pending'     => $hasPending,
        ]);
    }

    /** AJAX: preview checklist items berdasarkan jenjang + hari */
    public function previewItems(Request $request)
    {
        $el   = $request->education_level;
        $days = (int) $request->days;

        $applicableDay = $this->resolveDay($el, $days);

        $items = MemorizationType::where('education_level', $el)
            ->where('day', $applicableDay)
            ->orderBy('id')
            ->get(['id', 'target_description', 'day', 'education_level']);

        return response()->json([
            'items'          => $items,
            'applicable_day' => $applicableDay,
            'requested_day'  => $days,
        ]);
    }

    /** Cari day tertinggi yang tersedia ≤ $days; kalau tidak ada, ambil max absolut */
    private function resolveDay(string $el, int $days): int
    {
        $day = MemorizationType::where('education_level', $el)
            ->where('day', '<=', $days)
            ->max('day');

        // Jika input < semua day yang tersedia, ambil minimum
        return $day ?? (int) MemorizationType::where('education_level', $el)->min('day');
    }

    public function index(Request $request)
    {
        $query = StudentMemorization::with(['student', 'academicYear', 'items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('education_level')) {
            $query->where('education_level', $request->education_level);
        }

        $memorizations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('licensing.memorization.index', compact('memorizations'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        return view('licensing.memorization.create', compact('students'));
    }

    public function store(Request $request)
    {
        $isAjax     = $request->has('ajax');
        $activeYear = AcademicYear::where('status', 'active')->first();

        if (!$activeYear) {
            $msg = 'Tidak ada tahun ajaran aktif. Harap aktifkan tahun ajaran terlebih dahulu.';
            if ($isAjax) return response()->json(['error' => $msg], 422);
            abort(422, $msg);
        }

        try {
            $validated = $request->validate([
                'student_id'      => 'required|exists:students,id',
                'education_level' => 'required|in:MTS,MA,PT',
                'days'            => 'required|integer|min:1|max:365',
                'notes'           => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) return response()->json(['error' => implode(' ', $e->validator->errors()->all())], 422);
            throw $e;
        }

        $existingUnused = StudentMemorization::where('student_id', $validated['student_id'])
            ->where('academic_year_id', $activeYear->id)
            ->where('is_used', false)
            ->first();

        if ($existingUnused) {
            $msg = 'Santri ini masih memiliki riwayat hafalan yang belum selesai atau belum dipakai untuk perizinan.';
            if ($isAjax) return response()->json(['error' => $msg], 422);
            return back()->withInput()->withErrors(['student_id' => $msg]);
        }

        $validated['academic_year_id'] = $activeYear->id;
        $validated['status']           = 'pending';

        $memorization = StudentMemorization::create($validated);

        $applicableDay = $this->resolveDay($validated['education_level'], (int) $validated['days']);

        $types = MemorizationType::where('education_level', $validated['education_level'])
            ->where('day', $applicableDay)
            ->orderBy('id')
            ->get();

        $preChecked = $request->input('pre_checked', []);

        foreach ($types as $type) {
            StudentMemorizationItem::create([
                'student_memorization_id' => $memorization->id,
                'memorization_type_id'    => $type->id,
                'is_checked'              => in_array($type->id, $preChecked),
            ]);
        }

        if ($isAjax) {
            $memorization->load('items.memorizationType');
            return response()->json([
                'memorization_id' => $memorization->id,
                'items' => $memorization->items->map(fn($item) => [
                    'id'                 => $item->id,
                    'target_description' => $item->memorizationType->target_description,
                    'is_checked'         => $item->is_checked,
                    'toggle_url'         => route('admin.memorization-items.toggle', $item->id),
                ]),
            ]);
        }

        return redirect()->route('admin.memorization.show', $memorization->id)
            ->with('success', 'Data hafalan santri berhasil dibuat. Silakan centang item yang sudah diselesaikan.');
    }

    public function show(StudentMemorization $memorization)
    {
        $memorization->load([
            'student',
            'academicYear',
            'items.memorizationType',
        ]);

        // Kelompokkan item berdasarkan hari
        $itemsByDay = $memorization->items->groupBy(fn($item) => $item->memorizationType->day);

        $totalItems   = $memorization->items->count();
        $checkedItems = $memorization->items->where('is_checked', true)->count();

        return view('licensing.memorization.show', compact('memorization', 'itemsByDay', 'totalItems', 'checkedItems'));
    }

    public function edit(StudentMemorization $memorization)
    {
        $students = Student::orderBy('name')->get();
        return view('licensing.memorization.edit', compact('memorization', 'students'));
    }

    public function update(Request $request, StudentMemorization $memorization)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:pending,completed',
            'notes'      => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && $memorization->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] === 'pending') {
            $validated['completed_at'] = null;
        }

        $memorization->update($validated);

        return redirect()->route('admin.memorization.show', $memorization->id)
            ->with('success', 'Data hafalan santri berhasil diperbarui.');
    }

    public function destroy(StudentMemorization $memorization)
    {
        $memorization->delete();
        return redirect()->route('admin.memorization.index')->with('success', 'Data hafalan santri berhasil dihapus.');
    }

    /**
     * AJAX: Toggle item checklist
     */
    public function toggleItem(Request $request, StudentMemorizationItem $item)
    {
        $item->update(['is_checked' => !$item->is_checked]);

        // Cek apakah semua item sudah dicentang → auto-complete
        $memorization = $item->memorization;
        $allChecked   = $memorization->items()->where('is_checked', false)->doesntExist();

        if ($allChecked && $memorization->status !== 'completed') {
            $memorization->update(['status' => 'completed', 'completed_at' => now()]);
        } elseif (!$allChecked && $memorization->status === 'completed') {
            $memorization->update(['status' => 'pending', 'completed_at' => null]);
        }

        return response()->json([
            'is_checked' => $item->is_checked,
            'all_checked' => $allChecked,
            'status' => $memorization->fresh()->status,
        ]);
    }
}
