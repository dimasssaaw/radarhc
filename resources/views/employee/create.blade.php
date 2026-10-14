@extends('employee.index')

@push('modals')
<!-- Full screen modal overlay -->
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 sm:p-6">
    <!-- Modal Card -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tambah Karyawan</h2>
                <p class="text-sm text-gray-500 mt-1">Masukkan informasi detail karyawan baru ke dalam sistem.</p>
            </div>
            <a href="{{ route('employee.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </a>
        </div>

        <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data" novalidate class="flex flex-col flex-1 overflow-hidden">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <!-- NPK -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">NPK <span class="text-red-500">*</span></label>
                        <input type="text" name="npk" value="{{ old('npk') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('npk') border-red-500 @enderror" placeholder="cth. 10231">
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('nama') border-red-500 @enderror" placeholder="cth. Ade Kurniawan">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('tanggal_lahir') border-red-500 @enderror">
                    </div>

                    <!-- Lama Bekerja -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai Bekerja <span class="text-red-500">*</span></label>
                        <input type="date" name="lama_bekerja" value="{{ old('lama_bekerja') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('lama_bekerja') border-red-500 @enderror">
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Posisi / Jabatan <span class="text-red-500">*</span></label>
                        <select name="position_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('position_id') border-red-500 @enderror">
                            <option value="">Pilih Jabatan</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Readiness -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Kaderisasi <span class="text-red-500">*</span></label>
                        <select name="readiness" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('readiness') border-red-500 @enderror">
                            <option value="">Pilih Status</option>
                            <option value="R1 ready" {{ old('readiness') == 'R1 ready' ? 'selected' : '' }}>R1 Ready</option>
                            <option value="R2 ready" {{ old('readiness') == 'R2 ready' ? 'selected' : '' }}>R2 Ready</option>
                            <option value="R3 ready" {{ old('readiness') == 'R3 ready' ? 'selected' : '' }}>R3 Ready</option>
                            <option value="R4 ready" {{ old('readiness') == 'R4 ready' ? 'selected' : '' }}>R4 Ready</option>
                            <option value="R5 ready" {{ old('readiness') == 'R5 ready' ? 'selected' : '' }}>R5 Ready</option>
                            <option value="not_ready" {{ old('readiness') == 'not_ready' ? 'selected' : '' }}>Not Ready</option>
                        </select>
                    </div>

                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Unggah Foto</label>
                        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-[#0ea5e9] focus:ring-2 focus:ring-[#0ea5e9]/20 text-sm py-1.5 px-4 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#0ea5e9]/10 file:text-[#0ea5e9] hover:file:bg-[#0ea5e9]/20 @error('foto') border-red-500 @enderror">
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pendidikan <span class="text-red-500">*</span></label>
                        <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('pendidikan') border-red-500 @enderror" placeholder="cth. S1 Sistem Informasi">
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kontak (No HP) <span class="text-red-500">*</span></label>
                        <input type="text" name="kontak" value="{{ old('kontak') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('kontak') border-red-500 @enderror" placeholder="cth. 08123456789">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('email') border-red-500 @enderror" placeholder="cth. email@company.com">
                    </div>

                    <!-- Departemen -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Departemen <span class="text-red-500">*</span></label>
                        <select name="department_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('department_id') border-red-500 @enderror">
                            <option value="">Pilih Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->nama_departemen }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grade -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Grade <span class="text-red-500">*</span></label>
                        <input type="text" name="grade" value="{{ old('grade') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('grade') border-red-500 @enderror" placeholder="cth. A1">
                    </div>

                    <!-- IQ -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Skor IQ <span class="text-red-500">*</span></label>
                        <input type="number" name="iq" value="{{ old('iq') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('iq') border-red-500 @enderror" placeholder="cth. 120">
                    </div>

                    <!-- DISC -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">DISC <span class="text-red-500">*</span></label>
                        <input type="text" name="disc" value="{{ old('disc') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('disc') border-red-500 @enderror" placeholder="cth. D/I/S/C">
                    </div>

                    <!-- HAV -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">HAV <span class="text-red-500">*</span></label>
                        <select name="hav" required class="drop-up w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('hav') border-red-500 @enderror">
                            <option value="">Pilih HAV</option>
                            <option value="star" {{ old('hav') == 'star' ? 'selected' : '' }}>Star</option>
                            <option value="high potential" {{ old('hav') == 'high potential' ? 'selected' : '' }}>High potential</option>
                            <option value="potential" {{ old('hav') == 'potential' ? 'selected' : '' }}>Potential</option>
                            <option value="excellent career person" {{ old('hav') == 'excellent career person' ? 'selected' : '' }}>Excellent career person</option>
                            <option value="career person" {{ old('hav') == 'career person' ? 'selected' : '' }}>Career person</option>
                            <option value="problem employee" {{ old('hav') == 'problem employee' ? 'selected' : '' }}>Problem employee</option>
                            <option value="deadwood" {{ old('hav') == 'deadwood' ? 'selected' : '' }}>Deadwood</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <a href="{{ route('employee.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Data</span>
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
