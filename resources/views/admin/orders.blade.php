@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold mb-3 text-primary">Panel Admin - Kelola Transaksi PPOB</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Tujuan & Produk</th>
                                <th>Total</th>
                                <th>Bukti Transfer</th>
                                <th>Status Bayar / Trx</th>
                                <th class="text-center">Aksi (Update)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $item)
                            <tr>
                                <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong class="text-primary">{{ $item->invoice_number }}</strong></td>
                                <td>
                                    <div><strong>{{ $item->customer_no }}</strong></div>
                                    <small class="text-muted">{{ $item->product->name }}</small>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->proof_of_payment)
                                        <a href="{{ asset('storage/' . $item->proof_of_payment) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            Lihat Foto
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Belum Upload</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->payment_status == 'paid' ? 'success' : 'warning' }} text-uppercase mb-1">
                                        Bayar: {{ $item->payment_status }}
                                    </span>
                                    <br>
                                    <span class="badge bg-{{ $item->trx_status == 'success' ? 'success' : ($item->trx_status == 'failed' ? 'danger' : 'info') }} text-uppercase">
                                        Trx: {{ $item->trx_status }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.orders.update', $item->invoice_number) }}" method="POST" class="d-flex flex-column gap-1">
                                        @csrf
                                        <select name="payment_status" class="form-select form-select-sm">
                                            <option value="pending" {{ $item->payment_status == 'pending' ? 'selected' : '' }}>Bayar: PENDING</option>
                                            <option value="paid" {{ $item->payment_status == 'paid' ? 'selected' : '' }}>Bayar: PAID</option>
                                            <option value="failed" {{ $item->payment_status == 'failed' ? 'selected' : '' }}>Bayar: FAILED</option>
                                        </select>
                                        <select name="trx_status" class="form-select form-select-sm">
                                            <option value="pending" {{ $item->trx_status == 'pending' ? 'selected' : '' }}>Trx: PENDING</option>
                                            <option value="process" {{ $item->trx_status == 'process' ? 'selected' : '' }}>Trx: PROCESS</option>
                                            <option value="success" {{ $item->trx_status == 'success' ? 'selected' : '' }}>Trx: SUCCESS</option>
                                            <option value="failed" {{ $item->trx_status == 'failed' ? 'selected' : '' }}>Trx: FAILED</option>
                                        </select>
                                        <input type="text" name="sn" class="form-control form-control-sm" placeholder="Input SN / Token" value="{{ $item->sn }}">
                                        <button type="submit" class="btn btn-sm btn-primary mt-1">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada transaksi masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
