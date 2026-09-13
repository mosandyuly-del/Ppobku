@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold text-primary mb-0">Kelola Produk PPOB</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddProduct">+ Tambah Produk Baru</button>
    </div>

    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SKU Code</th>
                                <th>Nama Produk</th>
                                <th>Brand / Provider</th>
                                <th>Harga Modal</th>
                                <th>Harga Jual</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $item)
                            <tr>
                                <td><code class="fw-bold">{{ $item->sku_code }}</code></td>
                                <td class="fw-semibold">{{ $item->name }}</td>
                                <td><span class="badge bg-secondary">{{ $item->brand }}</span></td>
                                <td>Rp {{ number_format($item->price_original, 0, ',', '.') }}</td>
                                <td class="text-success fw-bold">Rp {{ number_format($item->price_sell, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.products.update', $item->id) }}" method="POST" class="d-flex gap-1 align-items-center">
                                        @csrf
                                        <input type="number" name="price_sell" class="form-control form-control-sm" style="width: 110px;" value="{{ (int)$item->price_sell }}" placeholder="Harga Jual">
                                        <input type="hidden" name="name" value="{{ $item->name }}">
                                        <input type="hidden" name="brand" value="{{ $item->brand }}">
                                        <input type="hidden" name="price_original" value="{{ $item->price_original }}">
                                        <select name="is_active" class="form-select form-select-sm" style="width: 100px;">
                                            <option value="1" {{ $item->is_active ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ !$item->is_active ? 'selected' : '' }}>Off</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalAddProduct" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode SKU (Unik)</label>
                        <input type="text" name="sku_code" class="form-control" placeholder="Contoh: TSEL100K" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Pulsa Telkomsel 100.000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand / Provider</label>
                        <input type="text" name="brand" class="form-control" placeholder="Contoh: Telkomsel / Indosat / PLN" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Modal (Rp)</label>
                        <input type="number" name="price_original" class="form-control" placeholder="99000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Jual (Rp)</label>
                        <input type="number" name="price_sell" class="form-control" placeholder="101000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
