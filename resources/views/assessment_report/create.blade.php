@extends(
    (request('employee_id') || isset($employee)) && isset($employee)
        ? (strtolower(auth()->user()->role ?? '') === 'admin' ? 'assessment_report.employee' : 'user.assessment_report.employee')
        : (strtolower(auth()->user()->role ?? '') === 'admin' ? 'assessment_report.index' : 'user.assessment_report.index')
)

@push('modals')
@php
    $targetEmpId = request('employee_id') ?? ($employee->id ?? null);
    $fallbackUrl = $targetEmpId
        ? (strtolower(auth()->user()->role ?? '') === 'admin' ? route('assessment-report.show-employee', $targetEmpId) : route('user-assessment-report.show-employee', $targetEmpId))
        : (strtolower(auth()->user()->role ?? '') === 'admin' ? route('assessment-report.index') : route('user-assessment-report.index'));
    $closeUrl = request('redirect_to') ?: $fallbackUrl;
@endphp
<!-- Full screen modal overlay -->
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <a href="{{ $closeUrl }}" class="fixed inset-0 transition-opacity" aria-hidden="true"></a>

    <!-- Modal Card -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up relative z-10">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tambah Assessment Report</h2>
                <p class="text-sm text-gray-500 mt-1">Tambahkan data assessment untuk karyawan</p>
            </div>
            <a href="{{ $closeUrl }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </a>
        </div>

        <form action="{{ route('assessment-report.store') }}" method="POST" novalidate class="flex flex-col flex-1 overflow-hidden">
        @csrf
        <!-- Body -->
        <div class="p-6 overflow-y-auto flex-1 bg-white custom-scrollbar">
    @if ($errors->any())
        <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6 border border-red-200">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $selectedEmployeeId = old('id_karyawan', request('employee_id'));
                $selectedEmployee = $selectedEmployeeId ? $allEmployees->firstWhere('id', $selectedEmployeeId) : null;
            @endphp

            <input type="hidden" name="id_karyawan" value="{{ $selectedEmployeeId }}">
            <input type="hidden" name="redirect_to" value="{{ $closeUrl }}">

            <!-- Nama Karyawan -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Karyawan</label>
                <input type="text" value="{{ $selectedEmployee ? $selectedEmployee->nama : '-' }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 border cursor-not-allowed">
            </div>

            <!-- NPK -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NPK</label>
                <input type="text" value="{{ $selectedEmployee ? $selectedEmployee->npk : '-' }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 border cursor-not-allowed">
            </div>

            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Position</label>
                <input type="text" value="{{ $selectedEmployee ? $selectedEmployee->position?->nama_jabatan : '-' }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 border cursor-not-allowed">
            </div>

            <!-- Departemen -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department</label>
                <input type="text" value="{{ ($selectedEmployee && $selectedEmployee->department) ? $selectedEmployee->department->nama_departemen : '-' }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 border cursor-not-allowed">
            </div>

            <!-- Tahun -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun *</label>
                <input type="number" name="tahun" value="{{ old('tahun') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="cth. 2024">
            </div>

            <!-- Periode -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Periode *</label>
                <input type="text" name="periode" value="{{ old('periode') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="cth. Periode Berapa">
            </div>

            <!-- Provider -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Provider/Assessor *</label>
                <input type="text" name="provider" value="{{ old('provider') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="cth. Nama Provider / Assessor">
            </div>
        </div>


        <div class="my-8 border-t border-gray-200"></div>

