

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="<?php echo e(route('user-certification.index')); ?>" class="hover:text-[#06b6d4] transition-colors">Certification Data</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-900 font-medium">Employee Certification Details</span>
    </div>
    <div class="flex gap-3">
        <a href="<?php echo e(route('user-certification.index')); ?>" class="bg-white shadow-[0_2px_4px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_6px_rgba(0,0,0,0.05),0_2px_4px_rgba(0,0,0,0.1)] border border-gray-100 rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-gray-700">
            <i class="fas fa-arrow-left text-gray-400"></i> Back
        </a>
    </div>
</div>

<!-- Employee Header Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center gap-6">
        <?php if($employee->foto): ?>
            <img src="<?php echo e($employee->foto); ?>" alt="Avatar" class="w-20 h-20 rounded-full object-cover shadow-sm">
        <?php else: ?>
            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($employee->nama)); ?>&background=115e59&color=fff&size=100" alt="Avatar" class="w-20 h-20 rounded-full object-cover shadow-sm">
        <?php endif; ?>
        
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1"><?php echo e($employee->nama); ?></h1>
            <p class="text-gray-500 text-sm mb-3"><?php echo e($employee->position?->nama_jabatan); ?> <?php echo e($employee->department ? '- ' . $employee->department->nama_departemen : ''); ?></p>
            
            <div class="flex gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                    <span class="text-indigo-900"><?php echo e($certifications->count()); ?></span> Certificates
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                    <span class="text-green-900"><?php echo e($certifications->where('status_sertifikat', 'aktif')->count()); ?></span> Active
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Certifications Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
    <!-- Header/Actions -->
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
        <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" id="searchInput" placeholder="Search certificate..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent hover:shadow-sm transition-all duration-200">
        </div>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_certification')): ?>
        <button type="button" onclick="openModal('addModal')" class="bg-[#06b6d4] hover:bg-[#0891b2] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Certificate
        </button>
        <?php endif; ?>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="certTable">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 font-semibold tracking-wider">Sertifikasi</th>
                    <th class="px-6 py-4 font-semibold tracking-wider">Penerbit</th>
                    <th class="px-6 py-4 font-semibold tracking-wider">Terbit</th>
                    <th class="px-6 py-4 font-semibold tracking-wider">Kedaluwarsa</th>
                    <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th class="px-6 py-4 font-semibold tracking-wider text-center">File</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit_certification', 'hapus_certification'])): ?>
                    <th class="px-6 py-4 font-semibold tracking-wider text-center">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100 relative">
                <?php $__empty_1 = true; $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50/80 transition-all duration-200 hover:shadow-md hover:-translate-y-px hover:z-10 relative bg-white">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 cert-name"><?php echo e($cert->nama_sertifikasi); ?></div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 cert-publisher"><?php echo e($cert->penerbit ?? '-'); ?></td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php echo e($cert->terbit ? \Carbon\Carbon::parse($cert->terbit)->translatedFormat('M Y') : '-'); ?>

                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php echo e($cert->kadaluarsa ? \Carbon\Carbon::parse($cert->kadaluarsa)->translatedFormat('M Y') : '-'); ?>

                    </td>
                    <td class="px-6 py-4">
                        <?php if($cert->status_sertifikat === 'aktif'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200 hover:shadow-sm transition-all">
                                Aktif
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200 hover:shadow-sm transition-all">
                                Nonaktif
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if($cert->lampiran_sertifikasi): ?>
                            <a href="<?php echo e($cert->lampiran_sertifikasi); ?>" target="_blank" class="text-gray-400 hover:text-blue-500 hover:-translate-y-1 hover:drop-shadow-md inline-block transition-all duration-200" title="Lihat Lampiran">
                                <i class="fas fa-file-pdf text-lg"></i>
                            </a>
                        <?php else: ?>
                            <span class="text-gray-300">-</span>
                        <?php endif; ?>
                    </td>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit_certification', 'hapus_certification'])): ?>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Edit -->
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_certification')): ?>
                            <button type="button" onclick="openEditModal(<?php echo e(json_encode($cert)); ?>)" class="text-gray-400 hover:text-blue-600 border border-gray-200 p-1.5 rounded-md hover:bg-blue-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" title="Edit">
                                <i class="fas fa-pen w-4 text-center"></i>
                            </button>
                            <?php endif; ?>
                            
                            <!-- Delete -->
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hapus_certification')): ?>
                            <form action="<?php echo e(route('certification.destroy', $cert->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sertifikasi ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-gray-400 hover:text-red-600 border border-gray-200 p-1.5 rounded-md hover:bg-red-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" title="Hapus">
                                    <i class="fas fa-trash-alt w-4 text-center"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e((auth()->user()->can('edit_certification') || auth()->user()->can('hapus_certification')) ? 7 : 6); ?>" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-certificate text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">Belum ada sertifikasi</p>
                            <p class="text-xs text-gray-500 mt-1">Tambahkan sertifikasi baru menggunakan tombol di atas.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
<!-- Modal Tambah Sertifikasi -->
<div id="addModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="absolute inset-0 transition-opacity" aria-hidden="true" onclick="closeModal('addModal')"></div>
    
    <!-- Modal panel -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up relative z-10">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h3 class="text-xl font-bold text-gray-900" id="modal-title">Tambah Sertifikasi</h3>
                <p class="text-sm text-gray-500 mt-1">Tambahkan data sertifikasi baru</p>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-colors" onclick="closeModal('addModal')">
                <i class="fas fa-times text-lg text-center w-5 h-5 flex items-center justify-center"></i>
            </button>
        </div>
        
        <form action="<?php echo e(route('certification.store', $employee->id)); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-1 bg-white custom-scrollbar space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Sertifikasi</label>
                    <input type="text" name="nama_sertifikasi" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200" placeholder="Contoh: Certified Financial Risk Manager (FRM)">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Penerbit</label>
                        <input type="text" name="penerbit" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200" placeholder="Contoh: GARP">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Status</label>
                        <select name="status_sertifikat" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border bg-white font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Tanggal Terbit</label>
                        <input type="date" name="terbit" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Tanggal Kedaluwarsa</label>
                        <input type="date" name="kadaluarsa" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1.5">File Sertifikat (PDF atau Gambar)</label>
                    <input type="file" name="lampiran_sertifikasi" accept=".pdf,image/*" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2 border font-medium text-gray-700 bg-white hover:shadow-sm transition-all duration-200">
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <button type="button" class="bg-white py-2 px-5 border border-gray-200 rounded-lg shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="bg-[#06b6d4] hover:bg-[#0891b2] text-white py-2 px-5 rounded-lg shadow-sm text-sm font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Sertifikasi -->
<div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 transition-opacity" aria-hidden="true" onclick="closeModal('editModal')"></div>
    
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up relative z-10 transition-all transform">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Edit Sertifikasi</h3>
                <p class="text-sm text-gray-500 mt-1">Ubah data sertifikasi karyawan</p>
            </div>
            <button type="button" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" onclick="closeModal('editModal')">
                <i class="fas fa-times text-lg flex items-center justify-center w-5 h-5 text-center"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-1 bg-white custom-scrollbar space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1.5">Nama Sertifikasi</label>
                    <input type="text" name="nama_sertifikasi" id="edit_nama" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Penerbit</label>
                        <input type="text" name="penerbit" id="edit_penerbit" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Status</label>
                        <select name="status_sertifikat" id="edit_status" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border bg-white font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Tanggal Terbit</label>
                        <input type="date" name="terbit" id="edit_terbit" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1.5">Tanggal Kedaluwarsa</label>
                        <input type="date" name="kadaluarsa" id="edit_kadaluarsa" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 border font-medium text-gray-900 hover:shadow-sm transition-all duration-200">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1.5">File Sertifikat (PDF atau Gambar)</label>
                    <input type="file" name="lampiran_sertifikasi" accept=".pdf,image/*" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2 border font-medium text-gray-700 bg-white hover:shadow-sm transition-all duration-200">
                </div>

                <div id="current_file_container" class="hidden mt-3">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex flex-col items-center justify-center text-gray-400 overflow-hidden relative hover:shadow-md transition-all duration-200">
                            <i class="fas fa-file-pdf text-2xl"></i>
                            <a id="current_file_link" href="#" target="_blank" class="absolute inset-0 z-10" title="Lihat File"></a>
                        </div>
                        <button type="button" onclick="document.getElementById('hapus_lampiran_input').value = '1'; this.closest('#current_file_container').classList.add('hidden');" class="border border-gray-200 text-gray-700 font-bold text-xs px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                            Hapus File
                        </button>
                        <input type="hidden" name="hapus_lampiran" id="hapus_lampiran_input" value="0">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <button type="button" class="bg-white py-2 px-5 border border-gray-200 rounded-lg shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="bg-[#06b6d4] hover:bg-[#0891b2] text-white py-2 px-5 rounded-lg shadow-sm text-sm font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload Single File -->
<div id="uploadModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('uploadModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="bg-white px-6 pt-5 pb-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Upload Lampiran Sertifikasi</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal('uploadModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File PDF (Maks 10MB) <span class="text-red-500">*</span></label>
                            <input type="file" name="file" required accept=".pdf" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-cyan-500 focus:ring-cyan-500 sm:text-sm p-2 border">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-gray-100">
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50" onclick="closeModal('uploadModal')">Cancel</button>
                    <button type="submit" class="bg-[#06b6d4] hover:bg-[#0891b2] text-white py-2 px-4 rounded-lg shadow-sm text-sm font-medium transition-colors">Upload</button>
                </div>
            </form>
        </div>
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

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#certTable tbody tr');
        
        rows.forEach(row => {
            if(row.querySelector('.cert-name')) {
                let name = row.querySelector('.cert-name').textContent.toLowerCase();
                let publisher = row.querySelector('.cert-publisher').textContent.toLowerCase();
                if (name.includes(filter) || publisher.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openEditModal(cert) {
        // Populate form
        document.getElementById('edit_nama').value = cert.nama_sertifikasi;
        document.getElementById('edit_penerbit').value = cert.penerbit || '';
        document.getElementById('edit_terbit').value = cert.terbit ? cert.terbit.split('T')[0] : '';
        document.getElementById('edit_kadaluarsa').value = cert.kadaluarsa ? cert.kadaluarsa.split('T')[0] : '';
        document.getElementById('edit_status').value = cert.status_sertifikat;
        
        // Handle existing file display
        if (cert.lampiran_sertifikasi) {
            document.getElementById('current_file_container').classList.remove('hidden');
            document.getElementById('current_file_container').classList.add('block');
            document.getElementById('current_file_link').href = cert.lampiran_sertifikasi;
        } else {
            document.getElementById('current_file_container').classList.add('hidden');
            document.getElementById('current_file_container').classList.remove('block');
        }
        
        // Reset hapus lampiran hidden input
        const hapusInput = document.getElementById('hapus_lampiran_input');
        if(hapusInput) hapusInput.value = '0';
        
        // Update form action route
        let updateUrl = `<?php echo e(url('certification')); ?>/${cert.id}`;
        document.getElementById('editForm').action = updateUrl;
        
        openModal('editModal');
    }

    function openUploadModal(id) {
        let uploadUrl = `<?php echo e(url('certification')); ?>/${id}/upload-lampiran`;
        document.getElementById('uploadForm').action = uploadUrl;
        openModal('uploadModal');
    }
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views/user/certification/show.blade.php ENDPATH**/ ?>