@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0 fw-bold">Detail Tagihan</h5>
                <span class="badge bg-warning text-dark text-uppercase">{{ $order->payment_status }}</span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted">No. Invoice</td>
                        <td class="text-end fw-bold">{{ $order->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nomor Tujuan</td>
                        <td class="text-end fw-bold">{{ $order->customer_no }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Produk</td>
                        <td class="text-end fw-bold">{{ $order->product->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Transaksi</td>
                        <td class="text-end fw-bold text-uppercase">{{ $order->trx_status }}</td>
                    </tr>
                    @if($order->sn)
                    <tr>
                        <td class="text-muted">SN / Token</td>
                        <td class="text-end text-success fw-bold">{{ $order->sn }}</td>
                    </tr>
                    @endif
                    <tr class="border-top">
                        <td class="fs-5 fw-bold">Total Pembayaran</td>
                        <td class="fs-5 text-end text-primary fw-bold">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Instruksi Pembayaran -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Instruksi Pembayaran ({{ $order->paymentMethod->name }})</h6>
            </div>
            <div class="card-body text-center">
                @if($order->paymentMethod->code == 'QRIS')
                    <p class="mb-2">Silakan scan kode QRIS di bawah ini:</p>
                    <img src="{{ $order->paymentMethod->qr_image }}" alt="QRIS" class="img-fluid rounded border mb-3" style="max-width: 250px;">
                @else
                    <p class="mb-1 text-muted">Silakan transfer tepat sebesar total biaya ke rekening berikut:</p>
                    <h4 class="fw-bold text-dark my-2">{{ $order->paymentMethod->account_number }}</h4>
                    <p class="mb-0 text-muted">Atas Nama: <strong>{{ $order->paymentMethod->account_name }}</strong></p>
                @endif
            </div>
        </div>

        <!-- Form Upload Bukti Transfer -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">Konfirmasi Pembayaran</h6>
            </div>
            <div class="card-body">
                @if($order->proof_of_payment)
                    <div class="alert alert-info mb-0">
                        Bukti bayar telah diunggah. Tim kami sedang memverifikasi pembayaran Anda.
                    </div>
                @else
                    <form action="{{ route('order.upload', $order->invoice_number) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="proof" class="form-label">Unggah Bukti Transfer (JPG/PNG)</label>
                            <input class="form-control @error('proof') is-invalid @enderror" type="file" id="proof" name="proof" required>
                            @error('proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">Kirim Bukti Pembayaran</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