<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-900">Input Nilai Assessment</h3>
    <p class="text-sm text-gray-500">Masukkan nilai dengan skala 1.0 sampai 5.0 (gunakan titik untuk desimal, contoh: 4.5)</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Kemampuan Berpikir -->
    <div class="bg-green-50/80 border border-green-100 p-5 rounded-2xl shadow-sm">
        <h4 class="font-bold text-green-800 mb-4 flex items-center gap-3 pb-3 border-b border-green-200/50">
            <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center shadow-sm">
                <i class="fas fa-brain"></i>
            </div>
            Kemampuan Berpikir (KB)
        </h4>
        <div class="space-y-3">
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-green-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-green-700 transition-colors">Kecerdasan Umum</label>
                <input type="number" step="0.1" min="1" max="5" name="kb_kecerdasan_umum" value="{{ old('kb_kecerdasan_umum') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-green-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-green-700 transition-colors">Fleksibilitas Berpikir</label>
                <input type="number" step="0.1" min="1" max="5" name="kb_fleksibilitas_berfikir" value="{{ old('kb_fleksibilitas_berfikir') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-green-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-green-700 transition-colors">Kemampuan Konseptual</label>
                <input type="number" step="0.1" min="1" max="5" name="kb_kemampuan_konseptual" value="{{ old('kb_kemampuan_konseptual') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-green-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-green-700 transition-colors">Analisa & Sintesa</label>
                <input type="number" step="0.1" min="1" max="5" name="kb_analisa_sintesa" value="{{ old('kb_analisa_sintesa') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
        </div>
    </div>

    <!-- Sikap Kerja -->
    <div class="bg-amber-50/80 border border-amber-100 p-5 rounded-2xl shadow-sm">
        <h4 class="font-bold text-amber-800 mb-4 flex items-center gap-3 pb-3 border-b border-amber-200/50">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shadow-sm">
                <i class="fas fa-briefcase"></i>
            </div>
            Sikap Kerja (SK)
        </h4>
        <div class="space-y-3">
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-amber-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-amber-700 transition-colors">Sistematika Kerja</label>
                <input type="number" step="0.1" min="1" max="5" name="sk_sistematika_kerja" value="{{ old('sk_sistematika_kerja') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-amber-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-amber-700 transition-colors">Daya Tahan</label>
                <input type="number" step="0.1" min="1" max="5" name="sk_daya_tahan" value="{{ old('sk_daya_tahan') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-amber-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-amber-700 transition-colors">Fleksibilitas Kerja</label>
                <input type="number" step="0.1" min="1" max="5" name="sk_fleksibilitas" value="{{ old('sk_fleksibilitas') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-amber-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-amber-700 transition-colors">Perhatian terhadap Detail</label>
                <input type="number" step="0.1" min="1" max="5" name="sk_perhatian_detail" value="{{ old('sk_perhatian_detail') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
        </div>
    </div>

    <!-- Kepribadian -->
    <div class="bg-indigo-50/80 border border-indigo-100 p-5 rounded-2xl shadow-sm">
        <h4 class="font-bold text-indigo-800 mb-4 flex items-center gap-3 pb-3 border-b border-indigo-200/50">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm">
                <i class="fas fa-user-shield"></i>
            </div>
            Kepribadian (KP)
        </h4>
        <div class="space-y-3">
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-indigo-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-indigo-700 transition-colors">Stabilitas Emosi</label>
                <input type="number" step="0.1" min="1" max="5" name="kp_stabilitas_emosi" value="{{ old('kp_stabilitas_emosi') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-indigo-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-indigo-700 transition-colors">Penyesuaian Diri</label>
                <input type="number" step="0.1" min="1" max="5" name="kp_penyesuaian_diri" value="{{ old('kp_penyesuaian_diri') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-indigo-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-indigo-700 transition-colors">Kepercayaan Diri</label>
                <input type="number" step="0.1" min="1" max="5" name="kp_kepercayaan_diri" value="{{ old('kp_kepercayaan_diri') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-indigo-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-indigo-700 transition-colors">Kepemimpinan</label>
                <input type="number" step="0.1" min="1" max="5" name="kp_kepemimpinan" value="{{ old('kp_kepemimpinan') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-indigo-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-indigo-700 transition-colors">Integritas</label>
                <input type="number" step="0.1" min="1" max="5" name="kp_integritas" value="{{ old('kp_integritas') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-indigo-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-indigo-700 transition-colors">Motivasi Kerja</label>
                <input type="number" step="0.1" min="1" max="5" name="kp_motivasi_kerja" value="{{ old('kp_motivasi_kerja') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
        </div>
    </div>

    <!-- Managerial Skill -->
    <div class="bg-cyan-50/80 border border-cyan-100 p-5 rounded-2xl shadow-sm">
        <h4 class="font-bold text-cyan-800 mb-4 flex items-center gap-3 pb-3 border-b border-cyan-200/50">
            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center shadow-sm">
                <i class="fas fa-users-cog"></i>
            </div>
            Managerial Skill (MS)
        </h4>
        <div class="space-y-3">
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Integrity & Ethics</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_integrity_ethics" value="{{ old('ms_integrity_ethics') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Excellence</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_excellence" value="{{ old('ms_excellence') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Compassion</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_compassion" value="{{ old('ms_compassion') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Humility</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_humility" value="{{ old('ms_humility') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Analysis & Decision Making</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_analysis_decision" value="{{ old('ms_analysis_decision') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Fostering Team Work</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_fostering_team_work" value="{{ old('ms_fostering_team_work') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Empowerment</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_empowerment" value="{{ old('ms_empowerment') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Managing Changes</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_managing_changes" value="{{ old('ms_managing_changes') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
            <div class="flex justify-between items-center gap-3 bg-white p-2.5 rounded-xl border border-cyan-50 shadow-sm hover:shadow-md transition-shadow group">
                <label class="text-sm font-semibold text-gray-600 flex-1 group-hover:text-cyan-700 transition-colors">Planning & Controlling</label>
                <input type="number" step="0.1" min="1" max="5" name="ms_planning_controlling" value="{{ old('ms_planning_controlling') }}" required class="w-20 rounded-lg border-gray-200 border bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 text-sm py-1.5 px-2 text-center transition-all font-medium text-gray-700">
            </div>
        </div>
    </div>

</div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
            <a href="{{ $closeUrl }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Data
            </button>
        </div>
    </form>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
@endpush






