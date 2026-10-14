@extends('layouts.app')
@section('title', 'Assessment Report - ' . $karyawan->name)

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Assessment Report</h1>
        <p class="text-sm text-gray-500 mt-1">Informasi lengkap assessment report karyawan</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('user-assessment-report.index') }}" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 text-gray-700 transition-colors">
            <i class="fas fa-arrow-left text-gray-400"></i> Back
        </a>
    </div>
</div>

<!-- Employee Profile Header -->
@include('components.employee-profile-header', ['employee' => $karyawan])

<!-- Assessment Info Full Width Row -->
<div class="mb-6">
    <!-- Card Assessment Info -->
    <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)]">
        
        <!-- Header & Icon Edit -->
        <div class="flex justify-between items-center border-b border-gray-50 pb-3 mb-4">
            <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-sm"></div> Assessment Info
            </h3>
            
            <!-- Tombol Edit (Arahkan ke modal atau halaman form edit) -->
            @if($activeReport)
                @can('edit_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-assessment-info').classList.remove('hidden');"
               class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5" 
               title="{{ $activeReport ? 'Edit Assessment Info' : 'Isi Assessment Info' }}">
                <i class="fas {{ $activeReport ? 'fa-pen' : 'fa-plus' }}"></i>
            </a>
                @endcan
            @else
                @can('tambah_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-assessment-info').classList.remove('hidden');"
               class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5" 
               title="{{ $activeReport ? 'Edit Assessment Info' : 'Isi Assessment Info' }}">
                <i class="fas {{ $activeReport ? 'fa-pen' : 'fa-plus' }}"></i>
            </a>
                @endcan
            @endif
        </div>

        <!-- Isi Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Tahun -->
            <div class="flex items-center p-3 rounded-xl bg-sky-50 border border-sky-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-white text-sky-500 flex items-center justify-center mr-3 shadow-sm border border-sky-50">
                    <i class="far fa-calendar-alt text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-sky-600/80 tracking-wider uppercase mb-0.5">Tahun</p>
                    <p class="text-sm font-bold text-gray-800">
                        {{ $activeReport ? $activeReport->tahun : '-' }}
                    </p>
                </div>
            </div>

            <!-- Periode -->
            <div class="flex items-center p-3 rounded-xl bg-purple-50 border border-purple-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-white text-purple-500 flex items-center justify-center mr-3 shadow-sm border border-purple-50">
                    <i class="far fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-purple-600/80 tracking-wider uppercase mb-0.5">Periode</p>
                    <p class="text-sm font-bold text-gray-800">
                        {{ $activeReport && $activeReport->periode ? $activeReport->periode : '-' }}
                    </p>
                </div>
            </div>

            <!-- Provider -->
            <div class="flex items-center p-3 rounded-xl bg-emerald-50 border border-emerald-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-white text-emerald-500 flex items-center justify-center mr-3 shadow-sm border border-emerald-50">
                    <i class="far fa-building text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-emerald-600/80 tracking-wider uppercase mb-0.5">Provider / Assessor</p>
                    <p class="text-sm font-bold text-gray-800">
                        {{ $activeReport && $activeReport->provider ? $activeReport->provider : '-' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

@push('modals')
<!-- Modal Edit Assessment Info -->
<div id="modal-assessment-info" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-gray-900 text-lg">Assessment Info</h3>
            <button type="button" onclick="document.getElementById('modal-assessment-info').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ $activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store') }}" method="POST" class="p-6">
            @csrf
            @if($activeReport)
                @method('PUT')
            @endif
            
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ $activeReport->tahun ?? '' }}" 
                           class="w-full rounded-lg border border-gray-200 shadow-sm focus:border-[#0ea5e9] focus:ring focus:ring-[#0ea5e9]/20 text-sm py-2 px-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <input type="text" name="periode" value="{{ $activeReport->periode ?? '' }}" 
                           class="w-full rounded-lg border border-gray-200 shadow-sm focus:border-[#0ea5e9] focus:ring focus:ring-[#0ea5e9]/20 text-sm py-2 px-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provider / Assessor</label>
                    <input type="text" name="provider" value="{{ $activeReport->provider ?? '' }}" 
                           class="w-full rounded-lg border border-gray-200 shadow-sm focus:border-[#0ea5e9] focus:ring focus:ring-[#0ea5e9]/20 text-sm py-2 px-3 transition-colors">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-assessment-info').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] border border-transparent rounded-lg hover:bg-[#0284c7] transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal KB -->
