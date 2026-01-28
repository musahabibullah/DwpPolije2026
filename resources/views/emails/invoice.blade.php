<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path("images/buktipembayaran.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: 0;
        }
        .invoice-header {
            background-color: #0056b3;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }
        .invoice-header h1 {
            margin: 0;
            padding: 15px 0;
            font-size: 28px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .invoice-content {
            padding: 30px;
            position: relative;
            z-index: 1;
        }
        .info-row {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 180px;
        }
        .info-value {
            display: inline-block;
            font-size: 16px;
        }
        .invoice-footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
            position: relative;
            z-index: 1;
        }
        .transaction-box {
            border: 2px solid #0056b3;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .transaction-box h2 {
            margin-top: 0;
            color: #0056b3;
            border-bottom: 1px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .stamp {
            position: absolute;
            margin-bottom: 20px;
            top: 50px;
            right: 40px;
            transform: rotate(15deg);
            color: rgba(0, 86, 179, 0.2);
            border: 4px solid rgba(0, 86, 179, 0.2);
            padding: 10px;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
            z-index: 2;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="background-image"></div>
        <div class="invoice-header">
            <h1>Invoice Pembayaran</h1>
        </div>
        
        <div class="invoice-content">
            <div class="stamp">LUNAS</div>
            
            <div class="transaction-box">
                <h2>Detail Transaksi</h2>
                <div class="info-row">
                    <span class="info-label">Nomor Transaksi:</span>
                    <span class="info-value">{{ $nomor_transaksi }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu Transaksi:</span>
                    <span class="info-value">{{ $waktu_transaksi }}</span>
                </div>
            </div>
            
            <div class="transaction-box">
                <h2>Informasi Pembayar</h2>
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value">{{ $nama }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jurusan/Unit:</span>
                    <span class="info-value">{{ $jurusan }}</span>
                </div>
                @if(isset($nik))
                <div class="info-row">
                    <span class="info-label">NIK:</span>
                    <span class="info-value">{{ $nik }}</span>
                </div>
                @endif
                @if(isset($email))
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $email }}</span>
                </div>
                @endif
                @if(isset($no_telpon))
                <div class="info-row">
                    <span class="info-label">No. Telepon:</span>
                    <span class="info-value">{{ $no_telpon }}</span>
                </div>
                @endif
            </div>
            
            @if(isset($kode_unik))
            <div style="text-align: center; margin: 30px 0;">
                <div style="display: inline-block; padding: 15px 30px; background-color: #e9f7fe; border: 2px dashed #0056b3; border-radius: 5px;">
                    <p style="margin: 0; font-size: 16px; color: #0056b3;">Kode Unik</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 24px; color: #0056b3;">{{ $kode_unik }}</h3>
                </div>
            </div>
            @endif
            
            <div style="text-align: center; margin-top: 40px;">
                <p style="font-size: 18px; color: #0056b3; font-weight: bold;">Terima Kasih Atas Pembayaran Anda</p>
            </div>
        </div>
        
        <div class="invoice-footer">
            <p>Dokumen ini dihasilkan secara otomatis dan sah tanpa tanda tangan.</p>
            <p>&copy; {{ date('Y') }} Nama Instansi Anda. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>