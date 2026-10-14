@extends('user-management.index')

@push('modals')
<!-- Full screen modal overlay -->
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4 sm:p-6 transition-opacity">
    <!-- Modal Card -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tambah User</h2>
                <p class="text-sm text-gray-500 mt-1">Tambahkan akun pengguna baru ke dalam sistem</p>
            </div>
            <a href="{{ route('user-management.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </a>
        </div>

        <form action="{{ route('user-management.store') }}" method="POST" novalidate class="flex flex-col flex-1 overflow-hidden" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent outline-none transition-all text-sm">
                    </div>

                    <div class="space-y-1.5 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">NPK <span class="text-red-500">*</span></label>
                        <input type="text" name="npk" value="{{ old('npk') }}" required placeholder="Nomor Pokok Karyawan" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent outline-none transition-all text-sm">
                    </div>

                    <div class="space-y-1.5 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Untuk pengiriman info akun" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent outline-none transition-all text-sm">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent outline-none transition-all text-sm">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="drop-up w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent outline-none transition-all text-sm">
                            <option value="">-- Pilih Role --</option>
                            @foreach($dbRoles as $r)
                                <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="drop-up w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent outline-none transition-all text-sm">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="space-y-3 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Hak Akses Langsung (Optional)</label>
                        <p class="text-xs text-gray-500 mb-2">Pilih spesifik aksi apa saja yang boleh dilakukan oleh user ini pada setiap menu.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($permissions->groupBy(fn($p) => explode('_', $p->name)[1] ?? $p->name) as $group => $perms)
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 transition-all hover:border-cyan-200 hover:shadow-sm">
                                    <h4 class="text-sm font-semibold text-gray-800 capitalize">Menu {{ $group }}</h4>
                                    <label class="flex items-center gap-2 cursor-pointer shrink-0 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="permissions[]" value="{{ $group }}" class="rounded text-[#06b6d4] focus:ring-[#06b6d4] border-gray-300 w-4 h-4 cursor-pointer">
                                        <span class="text-xs text-gray-700 font-medium">Beri Akses</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <a href="{{ route('user-management.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" :disabled="isSubmitting" class="bg-[#06b6d4] hover:bg-cyan-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-lg shadow-cyan-500/30 transition-all flex items-center gap-2">
                    <i class="fas fa-save" x-show="!isSubmitting"></i>
                    <i class="fas fa-spinner fa-spin" x-show="isSubmitting" style="display: none;"></i>
                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan User'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
.animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>
@endpush

