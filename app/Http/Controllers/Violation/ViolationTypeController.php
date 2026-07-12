<?php

namespace App\Http\Controllers\Violation;

use App\Http\Controllers\Controller;
use App\Models\Violation\ViolationType;
use App\Models\Violation\ViolationCategory;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canManageViolations()) abort(403);

        $query = ViolationType::with(['department', 'category']);

        if ($user->isDepartmentOfficer()) {
            $query->where('department_id', $user->department_id);
        }

        // Search by name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('violation_category_id', $request->category_id);
        }

        // Filter by ruleset
        if ($request->filled('ruleset')) {
            $query->where('ruleset', $request->ruleset);
        }

        // Filter by department (admin only)
        if ($user->isAdmin() && $request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $violationTypes = $query->orderBy('code')->paginate(10)->withQueryString();

        $categories = \App\Models\Violation\ViolationCategory::orderBy('points')->get();
        $departments = $user->isAdmin() ? \App\Models\Master\Department::orderBy('code')->get() : collect();

        return view('violation-types.index', compact('violationTypes', 'categories', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->canManageViolations()) abort(403);

        $violationCategories = ViolationCategory::all();
        $departments = Department::departments()->get();

        return view('violation-types.create', compact('violationCategories', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->canManageViolations()) abort(403);

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'code' => 'required|max:20|unique:violation_types,code',
            'name' => 'required|max:255',
            'default_sanction' => 'required|string',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        // Permission check
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        if ($authUser->isDepartmentOfficer() && $validated['department_id'] != $authUser->department_id) {
            abort(403);
        }

        ViolationType::create([
            'department_id' => $validated['department_id'],
            'violation_category_id' => $validated['violation_category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'default_sanction' => $validated['default_sanction'],
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'description' => $validated['description']
        ]);

        return redirect()->route('admin.violation-types.index')->with('success', 'Jenis pelanggaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('admin.violation-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!Auth::user()->canManageViolations()) abort(403);

        $violationType = ViolationType::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) {
            abort(403);
        }

        $violationCategories = ViolationCategory::all();
        $departments = Department::departments()->get();

        return view('violation-types.edit', compact('violationType', 'violationCategories', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->canManageViolations()) abort(403);

        $violationType = ViolationType::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) {
            abort(403);
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'code' => 'required|max:20|unique:violation_types,code,' . $violationType->id,
            'name' => 'required|max:255',
            'default_sanction' => 'required|string',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        // Permission check for target department
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        if ($authUser->isDepartmentOfficer() && $validated['department_id'] != $authUser->department_id) {
            abort(403);
        }

        $violationType->update([
            'department_id' => $validated['department_id'],
            'violation_category_id' => $validated['violation_category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'default_sanction' => $validated['default_sanction'],
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'description' => $validated['description']
        ]);

        return redirect()->route('admin.violation-types.index')->with('success', 'Jenis pelanggaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Auth::user()->canManageViolations()) abort(403);

        $violationType = ViolationType::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isDepartmentOfficer() && $violationType->department_id != $user->department_id) {
            abort(403);
        }

        if ($violationType->records()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis pelanggaran yang sudah digunakan dalam riwayat');
        }

        $violationType->delete();

        return redirect()->route('admin.violation-types.index')->with('success', 'Jenis pelanggaran berhasil dihapus');
    }
}
