<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Licensing\StudentLicense;
use App\Models\Licensing\LicenseExtension;
use App\Models\Licensing\MassLeave;
use App\Models\Licensing\MassLeaveStudent;
use App\Models\Finance\SppPayment;
use App\Models\Violation\ViolationRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $students   = $guardian->students()->with(['rayon', 'room', 'formalEducation', 'religiousEducation'])->get();
        $studentIds = $students->pluck('id');

        $activeYear = \App\Models\Master\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $totalLicenses  = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->count();
        $approvedCount  = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->where('status', 'approved')->count();
        $pendingCount   = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->where('status', 'pending')->count();
        $rejectedCount  = StudentLicense::whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId)->where('status', 'rejected')->count();

        // Tambahkan pengajuan perpanjangan ke perhitungan
        $extensions = \App\Models\Licensing\LicenseExtension::whereHas('studentLicense', function($q) use ($studentIds, $activeYearId) {
            $q->whereIn('student_id', $studentIds)->where('academic_year_id', $activeYearId);
        })->get();

        $extTotal    = $extensions->count();
        $extApproved = $extensions->where('status', 'approved')->count();
        $extPending  = $extensions->where('status', 'pending')->count();
        $extRejected = $extensions->where('status', 'rejected')->count();

        $recentLicenses = StudentLicense::with(['student', 'extensions'])
            ->whereIn('student_id', $studentIds)
            ->where('academic_year_id', $activeYearId)
            ->latest()
            ->limit(5)
            ->get();

        // Active Mass Leave Event for Guardian
        MassLeave::closeExpiredEvents();
        $activeMassLeave = MassLeave::where('status', 'active')->first();
        $blockedMassLeaveStudents = collect();
        $checkedOutMassLeaveStudents = collect();

        if ($activeMassLeave) {
            $blockedMassLeaveStudents = $students->filter(function ($student) {
                return ViolationRecord::where('student_id', $student->id)
                    ->where('sanction_status', 'pending')
                    ->exists();
            });

            $checkedOutMassLeaveStudents = $students->filter(function ($student) use ($activeMassLeave) {
                return MassLeaveStudent::where('mass_leave_id', $activeMassLeave->id)
                    ->where('student_id', $student->id)
                    ->exists();
            });
        }

        // Notifikasi: kumpulkan dari izin, pelanggaran, SPP
        $notifications = collect();

        // 1. Dari izin individu
        $licenses = StudentLicense::with(['student', 'leaveCategory', 'leaveReason', 'extensions.studentLicense.student'])
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->limit(50)
            ->get();

        foreach ($licenses as $lic) {
            $name  = $lic->student?->name ?? '-';
            $start = $lic->start_date?->format('d-m-Y') ?? '-';
            $end   = $lic->end_date?->format('d-m-Y') ?? '-';

            // Saat dibuat (pending)
            $notifications->push([
                'title'     => 'Pengajuan Izin Pulang',
                'message'   => "Ananda {$name} telah mengajukan izin pulang/keluar. Tanggal: {$start} s.d {$end}. Pengajuan sedang dalam proses persetujuan pengurus.",
                'icon'      => 'edit_note',
                'color'     => 'amber',
                'timestamp' => $lic->created_at,
                'link'      => route('guardian.licenses.show', $lic->id),
            ]);

            // Disetujui
            if ($lic->status === 'approved' && $lic->approved_at) {
                $notifications->push([
                    'title'     => 'Izin Pulang Disetujui',
                    'message'   => "Ananda {$name} telah mendapat izin pulang. Tanggal: {$start} s.d {$end}. Harap jaga dan awasi kepulangan ananda.",
                    'icon'      => 'check_circle',
                    'color'     => 'green',
                    'timestamp' => $lic->approved_at,
                    'link'      => route('guardian.licenses.show', $lic->id),
                ]);
            }

            // Ditolak
            if ($lic->status === 'rejected' && $lic->rejected_at) {
                $notifications->push([
                    'title'     => 'Izin Pulang Ditolak',
                    'message'   => "Pengajuan izin pulang Ananda {$name} tidak dapat disetujui saat ini. Silakan hubungi pihak pesantren untuk informasi lebih lanjut.",
                    'icon'      => 'cancel',
                    'color'     => 'red',
                    'timestamp' => $lic->rejected_at,
                    'link'      => route('guardian.licenses.show', $lic->id),
                ]);
            }

            // Santri kembali
            if ($lic->actual_return_date) {
                $returnDate = \Carbon\Carbon::parse($lic->actual_return_date)->format('d-m-Y');
                $status     = $lic->is_late ? 'Terlambat ' . $lic->late_days . ' hari' : 'Tepat waktu';
                $notifications->push([
                    'title'     => 'Santri Kembali ke Pesantren',
                    'message'   => "Ananda {$name} telah kembali ke pesantren. Tanggal kembali: {$returnDate}. Status: {$status}.",
                    'icon'      => 'home',
                    'color'     => 'blue',
                    'timestamp' => $lic->updated_at,
                    'link'      => route('guardian.licenses.show', $lic->id),
                ]);
            }

            // Perpanjangan izin
            foreach ($lic->extensions as $ext) {
                $newDate = $ext->requested_new_end_date instanceof \Carbon\Carbon
                    ? $ext->requested_new_end_date->format('d-m-Y')
                    : \Carbon\Carbon::parse($ext->requested_new_end_date)->format('d-m-Y');

                if ($ext->status === 'approved' && $ext->approved_at) {
                    $notifications->push([
                        'title'     => 'Perpanjangan Izin Disetujui',
                        'message'   => "Perpanjangan izin Ananda {$name} telah disetujui. Tanggal kembali yang baru: {$newDate}. Mohon diperhatikan.",
                        'icon'      => 'event_available',
                        'color'     => 'green',
                        'timestamp' => $ext->approved_at,
                        'link'      => route('guardian.licenses.show', $lic->id),
                    ]);
                }

                if ($ext->status === 'rejected' && $ext->rejected_at) {
                    $notifications->push([
                        'title'     => 'Perpanjangan Izin Ditolak',
                        'message'   => "Perpanjangan izin Ananda {$name} tidak dapat disetujui. Harap santri kembali sesuai jadwal semula.",
                        'icon'      => 'event_busy',
                        'color'     => 'red',
                        'timestamp' => $ext->rejected_at,
                        'link'      => route('guardian.licenses.show', $lic->id),
                    ]);
                }
            }
        }

        // 2. Dari pelanggaran
        $violations = ViolationRecord::with(['student', 'violationType'])
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->limit(20)
            ->get();

        foreach ($violations as $vio) {
            $name     = $vio->student?->name ?? '-';
            $typeName = $vio->violationType?->name ?? '-';
            $sanction = $vio->violationType?->default_sanction ?? '-';
            $notifications->push([
                'title'     => 'Pemberitahuan Pelanggaran',
                'message'   => "Ananda {$name} tercatat melakukan pelanggaran: {$typeName}. Sanksi: {$sanction}.",
                'icon'      => 'warning',
                'color'     => 'red',
                'timestamp' => $vio->created_at,
                'link'      => route('guardian.violations.show', $vio->id),
            ]);
        }

        // 3. Dari SPP
        $payments = SppPayment::with('student')
            ->whereIn('student_id', $studentIds)
            ->latest('payment_date')
            ->limit(20)
            ->get();

        foreach ($payments as $pay) {
            $name      = $pay->student?->name ?? '-';
            $stageText = $pay->stage === 'full' ? 'LUNAS (Full)' : "Tahap {$pay->stage}";
            $amount    = 'Rp ' . number_format($pay->amount, 0, ',', '.');
            $statusTxt = $pay->status === 'paid' ? 'telah diterima dan lunas' : 'telah dicatat namun belum lunas';
            $notifications->push([
                'title'     => 'Pembayaran SPP',
                'message'   => "Pembayaran SPP {$stageText} atas nama {$name} sebesar {$amount} {$statusTxt}.",
                'icon'      => 'payments',
                'color'     => 'blue',
                'timestamp' => $pay->created_at,
                'link'      => null,
            ]);
        }

        $notifications = $notifications->sortByDesc('timestamp')->values()->take(10);

        return view('guardian.dashboard', compact(
            'guardian', 'students', 'recentLicenses',
            'totalLicenses', 'approvedCount', 'pendingCount', 'rejectedCount',
            'extTotal', 'extApproved', 'extPending', 'extRejected',
            'activeMassLeave', 'blockedMassLeaveStudents', 'checkedOutMassLeaveStudents',
            'notifications'
        ));
    }

    public function notifications()
    {
        /** @var Guardian $guardian */
        $guardian   = Auth::guard('guardian')->user();
        $studentIds = $guardian->students()->pluck('id');

        $notifications = collect();

        $licenses = StudentLicense::with(['student', 'extensions'])
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->get();

        foreach ($licenses as $lic) {
            $name  = $lic->student?->name ?? '-';
            $start = $lic->start_date?->format('d-m-Y') ?? '-';
            $end   = $lic->end_date?->format('d-m-Y') ?? '-';

            $notifications->push([
                'title'     => 'Pengajuan Izin Pulang',
                'message'   => "Ananda {$name} telah mengajukan izin pulang/keluar. Tanggal: {$start} s.d {$end}. Pengajuan sedang dalam proses persetujuan pengurus.",
                'icon'      => 'edit_note',
                'color'     => 'amber',
                'timestamp' => $lic->created_at,
                'link'      => route('guardian.licenses.show', $lic->id),
            ]);

            if ($lic->status === 'approved' && $lic->approved_at) {
                $notifications->push([
                    'title'     => 'Izin Pulang Disetujui',
                    'message'   => "Ananda {$name} telah mendapat izin pulang. Tanggal: {$start} s.d {$end}. Harap jaga dan awasi kepulangan ananda.",
                    'icon'      => 'check_circle',
                    'color'     => 'green',
                    'timestamp' => $lic->approved_at,
                    'link'      => route('guardian.licenses.show', $lic->id),
                ]);
            }

            if ($lic->status === 'rejected' && $lic->rejected_at) {
                $notifications->push([
                    'title'     => 'Izin Pulang Ditolak',
                    'message'   => "Pengajuan izin pulang Ananda {$name} tidak dapat disetujui saat ini. Silakan hubungi pihak pesantren untuk informasi lebih lanjut.",
                    'icon'      => 'cancel',
                    'color'     => 'red',
                    'timestamp' => $lic->rejected_at,
                    'link'      => route('guardian.licenses.show', $lic->id),
                ]);
            }

            if ($lic->actual_return_date) {
                $returnDate = \Carbon\Carbon::parse($lic->actual_return_date)->format('d-m-Y');
                $status     = $lic->is_late ? 'Terlambat ' . $lic->late_days . ' hari' : 'Tepat waktu';
                $notifications->push([
                    'title'     => 'Santri Kembali ke Pesantren',
                    'message'   => "Ananda {$name} telah kembali ke pesantren. Tanggal kembali: {$returnDate}. Status: {$status}.",
                    'icon'      => 'home',
                    'color'     => 'blue',
                    'timestamp' => $lic->updated_at,
                    'link'      => route('guardian.licenses.show', $lic->id),
                ]);
            }

            foreach ($lic->extensions as $ext) {
                $newDate = $ext->requested_new_end_date instanceof \Carbon\Carbon
                    ? $ext->requested_new_end_date->format('d-m-Y')
                    : \Carbon\Carbon::parse($ext->requested_new_end_date)->format('d-m-Y');

                if ($ext->status === 'approved' && $ext->approved_at) {
                    $notifications->push([
                        'title'     => 'Perpanjangan Izin Disetujui',
                        'message'   => "Perpanjangan izin Ananda {$name} telah disetujui. Tanggal kembali yang baru: {$newDate}. Mohon diperhatikan.",
                        'icon'      => 'event_available',
                        'color'     => 'green',
                        'timestamp' => $ext->approved_at,
                        'link'      => route('guardian.licenses.show', $lic->id),
                    ]);
                }

                if ($ext->status === 'rejected' && $ext->rejected_at) {
                    $notifications->push([
                        'title'     => 'Perpanjangan Izin Ditolak',
                        'message'   => "Perpanjangan izin Ananda {$name} tidak dapat disetujui. Harap santri kembali sesuai jadwal semula.",
                        'icon'      => 'event_busy',
                        'color'     => 'red',
                        'timestamp' => $ext->rejected_at,
                        'link'      => route('guardian.licenses.show', $lic->id),
                    ]);
                }
            }
        }

        $violations = ViolationRecord::with(['student', 'violationType'])
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->get();

        foreach ($violations as $vio) {
            $name     = $vio->student?->name ?? '-';
            $typeName = $vio->violationType?->name ?? '-';
            $sanction = $vio->violationType?->default_sanction ?? '-';
            $notifications->push([
                'title'     => 'Pemberitahuan Pelanggaran',
                'message'   => "Ananda {$name} tercatat melakukan pelanggaran: {$typeName}. Sanksi: {$sanction}.",
                'icon'      => 'warning',
                'color'     => 'red',
                'timestamp' => $vio->created_at,
                'link'      => null,
            ]);
        }

        $payments = SppPayment::with('student')
            ->whereIn('student_id', $studentIds)
            ->latest('payment_date')
            ->get();

        foreach ($payments as $pay) {
            $name      = $pay->student?->name ?? '-';
            $stageText = $pay->stage === 'full' ? 'LUNAS (Full)' : "Tahap {$pay->stage}";
            $amount    = 'Rp ' . number_format($pay->amount, 0, ',', '.');
            $statusTxt = $pay->status === 'paid' ? 'telah diterima dan lunas' : 'telah dicatat namun belum lunas';
            $notifications->push([
                'title'     => 'Pembayaran SPP',
                'message'   => "Pembayaran SPP {$stageText} atas nama {$name} sebesar {$amount} {$statusTxt}.",
                'icon'      => 'payments',
                'color'     => 'blue',
                'timestamp' => $pay->created_at,
                'link'      => null,
            ]);
        }

        $notifications = $notifications->sortByDesc('timestamp')->values();

        return view('guardian.notifications', compact('guardian', 'notifications'));
    }
}
