<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Cek Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="mb-3">
                                <label for="trx_id" class="form-label">Nomor Transaksi / Invoice</label>
                                <input type="text" class="form-control" id="trx_id" name="trx_id" placeholder="Masukkan ID Transaksi" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Cek Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
