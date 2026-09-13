@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold text-primary">Isi Pulsa & Paket Data</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf
                    
                    <!-- Form Input Nomor HP -->
                    <div class="mb-4">
                        <label for="customer_no" class="form-label fw-semibold">Nomor Handphone</label>
                        <input type="text" class="form-control form-control-lg @error('customer_no') is-invalid @enderror" 
                               id="customer_no" name="customer_no" placeholder="Contoh: 081234567890" value="{{ old('customer_no') }}" required autocomplete="off">
                        <div class="form-text" id="operator-info">Masukkan nomor HP untuk mendeteksi provider.</div>
                        @error('customer_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Pilihan Produk Nominal -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Nominal</label>
                        <div class="row g-2" id="product-container">
                            @foreach($products as $product)
                            <div class="col-6 col-md-4 product-card" data-brand="{{ strtolower($product->brand) }}">
                                <input type="radio" class="btn-check" name="sku_code" id="sku_{{ $product->sku_code }}" value="{{ $product->sku_code }}" required>
                                <label class="btn btn-outline-primary w-100 text-start p-3 h-100" for="sku_{{ $product->sku_code }}">
                                    <div class="small text-muted">{{ $product->brand }}</div>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <div class="text-success fw-semibold mt-1">Rp {{ number_format($product->price_sell, 0, ',', '.') }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('sku_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Pilihan Metode Pembayaran -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Metode Pembayaran</label>
                        <div class="row g-2">
                            @foreach($paymentMethods as $pm)
                            <div class="col-12 col-md-4">
                                <input type="radio" class="btn-check" name="payment_method_id" id="pm_{{ $pm->id }}" value="{{ $pm->id }}" required>
                                <label class="btn btn-outline-secondary w-100 text-start p-3" for="pm_{{ $pm->id }}">
                                    <div class="fw-bold">{{ $pm->name }}</div>
                                    <small class="text-muted">{{ $pm->code }}</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('payment_method_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Beli Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('customer_no');
    const operatorInfo = document.getElementById('operator-info');
    const productCards = document.querySelectorAll('.product-card');

    const prefixMap = {
        'telkomsel': ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
        'indosat': ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
        'xl': ['0817', '0818', '0819', '0859', '0877', '0878']
    };

    phoneInput.addEventListener('input', function () {
        const val = this.value.trim();
        let detectedBrand = null;

        if (val.length >= 4) {
            const prefix = val.substring(0, 4);
            for (const [brand, prefixes] of Object.entries(prefixMap)) {
                if (prefixes.includes(prefix)) {
                    detectedBrand = brand;
                    break;
                }
            }
        }

        if (detectedBrand) {
            operatorInfo.innerHTML = `Provider terdeteksi: <strong class="text-uppercase text-primary">${detectedBrand}</strong>`;
            
            productCards.forEach(card => {
                if (card.dataset.brand === detectedBrand) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio) radio.checked = false;
                }
            });
        } else {
            operatorInfo.innerText = 'Masukkan nomor HP untuk mendeteksi provider.';
            productCards.forEach(card => card.style.display = 'block');
        }
    });
});
</script>
@endpush
