<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use App\Models\Licensing\StudentMemorization;
use App\Models\Licensing\StudentMemorizationItem;
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
        $activeYear = AcademicYear::where('status', 'active')->first();
        abort_if(!$activeYear, 422, 'Tidak ada tahun ajaran aktif. Harap aktifkan tahun ajaran terlebih dahulu.');

        $validated = $request->validate([
            'student_id'      => 'required|exists:students,id',
            'education_level' => 'required|in:MTS,MA,PT',
            'days'            => 'required|integer|min:1|max:365',
            'notes'           => 'nullable|string',
        ]);

        // Cek apakah santri masih punya hafalan yang belum dipakai (aktif) pada tahun ajaran ini
        $existingUnused = StudentMemorization::where('student_id', $validated['student_id'])
            ->where('academic_year_id', $activeYear->id)
            ->where('is_used', false)
            ->first();

        if ($existingUnused) {
            return back()->withInput()->withErrors([
                'student_id' => 'Santri ini masih memiliki riwayat hafalan yang belum selesai atau belum dipakai untuk perizinan.'
            ]);
        }

        $validated['academic_year_id'] = $activeYear->id;
        $validated['status']           = 'pending';

        $memorization = StudentMemorization::create($validated);

        // Generate item checklist dari memorization_types sesuai jenjang dan jumlah hari
        $types = MemorizationType::where('education_level', $validated['education_level'])
            ->where('day', $validated['days'])
            ->orderBy('day')
            ->get();

        foreach ($types as $type) {
            StudentMemorizationItem::create([
                'student_memorization_id' => $memorization->id,
                'memorization_type_id'    => $type->id,
                'is_checked'              => false,
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
