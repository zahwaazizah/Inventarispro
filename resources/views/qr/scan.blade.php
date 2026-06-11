@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('page-title', 'Scan QR Code')
@section('page-description', 'Arahkan kamera ke QR Code barang untuk meminjam')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-camera"></i> Scanner QR Code</h3>
    </div>
    <div class="card-body text-center">
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Pastikan QR Code barang terlihat jelas dan dalam pencahayaan yang cukup</p>
        </div>

        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
        <div id="loading-message" style="display: none; padding: 20px;">
            <i class="fas fa-spinner fa-spin"></i> Mengakses kamera...
        </div>

        <hr>

        <div class="backup-method">
            <h4><i class="fas fa-keyboard"></i> Metode Cadangan</h4>
            <p>Jika QR Code tidak terbaca, silakan gunakan form manual</p>
            <a href="{{ route('transaksi.peminjaman.form') }}" class="btn-backup">
                <i class="fas fa-hand-holding"></i> Form Peminjaman Manual
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    // Ambil base URL dari meta tag atau dari lokasi saat ini
    const baseUrl = "{{ url('/') }}"; // otomatis menghasilkan https://.../inventarispro

    function startScanner() {
        document.getElementById('loading-message').style.display = 'block';

        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                // Ekstrak hash dari URL jika perlu
                let hash = decodedText;
                if (decodedText.startsWith('http')) {
                    try {
                        const url = new URL(decodedText);
                        const pathParts = url.pathname.split('/');
                        hash = pathParts[pathParts.length - 1];
                    } catch(e) {
                        const parts = decodedText.split('/');
                        hash = parts[parts.length - 1];
                    }
                }
                // Redirect ke form peminjaman
                window.location.href = baseUrl + "/transaksi/peminjaman?qr=" + encodeURIComponent(hash);
            },
            (error) => { /* continue scanning */ }
        ).catch(err => {
            document.getElementById('loading-message').style.display = 'none';
            alert('Tidak dapat mengakses kamera. Pastikan izin diberikan.');
        });

        setTimeout(() => {
            document.getElementById('loading-message').style.display = 'none';
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', startScanner);
</script>
@endpush