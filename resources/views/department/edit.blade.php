@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Departemen</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah nama departemen</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('department.index') }}" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2">
            <i class="fas fa-arrow-left text-gray-400"></i> Kembali
        </a>
    </div>
</div>

<div class="card-3d bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl mx-auto">
    <div class="p-6">
        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6 border border-red-200">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('department.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Departemen <span class="text-red-500">*</span></label>
                <input type="text" name="nama_departemen" value="{{ old('nama_departemen', $department->nama_departemen) }}" required class="w-full rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-[#0ea5e9] focus:ring-2 focus:ring-[#0ea5e9]/20 text-sm py-2.5 px-4 transition-all" placeholder="cth. Human Resources">
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('department.index') }}" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-md shadow-sky-500/30 px-6 py-2.5 bg-[#0ea5e9] text-sm font-medium text-white hover:bg-[#0284c7] focus:outline-none transition-all items-center gap-2">
                    <i class="fas fa-save"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

