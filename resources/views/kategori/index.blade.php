@extends('layouts.app')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori Barang')
@section('page-description', 'Kelola data kategori untuk barang inventaris')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #4361ee;">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $kategoris->count() }}</h3>
                <p>Total Kategori</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #10b981;">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalBarang ?? 0 }}</h3>
                <p>Total Barang</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #f59e0b;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $kategoris->count() }}</h3>
                <p>Kategori Aktif</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #8b5cf6;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $kategoris->where('created_at', '>=', now()->subDays(30))->count() }}</h3>
                <p>Kategori Baru (30 hari)</p>
            </div>
        </div>
    </div>
    
    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <h3 style="margin: 0;"><i class="fas fa-list"></i> Daftar Kategori</h3>
                <button class="btn-add" id="btnTambah">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            @if($kategoris->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th width="130">Tanggal Dibuat</th>
                                <th width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategoris as $index => $kat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $kat->nama_kategori }}</strong></td>
                                <td>{{ $kat->deskripsi ?? '-' }}</td>
                                <td>{{ $kat->created_at ? date('d/m/Y', strtotime($kat->created_at)) : '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit" onclick="editKategori({{ $kat->id }}, '{{ $kat->nama_kategori }}', '{{ addslashes($kat->deskripsi) }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-delete" onclick="hapusKategori({{ $kat->id }}, '{{ $kat->nama_kategori }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                 </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-tags"></i>
                    <h4>Belum Ada Data Kategori</h4>
                    <p>Silakan tambahkan kategori terlebih dahulu</p>
                    <button class="btn-add" id="btnTambahEmpty" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Kategori -->
<div id="kategoriModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Kategori</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" id="formKategori">
            @csrf
            <input type="hidden" name="id" id="kategoriId">
            <input type="hidden" name="_method" id="methodField" value="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kategori <span class="required">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Contoh: Elektronik, Furniture, Alat Tulis" required>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi kategori (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon i {
        font-size: 28px;
        color: white;
    }
    
    .stat-info h3 {
        font-size: 28px;
        margin: 0;
        color: #1e293b;
        font-weight: 700;
    }
    
    .stat-info p {
        margin: 5px 0 0;
        font-size: 13px;
        color: #64748b;
    }
    
    /* Button Add */
    .btn-add {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }
    
    /* Card */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: white;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #1e293b;
    }
    
    .card-body {
        padding: 20px 24px;
    }
    
    /* Alert */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    /* Table */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .table th {
        padding: 12px;
        text-align: left;
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    
    .table tr:hover {
        background: #f8fafc;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit, .btn-delete {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-edit {
        background: #fef3c7;
        color: #d97706;
    }
    
    .btn-edit:hover {
        background: #f59e0b;
        color: white;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .btn-delete:hover {
        background: #ef4444;
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 60px;
        color: #cbd5e1;
        margin-bottom: 15px;
        display: block;
    }
    
    .empty-state h4 {
        font-size: 16px;
        color: #475569;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 20px;
    }
    
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 1100;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: white;
        max-width: 500px;
        width: 90%;
        border-radius: 20px;
        animation: modalFadeIn 0.3s ease;
    }
    
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #94a3b8;
        transition: 0.2s;
    }
    
    .modal-close:hover {
        color: #ef4444;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
        font-size: 13px;
    }
    
    .required {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .btn-save {
        background: #4361ee;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-save:hover {
        background: #3a0ca3;
    }
    
    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        padding: 10px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
    }
    
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-add {
            width: 100%;
            justify-content: center;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th, .table td {
            padding: 8px;
        }
    }
</style>

<script>
// Modal elements
const modal = document.getElementById('kategoriModal');
const modalTitle = document.getElementById('modalTitle');
const form = document.getElementById('formKategori');
const methodField = document.getElementById('methodField');

// Tambah Kategori
document.getElementById('btnTambah').onclick = function() {
    modalTitle.innerText = 'Tambah Kategori';
    methodField.value = 'POST';
    document.getElementById('kategoriId').value = '';
    document.getElementById('nama_kategori').value = '';
    document.getElementById('deskripsi').value = '';
    modal.style.display = 'flex';
    form.action = '{{ route("kategori.store") }}';
}

if(document.getElementById('btnTambahEmpty')) {
    document.getElementById('btnTambahEmpty').onclick = function() {
        modalTitle.innerText = 'Tambah Kategori';
        methodField.value = 'POST';
        document.getElementById('kategoriId').value = '';
        document.getElementById('nama_kategori').value = '';
        document.getElementById('deskripsi').value = '';
        modal.style.display = 'flex';
        form.action = '{{ route("kategori.store") }}';
    }
}

// Edit Kategori
function editKategori(id, nama, deskripsi) {
    modalTitle.innerText = 'Edit Kategori';
    methodField.value = 'PUT';
    document.getElementById('kategoriId').value = id;
    document.getElementById('nama_kategori').value = nama;
    document.getElementById('deskripsi').value = deskripsi || '';
    modal.style.display = 'flex';
    form.action = '{{ url("kategori") }}/' + id;
}

// Hapus Kategori
function hapusKategori(id, nama) {
    if(confirm('Apakah Anda yakin ingin menghapus kategori "' + nama + '"?\n\nKategori yang memiliki barang tidak dapat dihapus.')) {
        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = '{{ url("kategori") }}/' + id;
        deleteForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                              '<input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(deleteForm);
        deleteForm.submit();
    }
}

function closeModal() {
    modal.style.display = 'none';
}

window.onclick = function(event) {
    if(event.target == modal) closeModal();
}
</script>
@endsection