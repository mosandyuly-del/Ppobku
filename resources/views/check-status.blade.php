@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3 text-center">Cek Status Pesanan</h4>
                <form action="{{ route('order.check.search') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="query" placeholder="Masukkan No Invoice / No HP..." value="{{ $search ?? '' }}" required>
                        <button class="btn btn-primary" type="submit">Cari Pesanan</button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($orders))
            <h5 class="fw-bold mb-3">Hasil Pencarian:</h5>
            @forelse($orders as $item)
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-primary">{{ $item->invoice_number }}</div>
                            <div class="small text-muted">{{ $item->product->name }} ({{ $item->customer_no }})</div>
                            <div class="small text-muted">{{ $item->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            <span class="badge bg-{{ $item->payment_status == 'paid' ? 'success' : 'warning' }} text-uppercase">
                                {{ $item->payment_status }}
                            </span>
                            <div class="mt-2">
                                <a href="{{ route('order.show', $item->invoice_number) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    Data transaksi tidak ditemukan. Pastikan nomor Invoice atau Nomor HP sudah benar.
                </div>
            @endforelse
        @endif
    </div>
</div>
@endsection