<div id="modal-kb" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Kemampuan Berpikir</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Input skor skala 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-kb').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="{{ $activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store') }}" method="POST" class="px-4 py-3">
            @csrf
            @if($activeReport) @method('PUT') @endif
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Kecerdasan Umum</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_kecerdasan_umum" value="{{ $activeReport->kb_kecerdasan_umum ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Fleksibilitas Berpikir</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_fleksibilitas_berfikir" value="{{ $activeReport->kb_fleksibilitas_berfikir ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Kemampuan Konseptual</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_kemampuan_konseptual" value="{{ $activeReport->kb_kemampuan_konseptual ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Daya Analisa Sintesa</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_analisa_sintesa" value="{{ $activeReport->kb_analisa_sintesa ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-kb').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal SK -->
<div id="modal-sk" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Sikap Kerja</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Input skor skala 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-sk').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="{{ $activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store') }}" method="POST" class="px-4 py-3">
            @csrf
            @if($activeReport) @method('PUT') @endif
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Sistematika Kerja</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_sistematika_kerja" value="{{ $activeReport->sk_sistematika_kerja ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Daya Tahan Kerja</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_daya_tahan" value="{{ $activeReport->sk_daya_tahan ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Fleksibilitas Kerja</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_fleksibilitas" value="{{ $activeReport->sk_fleksibilitas ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Perhatian terhadap Detail</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_perhatian_detail" value="{{ $activeReport->sk_perhatian_detail ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-sk').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal KP -->
<div id="modal-kp" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Kepribadian</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Input skor skala 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-kp').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="{{ $activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store') }}" method="POST" class="px-4 py-3">
            @csrf
            @if($activeReport) @method('PUT') @endif
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Stabilitas Emosi</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_stabilitas_emosi" value="{{ $activeReport->kp_stabilitas_emosi ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Kepemimpinan</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_kepemimpinan" value="{{ $activeReport->kp_kepemimpinan ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Penyesuaian Diri</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_penyesuaian_diri" value="{{ $activeReport->kp_penyesuaian_diri ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Integritas</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_integritas" value="{{ $activeReport->kp_integritas ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Kepercayaan Diri</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_kepercayaan_diri" value="{{ $activeReport->kp_kepercayaan_diri ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Motivasi Kerja</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_motivasi_kerja" value="{{ $activeReport->kp_motivasi_kerja ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-kp').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal MS -->
<div id="modal-ms" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Managerial Skill</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Input skor skala 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-ms').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="{{ $activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store') }}" method="POST" class="px-4 py-3">
            @csrf
            @if($activeReport) @method('PUT') @endif
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Integrity & Ethics</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_integrity_ethics" value="{{ $activeReport->ms_integrity_ethics ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Fostering Team Work</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_fostering_team_work" value="{{ $activeReport->ms_fostering_team_work ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Excellence</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_excellence" value="{{ $activeReport->ms_excellence ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Empowerment</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_empowerment" value="{{ $activeReport->ms_empowerment ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Compassion</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_compassion" value="{{ $activeReport->ms_compassion ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Managing Changes</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_managing_changes" value="{{ $activeReport->ms_managing_changes ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Humility</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_humility" value="{{ $activeReport->ms_humility ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Planning & Controlling</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_planning_controlling" value="{{ $activeReport->ms_planning_controlling ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="col-span-2 flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Analysis & Decision Making</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_analysis_decision" value="{{ $activeReport->ms_analysis_decision ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-ms').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

<!-- Assessment Charts Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    
    <!-- Kemampuan Berpikir -->
    <div class="bg-[#eef8f1] rounded-2xl p-6 relative border border-[#d1ebd7] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-gray-900 text-lg">Kemampuan Berpikir</h3>
            @if($activeReport)
                @can('edit_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-kb').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @else
                @can('tambah_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-kb').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @endif
        </div>
        <div class="flex justify-center mb-6 h-48 relative z-10">
            <canvas id="chartKB"></canvas>
        </div>
        <div class="space-y-3 z-20 relative">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Kecerdasan Umum</span>
                <span class="font-bold text-[#147a42]">{{ $activeReport ? number_format((float)$activeReport->kb_kecerdasan_umum, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Fleksibilitas Berpikir</span>
                <span class="font-bold text-[#147a42]">{{ $activeReport ? number_format((float)$activeReport->kb_fleksibilitas_berfikir, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Kemampuan Konseptual</span>
                <span class="font-bold text-[#147a42]">{{ $activeReport ? number_format((float)$activeReport->kb_kemampuan_konseptual, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Daya Analisa Sintesa</span>
                <span class="font-bold text-[#147a42]">{{ $activeReport ? number_format((float)$activeReport->kb_analisa_sintesa, 1) : '0.0' }} / 5</span>
            </div>
        </div>
    </div>

    <!-- Sikap Kerja -->
    <div class="bg-[#fcf7ec] rounded-2xl p-6 relative border border-[#f0e4cc] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-gray-900 text-lg">Sikap Kerja</h3>
            @if($activeReport)
                @can('edit_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-sk').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @else
                @can('tambah_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-sk').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @endif
        </div>
        <div class="flex justify-center mb-6 h-48 relative z-10">
            <canvas id="chartSK"></canvas>
        </div>
        <div class="space-y-3 z-20 relative">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Sistematika Kerja</span>
                <span class="font-bold text-[#b58514]">{{ $activeReport ? number_format((float)$activeReport->sk_sistematika_kerja, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Daya Tahan Kerja</span>
                <span class="font-bold text-[#b58514]">{{ $activeReport ? number_format((float)$activeReport->sk_daya_tahan, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Fleksibilitas Kerja</span>
                <span class="font-bold text-[#b58514]">{{ $activeReport ? number_format((float)$activeReport->sk_fleksibilitas, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Perhatian terhadap Detail</span>
                <span class="font-bold text-[#b58514]">{{ $activeReport ? number_format((float)$activeReport->sk_perhatian_detail, 1) : '0.0' }} / 5</span>
            </div>
        </div>
    </div>

    <!-- Kepribadian -->
    <div class="bg-[#eff1f9] rounded-2xl p-6 relative border border-[#dce0f2] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-gray-900 text-lg">Kepribadian</h3>
            @if($activeReport)
                @can('edit_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-kp').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @else
                @can('tambah_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-kp').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @endif
        </div>
        <div class="flex justify-center mb-16 h-48 relative z-10">
            <canvas id="chartKP"></canvas>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3 z-20 relative">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-xs sm:text-sm">Stabilitas Emosi</span>
                <span class="font-bold text-[#35459e]">{{ $activeReport ? number_format((float)$activeReport->kp_stabilitas_emosi, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-xs sm:text-sm">Kepemimpinan</span>
                <span class="font-bold text-[#35459e]">{{ $activeReport ? number_format((float)$activeReport->kp_kepemimpinan, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-xs sm:text-sm">Penyesuaian Diri</span>
                <span class="font-bold text-[#35459e]">{{ $activeReport ? number_format((float)$activeReport->kp_penyesuaian_diri, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-xs sm:text-sm">Integritas</span>
                <span class="font-bold text-[#35459e]">{{ $activeReport ? number_format((float)$activeReport->kp_integritas, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-xs sm:text-sm">Kepercayaan Diri</span>
                <span class="font-bold text-[#35459e]">{{ $activeReport ? number_format((float)$activeReport->kp_kepercayaan_diri, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-xs sm:text-sm">Motivasi Kerja</span>
                <span class="font-bold text-[#35459e]">{{ $activeReport ? number_format((float)$activeReport->kp_motivasi_kerja, 1) : '0.0' }} / 5</span>
            </div>
        </div>
    </div>

    <!-- Managerial Skill -->
    <div class="bg-[#eef8f8] rounded-2xl p-6 relative border border-[#d1eeee] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-gray-900 text-lg">Managerial Skill</h3>
            @if($activeReport)
                @can('edit_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-ms').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @else
                @can('tambah_assessment')
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-ms').classList.remove('hidden');" class="text-gray-500 hover:text-gray-700 text-xs font-medium flex items-center gap-1 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow">
                <i class="far fa-edit"></i> {{ $activeReport ? 'Edit' : 'Isi Data' }}
            </a>
                @endcan
            @endif
        </div>
        <div class="flex justify-center mb-6 h-48 relative z-10">
            <canvas id="chartMS"></canvas>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3 z-20 relative">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Integrity & Ethics</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_integrity_ethics, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Fostering Team Work</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_fostering_team_work, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Excellence</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_excellence, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Empowerment</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_empowerment, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Compassion</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_compassion, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Managing Changes</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_managing_changes, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Humility</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_humility, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 text-[11px] sm:text-xs">Planning & Controlling</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_planning_controlling, 1) : '0.0' }} / 5</span>
            </div>
            <div class="flex justify-between items-center text-sm col-span-2 sm:col-span-1">
                <span class="text-gray-600 text-[11px] sm:text-xs">Analysis & Decision Making</span>
                <span class="font-bold text-[#0c7c85]">{{ $activeReport ? number_format((float)$activeReport->ms_analysis_decision, 1) : '0.0' }} / 5</span>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0, 0, 0, 0.1)' },
                    grid: { 
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    pointLabels: { 
                        display: true,
                        font: { size: 10, weight: '500' },
                        color: '#4b5563'
                    },
                    ticks: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.4)',
                        backdropColor: 'transparent',
                        font: { size: 10 },
                        min: 0,
                        max: 5,
                        stepSize: 1,
                        count: 6 // 0, 1, 2, 3, 4, 5 -> 5 rings
                    },
                    suggestedMin: 0,
                    suggestedMax: 5,
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (items) => items[0].label,
                        label: (item) => 'Nilai: ' + item.raw
                    }
                }
            }
        };

        // KB Chart
        new Chart(document.getElementById('chartKB').getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['Kecerdasan Umum', 'Fleksibilitas Berpikir', 'Kemampuan Konseptual', 'Analisa Sintesa'],
                datasets: [{
                    data: [
                        {{ $activeReport?->kb_kecerdasan_umum ?? 0 }},
                        {{ $activeReport?->kb_fleksibilitas_berfikir ?? 0 }},
                        {{ $activeReport?->kb_kemampuan_konseptual ?? 0 }},
                        {{ $activeReport?->kb_analisa_sintesa ?? 0 }}
                    ],
                    backgroundColor: 'rgba(20, 122, 66, 0.2)',
                    borderColor: 'rgba(20, 122, 66, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(20, 122, 66, 1)',
                    pointRadius: {{ $activeReport ? 3 : 0 }}
                }]
            },
            options: commonOptions
        });

        // SK Chart
        new Chart(document.getElementById('chartSK').getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['Sistematika Kerja', 'Daya Tahan', 'Fleksibilitas Kerja', 'Perhatian thd Detail'],
                datasets: [{
                    data: [
                        {{ $activeReport?->sk_sistematika_kerja ?? 0 }},
                        {{ $activeReport?->sk_daya_tahan ?? 0 }},
                        {{ $activeReport?->sk_fleksibilitas ?? 0 }},
                        {{ $activeReport?->sk_perhatian_detail ?? 0 }}
                    ],
                    backgroundColor: 'rgba(181, 133, 20, 0.2)',
                    borderColor: 'rgba(181, 133, 20, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(181, 133, 20, 1)',
                    pointRadius: {{ $activeReport ? 3 : 0 }}
                }]
            },
            options: commonOptions
        });

        // KP Chart
        new Chart(document.getElementById('chartKP').getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['Stabilitas Emosi', 'Penyesuaian Diri', 'Kepercayaan Diri', 'Kepemimpinan', 'Integritas', 'Motivasi Kerja'],
                datasets: [{
                    data: [
                        {{ $activeReport?->kp_stabilitas_emosi ?? 0 }},
                        {{ $activeReport?->kp_penyesuaian_diri ?? 0 }},
                        {{ $activeReport?->kp_kepercayaan_diri ?? 0 }},
                        {{ $activeReport?->kp_kepemimpinan ?? 0 }},
                        {{ $activeReport?->kp_integritas ?? 0 }},
                        {{ $activeReport?->kp_motivasi_kerja ?? 0 }}
                    ],
                    backgroundColor: 'rgba(53, 69, 158, 0.2)',
                    borderColor: 'rgba(53, 69, 158, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(53, 69, 158, 1)',
                    pointRadius: {{ $activeReport ? 3 : 0 }}
                }]
            },
            options: commonOptions
        });

        // MS Chart
        new Chart(document.getElementById('chartMS').getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['Integrity & Ethics', 'Excellence', 'Compassion', 'Humility', 'Analysis & Decision', 'Team Work', 'Empowerment', 'Managing Changes', 'Planning & Controlling'],
                datasets: [{
                    data: [
                        {{ $activeReport?->ms_integrity_ethics ?? 0 }},
                        {{ $activeReport?->ms_excellence ?? 0 }},
                        {{ $activeReport?->ms_compassion ?? 0 }},
                        {{ $activeReport?->ms_humility ?? 0 }},
                        {{ $activeReport?->ms_analysis_decision ?? 0 }},
                        {{ $activeReport?->ms_fostering_team_work ?? 0 }},
                        {{ $activeReport?->ms_empowerment ?? 0 }},
                        {{ $activeReport?->ms_managing_changes ?? 0 }},
                        {{ $activeReport?->ms_planning_controlling ?? 0 }}
                    ],
                    backgroundColor: 'rgba(12, 124, 133, 0.2)',
                    borderColor: 'rgba(12, 124, 133, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(12, 124, 133, 1)',
                    pointRadius: {{ $activeReport ? 3 : 0 }}
                }]
            },
            options: commonOptions
        });
    })();
</script>

<style>
/* Custom Scrollbar for Modals */
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
@endsection





