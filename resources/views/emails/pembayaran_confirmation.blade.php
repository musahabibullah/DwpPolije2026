<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-image: url('{{ asset("images/barcode.png") }}');
            background-size: cover;
            background-position: center;
            padding: 20px;
            text-align: center;
            color: #ffffff;
            background-color: #0056b3; /* Fallback if image doesn't load */
        }
        .email-header h1 {
            margin: 0;
            padding: 15px 0;
            font-size: 24px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .email-content {
            padding: 30px;
            background-color: #ffffff;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .info-note {
            background-color: #e9f7fe;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0099e5;
            margin: 20px 0;
        }
        .btn-primary {
            display: inline-block;
            background-color: #0056b3;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Konfirmasi Pembayaran</h1>
        </div>
        
        <div class="email-content">
            <p>Yth. {{ $nama }},</p>
            
            <p>Terima kasih atas pembayaran Anda. Berikut adalah detail informasi pembayaran:</p>
            
            <table class="info-table">
                <tr>
                    <td>Nomor Transaksi</td>
                    <td>{{ $nomor_transaksi }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran</td>
                    <td>{{ $tanggal_pembayaran }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>{{ $nama }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>{{ $nik }}</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>{{ $jurusan }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $email }}</td>
                </tr>
                <tr>
                    <td>No. Telepon</td>
                    <td>{{ $no_telpon }}</td>
                </tr>
                <tr>
                    <td>Meja Pengambilan</td>
                    <td>{{ $meja }}</td>
                </tr>
                <tr>
                    <td>Kode Unik</td>
                    <td><strong>{{ $kode_unik }}</strong></td>
                </tr>
            </table>
            
            <div class="info-note">
                <p><strong>Penting:</strong> Terlampir adalah bukti pembayaran dalam format PDF. Harap cetak dan bawa saat pengambilan, atau tunjukkan kode unik yang tertera.</p>
            </div>
            
            <p>Jika Anda memiliki pertanyaan, silakan hubungi tim kami.</p>
            
            <p>Hormat kami,<br>
            Tim Administrasi</p>
        </div>
        
        <div class="email-footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Nama Instansi Anda. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>