@extends(strtolower(auth()->user()->role ?? '') === 'admin' ? 'mentoring.index' : 'user.mentoring.index')

@push('modals')
<!-- Full screen modal overlay -->
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 sm:p-6">
    <!-- Modal Card -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tambah Mentoring</h2>
                <p class="text-sm text-gray-500 mt-1">Tambahkan data mentoring untuk karyawan</p>
            </div>
            <a href="{{ request('redirect_to') ?? (strtolower(auth()->user()->role ?? '') === 'admin' ? route('mentoring.index') : route('user-mentoring.index')) }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </a>
        </div>

        <form action="{{ route('mentoring.store') }}" method="POST" novalidate class="flex flex-col flex-1 overflow-hidden" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
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
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department</label>
                        <input type="text" value="{{ ($selectedEmployee && $selectedEmployee->department) ? $selectedEmployee->department->nama_departemen : '-' }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 border cursor-not-allowed">
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun</label>
                        <input type="text" name="tahun" value="{{ old('tahun', date('Y')) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="cth. 2024">
                    </div>

                    <!-- Periode -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Periode</label>
                        <input type="text" name="periode" value="{{ old('periode', '1') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="cth. 1 atau 2">
                    </div>

                    <!-- Project -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Project</label>
                        <input type="text" name="project" value="{{ old('project') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="Nama Project">
                    </div>
                    
                    <!-- Mentor -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Mentor</label>
                        <input type="text" name="mentor" value="{{ old('mentor') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="Nama Mentor">
                    </div>

                    <!-- Status Mentoring -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Mentoring</label>
                        <select name="status_mentoring" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white">
                            <option value="in_progress" {{ old('status_mentoring') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status_mentoring') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Development Area -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Area Pengembangan (Development)</label>
                        <input type="text" name="development" value="{{ old('development') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white" placeholder="Area kompetensi yang dikembangkan">
                    </div>

                    <!-- Target Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Target Penyelesaian</label>
                        <input type="date" name="target" value="{{ old('target') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white">
                    </div>
                    
                    <!-- Monitoring Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Monitoring</label>
                        <input type="date" name="monitoring" value="{{ old('monitoring') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white">
                    </div>

                    <!-- Final Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Final (Selesai)</label>
                        <input type="date" name="final" value="{{ old('final') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <a href="{{ request('redirect_to') ?? (strtolower(auth()->user()->role ?? '') === 'admin' ? route('mentoring.index') : route('user-mentoring.index')) }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Mentoring</span>
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
