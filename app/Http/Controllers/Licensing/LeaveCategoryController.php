<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use App\Models\Licensing\LeaveCategory;
use App\Models\Licensing\LeaveReason;
use Illuminate\Http\Request;

class LeaveCategoryController extends Controller
{
    public function index()
    {
        $categories = LeaveCategory::withCount('reasons')->orderBy('order')->get();
        return view('leave-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('leave-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:100',
            'max_duration'        => 'nullable|string|max:255',
            'notes'               => 'nullable|string',
            'is_fixed_duration'   => 'boolean',
            'duration_days'       => 'nullable|integer|min:1',
            'order'               => 'nullable|integer|min:0',
            'reasons'             => 'nullable|array',
            'reasons.*.reason'    => 'nullable|string|max:255',
            'reasons.*.can_skip'  => 'nullable|boolean',
        ]);

        $category = LeaveCategory::create([
            'name'                => $validated['name'],
            'max_duration'        => $validated['max_duration'] ?? null,
            'is_fixed_duration'   => $request->boolean('is_fixed_duration'),
            'duration_days'       => $request->boolean('is_fixed_duration') ? ($validated['duration_days'] ?? null) : null,
            'notes'               => $validated['notes'] ?? null,
            'order'               => $validated['order'] ?? 0,
        ]);

        $reasons = $request->input('reasons', []);
        foreach ($reasons as $i => $reasonData) {
            if (empty($reasonData['reason'])) continue;
            LeaveReason::create([
                'leave_category_id'   => $category->id,
                'reason'              => $reasonData['reason'],
                'can_skip_validation' => !empty($reasonData['can_skip']),
                'order'               => $i,
            ]);
        }

        return redirect()->route('admin.leave-categories.index')
            ->with('success', 'Kategori kepulangan berhasil ditambahkan.');
    }

    public function edit(LeaveCategory $leaveCategory)
    {
        $leaveCategory->load('reasons');
        return view('leave-categories.edit', compact('leaveCategory'));
    }

    public function update(Request $request, LeaveCategory $leaveCategory)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:100',
            'max_duration'        => 'nullable|string|max:255',
            'notes'               => 'nullable|string',
            'is_fixed_duration'   => 'boolean',
            'duration_days'       => 'nullable|integer|min:1',
            'order'               => 'nullable|integer|min:0',
            'reasons'             => 'nullable|array',
            'reasons.*.reason'    => 'nullable|string|max:255',
            'reasons.*.can_skip'  => 'nullable|boolean',
        ]);

        $leaveCategory->update([
            'name'                => $validated['name'],
            'max_duration'        => $validated['max_duration'] ?? null,
            'is_fixed_duration'   => $request->boolean('is_fixed_duration'),
            'duration_days'       => $request->boolean('is_fixed_duration') ? ($validated['duration_days'] ?? null) : null,
            'notes'               => $validated['notes'] ?? null,
            'order'               => $validated['order'] ?? 0,
        ]);

        $leaveCategory->reasons()->delete();
        $reasons = $request->input('reasons', []);
        foreach ($reasons as $i => $reasonData) {
            if (empty($reasonData['reason'])) continue;
            LeaveReason::create([
                'leave_category_id'   => $leaveCategory->id,
                'reason'              => $reasonData['reason'],
                'can_skip_validation' => !empty($reasonData['can_skip']),
                'order'               => $i,
            ]);
        }

        return redirect()->route('admin.leave-categories.index')
            ->with('success', 'Kategori kepulangan berhasil diperbarui.');
    }

    public function reasons(LeaveCategory $leaveCategory)
    {
        return response()->json(
            $leaveCategory->reasons()->orderBy('order')->get(['id', 'reason'])
        );
    }

    public function destroy(LeaveCategory $leaveCategory)
    {
        $leaveCategory->delete();
        return redirect()->route('admin.leave-categories.index')
            ->with('success', 'Kategori kepulangan berhasil dihapus.');
    }
}
