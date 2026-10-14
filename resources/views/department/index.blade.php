@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Department</h1>
        <p class="text-sm text-gray-500 mt-1">Manage department data in the company</p>
    </div>
    <div class="flex gap-3">
        <button onclick="document.getElementById('addDeptModal').classList.remove('hidden')" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Department
        </button>
    </div>
</div>

@if (session('success'))
<div class="bg-green-50 text-green-600 p-4 rounded-lg mb-6 border border-green-200">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200">
    {{ session('error') }}
</div>
@endif

<div class="card-3d bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center w-16">No</th>
                    <th class="px-5 py-4 font-semibold tracking-wider">Nama Departemen</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Jumlah Karyawan</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @forelse($departments as $dept)
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
                    <td class="px-5 py-4 text-center text-gray-500">{{ $loop->iteration + $departments->firstItem() - 1 }}</td>
                    <td class="px-5 py-4 font-medium text-gray-800">{{ $dept->nama_departemen }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $dept->karyawan()->count() }} Karyawan
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="document.getElementById('editDeptModal-{{ $dept->id }}').classList.remove('hidden')" class="text-gray-400 hover:text-amber-600 p-1.5 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                <i class="fas fa-edit w-4"></i>
                            </button>
                            <form action="{{ route('department.destroy', $dept->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus departemen ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="fas fa-trash-alt w-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">Belum ada data departemen</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan klik tombol Tambah Departemen</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-5 border-t border-gray-100 bg-white flex justify-between items-center">
        @if(method_exists($departments, 'links'))
            <div class="w-full">
                {{ $departments->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah Departemen -->
<div id="addDeptModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="document.getElementById('addDeptModal').classList.add('hidden')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-gray-100 my-8">
            <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                        Tambah Departemen
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Masukkan nama departemen baru.</p>
                </div>
                <button type="button" onclick="document.getElementById('addDeptModal').classList.add('hidden')" class="bg-gray-50 p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition-all">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('department.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-5 pb-6 sm:p-8 sm:pt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Departemen <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_departemen" value="{{ old('nama_departemen') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border" placeholder="cth. Human Resources">
                        @error('nama_departemen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 sm:px-8">
                    <button type="button" onclick="document.getElementById('addDeptModal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md shadow-sky-500/30 px-6 py-2.5 bg-[#0ea5e9] text-sm font-medium text-white hover:bg-[#0284c7] focus:outline-none transition-all sm:w-auto flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($departments as $dept)
<!-- Modal Edit Departemen -->
<div id="editDeptModal-{{ $dept->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="document.getElementById('editDeptModal-{{ $dept->id }}').classList.add('hidden')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-gray-100 my-8">
            <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Edit Departemen
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Ubah nama departemen.</p>
                </div>
                <button type="button" onclick="document.getElementById('editDeptModal-{{ $dept->id }}').classList.add('hidden')" class="bg-gray-50 p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition-all">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('department.update', $dept->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $dept->id }}">
                <div class="bg-white px-6 pt-5 pb-6 sm:p-8 sm:pt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Departemen <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_departemen" value="{{ old('nama_departemen', $dept->nama_departemen) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border" placeholder="cth. Human Resources">
                        @if(old('_method') == 'PUT' && old('id') == $dept->id)
                            @error('nama_departemen')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 sm:px-8">
                    <button type="button" onclick="document.getElementById('editDeptModal-{{ $dept->id }}').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md shadow-sky-500/30 px-6 py-2.5 bg-[#0ea5e9] text-sm font-medium text-white hover:bg-[#0284c7] focus:outline-none transition-all sm:w-auto flex items-center gap-2">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endpush

@push('scripts')
<script>
    function checkDeptModals() {
        @if ($errors->any())
            @if (!old('_method'))
                document.getElementById('addDeptModal')?.classList.remove('hidden');
            @elseif (old('_method') == 'PUT')
                @php $oldId = old('id') ?? request()->route('id'); @endphp
                @if($oldId)
                    document.getElementById('editDeptModal-{{ $oldId }}')?.classList.remove('hidden');
                @endif
            @endif
        @endif
    }
    
    document.addEventListener('DOMContentLoaded', checkDeptModals);
    document.addEventListener('htmx:afterSwap', checkDeptModals);
</script>
@endpush

