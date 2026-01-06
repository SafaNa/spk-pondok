<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\Santri;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of violations
     */
    public function index()
    {
        $user = Auth::user();

        $query = RiwayatPelanggaran::with([
            'santri',
            'jenisPelanggaran.kategoriPelanggaran',
            'jenisPelanggaran.departemen',
            'createdBy'
        ])->orderBy('tanggal_kejadian', 'desc');

        // Filter by department for departemen staff
        if ($user->isPengurusDepartemen()) {
            $query->whereHas('jenisPelanggaran', function ($q) use ($user) {
                $q->where('departemen_id', $user->departemen_id);
            });
        }

        $violations = $query->paginate(20);

        // Get summary stats
        $stats = [
            'total' => $violations->total(),
            'pending' => RiwayatPelanggaran::where('status_sanksi', 'belum_selesai')->count(),
            'completed' => RiwayatPelanggaran::where('status_sanksi', 'selesai')->count(),
        ];

        return view('v2.pelanggaran.index', compact('violations', 'stats'));
    }

    /**
     * Show the form for creating a new violation
     */
    public function create()
    {
        $user = Auth::user();
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif. Silakan aktifkan periode terlebih dahulu.');
        }

        // Get active santri
        $santri = Santri::where('status', 'aktif')->orderBy('nama')->get();

        // Get violation types (filtered by department for departemen staff)
        if ($user->isPengurusDepartemen()) {
            $jenisPelanggaran = JenisPelanggaran::where('departemen_id', $user->departemen_id)
                ->where('is_active', true)
                ->with('kategoriPelanggaran')
                ->orderBy('nama_pelanggaran')
                ->get();
        } else {
            $jenisPelanggaran = JenisPelanggaran::where('is_active', true)
                ->with('kategoriPelanggaran')
                ->orderBy('nama_pelanggaran')
                ->get();
        }

        return view('v2.pelanggaran.create', compact('santri', 'jenisPelanggaran', 'periode'));
    }

    /**
     * Store a newly created violation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id',
            'tanggal_kejadian' => 'required|date',
            'sanksi' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $jenisPelanggaran = JenisPelanggaran::findOrFail($validated['jenis_pelanggaran_id']);
        $periode = Periode::where('is_active', true)->first();

        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif');
        }

        // Check if user has permission for this department
        $user = Auth::user();
        if ($user->isPengurusDepartemen() && $jenisPelanggaran->departemen_id != $user->departemen_id) {
            abort(403, 'Anda tidak memiliki akses untuk mencatat pelanggaran departemen ini');
        }

        RiwayatPelanggaran::create([
            'santri_id' => $validated['santri_id'],
            'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
            'periode_id' => $periode->id,
            'tanggal_kejadian' => $validated['tanggal_kejadian'],
            'tanggal_input' => now(),
            'sanksi' => $validated['sanksi'],
            'status_sanksi' => 'belum_selesai',
            'catatan' => $validated['catatan'],
            'created_by' => Auth::id()
        ]);

        return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil dicatat');
    }

    /**
     * Display the specified violation
     */
    public function show($id)
    {
        $violation = RiwayatPelanggaran::with([
            'santri',
            'jenisPelanggaran.kategoriPelanggaran',
            'jenisPelanggaran.departemen',
            'periode',
            'createdBy'
        ])->findOrFail($id);

        return view('v2.pelanggaran.detail', compact('violation'));
    }

    /**
     * Verify/complete a sanction
     */
    public function verifySanksi(Request $request, $id)
    {
        $violation = RiwayatPelanggaran::findOrFail($id);

        // Check if user has permission
        $user = Auth::user();
        if ($user->isPengurusDepartemen()) {
            $violation->load('jenisPelanggaran');
            if ($violation->jenisPelanggaran->departemen_id != $user->departemen_id) {
                abort(403, 'Anda tidak memiliki akses untuk memverifikasi sanksi departemen ini');
            }
        }

        $violation->update([
            'status_sanksi' => 'selesai',
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Sanksi berhasil diverifikasi sebagai selesai');
    }

    /**
     * Display violation history for a santri
     */
    public function riwayat($santriId)
    {
        $santri = Santri::findOrFail($santriId);

        $violations = RiwayatPelanggaran::where('santri_id', $santriId)
            ->with([
                'jenisPelanggaran.kategoriPelanggaran',
                'jenisPelanggaran.departemen',
                'periode',
                'createdBy',
                'verifiedBy'
            ])
            ->orderBy('tanggal_kejadian', 'desc')
            ->get();

        // Group by periode
        $violationsByPeriode = $violations->groupBy('periode_id');

        return view('pelanggaran.riwayat', compact('santri', 'violations', 'violationsByPeriode'));
    }

    /**
     * Display a listing for V2
     */
    public function indexV2()
    {
        $user = Auth::user();

        $query = RiwayatPelanggaran::with([
            'santri',
            'jenisPelanggaran.kategoriPelanggaran',
            'jenisPelanggaran.departemen',
            'createdBy'
        ])->orderBy('tanggal_kejadian', 'desc');

        // Filter by department for departemen staff
        if ($user->isPengurusDepartemen()) {
            $query->whereHas('jenisPelanggaran', function ($q) use ($user) {
                $q->where('departemen_id', $user->departemen_id);
            });
        }

        $violations = $query->paginate(20);

        // Get summary stats
        $stats = [
            'total' => $violations->total(),
            'pending' => RiwayatPelanggaran::where('status_sanksi', 'belum_selesai')->count(),
            'completed' => RiwayatPelanggaran::where('status_sanksi', 'selesai')->count(),
        ];

        return view('v2.pelanggaran.index', compact('violations', 'stats'));
    }

    /**
     * Display the specified violation for V2
     */
    public function showV2($id)
    {
        $violation = RiwayatPelanggaran::with([
            'santri',
            'jenisPelanggaran.kategoriPelanggaran',
            'jenisPelanggaran.departemen',
            'periode',
            'createdBy',
            'verifiedBy'
        ])->findOrFail($id);

        return view('v2.pelanggaran.detail', compact('violation'));
    }
    /**
     * Show the form for editing the specified violation.
     */
    public function edit($id)
    {
        $violation = RiwayatPelanggaran::with('jenisPelanggaran')->findOrFail($id);
        $user = Auth::user();

        // Check permission
        if ($user->isPengurusDepartemen() && $violation->jenisPelanggaran->departemen_id != $user->departemen_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran ini');
        }

        // Check if pengurus perizinan (cannot edit)
        if ($user->isPengurusPerizinan()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran');
        }

        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $santri = Santri::where('status', 'aktif')->orderBy('nama')->get();

        // Get violation types
        if ($user->isPengurusDepartemen()) {
            $jenisPelanggaran = JenisPelanggaran::where('departemen_id', $user->departemen_id)
                ->where('is_active', true)
                ->with('kategoriPelanggaran')
                ->orderBy('nama_pelanggaran')
                ->get();
        } else {
            $jenisPelanggaran = JenisPelanggaran::where('is_active', true)
                ->with('kategoriPelanggaran')
                ->orderBy('nama_pelanggaran')
                ->get();
        }

        return view('v2.pelanggaran.edit', compact('violation', 'santri', 'jenisPelanggaran', 'periode'));
    }

    /**
     * Update the specified violation in storage.
     */
    public function update(Request $request, $id)
    {
        $violation = RiwayatPelanggaran::findOrFail($id);
        $user = Auth::user();

        if ($user->isPengurusDepartemen()) {
            $violation->load('jenisPelanggaran');
            if ($violation->jenisPelanggaran->departemen_id != $user->departemen_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran ini');
            }
        }
        if ($user->isPengurusPerizinan()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pelanggaran');
        }

        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id',
            'tanggal_kejadian' => 'required|date',
            'sanksi' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        // If jenis violation changed, check department access for new type
        $jenisPelanggaran = JenisPelanggaran::findOrFail($validated['jenis_pelanggaran_id']);
        if ($user->isPengurusDepartemen() && $jenisPelanggaran->departemen_id != $user->departemen_id) {
            abort(403, 'Anda tidak memiliki akses untuk jenis pelanggaran departemen ini');
        }

        $violation->update([
            'santri_id' => $validated['santri_id'],
            'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
            'tanggal_kejadian' => $validated['tanggal_kejadian'],
            'sanksi' => $validated['sanksi'],
            'catatan' => $validated['catatan']
        ]);

        return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil diperbarui');
    }

    /**
     * Remove the specified violation from storage.
     */
    public function destroy($id)
    {
        $violation = RiwayatPelanggaran::findOrFail($id);
        $user = Auth::user();

        if ($user->isPengurusDepartemen()) {
            $violation->load('jenisPelanggaran');
            if ($violation->jenisPelanggaran->departemen_id != $user->departemen_id) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus pelanggaran ini');
            }
        }
        if ($user->isPengurusPerizinan()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pelanggaran');
        }

        $violation->delete();

        return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil dihapus');
    }
}
