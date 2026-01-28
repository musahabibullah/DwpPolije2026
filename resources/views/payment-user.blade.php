<!DOCTYPE html>
<html lang="id">
<head>
    <link href="assets/img/logo_polije.png" rel="icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran DWP - Polije</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html {
            overflow-y: scroll;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background: linear-gradient(135deg, rgba(48, 136, 212, 0.8), rgba(37, 107, 176, 0.9)), 
                        url('images/gedungA3.png') center/cover no-repeat fixed;
            min-height: 100vh;
            background-attachment: fixed;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .logo {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .logo img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .background {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 40px;
        }

        .form-container {
            background: #ffffff;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 800px;
            text-align: left;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3088d4;
        }

        .form-icon {
            display: inline-block;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3088d4, #256bb0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(48, 136, 212, 0.4);
        }

        .form-icon i {
            font-size: 32px;
            color: white;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .form-header .subtitle {
            font-size: 14px;
            color: #666;
            font-weight: 400;
            margin: 0;
        }

        .payment-info-card {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f2ff 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid #3088d4;
            box-shadow: 0 5px 15px rgba(48, 136, 212, 0.2);
        }

        .info-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3088d4;
        }

        .info-header i {
            font-size: 24px;
            color: #3088d4;
        }

        .info-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0;
        }

        .info-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-item label {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-item label i {
            color: #3088d4;
        }

        .nominal-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .nominal-display {
            margin-top: 10px;
        }

        .nominal-number {
            font-size: 32px;
            font-weight: 700;
            color: #e74c3c;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nominal-text {
            font-size: 13px;
            font-weight: 500;
            color: #c0392b;
            margin: 5px 0 0 0;
            font-style: italic;
        }

        .bank-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .bank-value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            background: white;
            padding: 12px;
            border-radius: 8px;
            margin: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .bank-value.loading {
            color: #95a5a6;
            font-style: italic;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #3088d4;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #3088d4;
            font-size: 16px;
        }

        .required {
            color: #e74c3c;
            font-weight: 700;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            padding-right: 40px;
        }

        .input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            display: none;
        }

        .valid-icon {
            color: #27ae60;
        }

        .form-container input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-container input:focus {
            outline: none;
            border-color: #3088d4;
            background: white;
            box-shadow: 0 0 0 4px rgba(48, 136, 212, 0.1);
        }

        .form-container input:read-only {
            background: #f5f5f5;
            cursor: not-allowed;
            color: #666;
        }

        .form-container input:not(:read-only):not(:placeholder-shown) {
            border-color: #27ae60;
        }

        .file-upload {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 200px;
            border: 3px dashed #3088d4;
            border-radius: 15px;
            background: linear-gradient(135deg, #f8fbff 0%, #f0f7ff 100%);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            overflow: hidden;
            padding: 20px;
        }

        .file-upload:hover {
            background: linear-gradient(135deg, #e8f4ff 0%, #ddefff 100%);
            border-color: #256bb0;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(48, 136, 212, 0.2);
        }

        .upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .file-upload img {
            transition: all 0.3s ease;
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;
        }

        .upload-text-wrapper {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        #upload-text {
            font-size: 16px;
            color: #2c3e50;
            margin: 0;
            font-weight: 600;
        }

        #format-text {
            font-size: 12px;
            color: #7f8c8d;
            margin: 0;
            font-weight: 400;
        }

        #remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            font-size: 18px;
            width: 36px;
            height: 36px;
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
            transition: all 0.3s ease;
        }

        #remove-btn:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: scale(1.1);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            top: 30px;
            right: 45px;
            color: white;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .close:hover {
            color: #e74c3c;
            transform: scale(1.1);
        }

        .warning-box {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 15px 20px;
            margin: 25px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
        }

        .warning-box i {
            font-size: 24px;
            color: #ff9800;
            flex-shrink: 0;
        }

        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3088d4 0%, #256bb0 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(48, 136, 212, 0.3);
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #256bb0 0%, #1e5a96 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(48, 136, 212, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn i {
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .header {
                padding: 12px 20px;
            }

            .logo img {
                height: 40px;
            }

            .background {
                padding: 80px 15px 30px;
            }

            .form-container {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .form-header h2 {
                font-size: 22px;
            }

            .form-icon {
                width: 60px;
                height: 60px;
            }

            .form-icon i {
                font-size: 28px;
            }

            .nominal-number {
                font-size: 26px;
            }

            .bank-info {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .payment-info-card {
                padding: 20px;
            }

            .section-title {
                font-size: 16px;
            }

            .close {
                top: 20px;
                right: 30px;
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/Bazzar.png" alt="Logo Bazaar"> 
        </div>
    </div>
    <div class="background">
        <div class="form-container">
            <!-- Header Form dengan Icon -->
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h2>FORM PEMBAYARAN DWP</h2>
                <p class="subtitle">Politeknik Negeri Jember</p>
            </div>

            <!-- Payment Information Card -->
            <div class="payment-info-card">
                <div class="info-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>INFORMASI PEMBAYARAN</h3>
                </div>
                
                <div class="info-content">
                    <div class="info-item nominal-section">
                        <label><i class="fas fa-coins"></i> NOMINAL PEMBAYARAN</label>
                        <div class="nominal-display">
                            <p id="nominal_angka" class="nominal-number">Rp 120.000</p>
                            <p id="nominal_huruf" class="nominal-text">(Seratus dua puluh ribu rupiah)</p>
                        </div>
                    </div>

                    <div class="bank-info">
                        <div class="info-item">
                            <label><i class="fas fa-university"></i> NO. REKENING</label>
                            <p id="no_rekening" class="bank-value loading">Memuat data...</p>
                        </div>

                        <div class="info-item">
                            <label><i class="fas fa-user-circle"></i> ATAS NAMA</label>
                            <p id="nama_rekening" class="bank-value loading">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Data Section -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-user-edit"></i> Data Pembayar</h3>

            <!-- Form Data Section -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-user-edit"></i> Data Pembayar</h3>

            <div class="form-group">
                <label for="nik">
                    <i class="fas fa-id-card"></i> NIK/NIPPPK/NIP
                    <span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <input type="text" id="nik" placeholder="Masukkan NIK/NIPPPK/NIP" oninput="checkFields()" readonly>
                    <i class="fas fa-check-circle input-icon valid-icon"></i>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nama">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </label>
                    <input type="text" id="nama" placeholder="Nama akan terisi otomatis" readonly>
                </div>
                
                <div class="form-group">
                    <label for="jabatan">
                        <i class="fas fa-briefcase"></i> Jabatan
                    </label>
                    <input type="text" id="jabatan" placeholder="Jabatan akan terisi otomatis" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="jurusan">
                    <i class="fas fa-building"></i> Jurusan/Unit Kerja
                </label>
                <input type="text" id="jurusan" placeholder="Jurusan/Unit akan terisi otomatis" readonly>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="hp">
                        <i class="fas fa-phone"></i> No. WhatsApp
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="hp" placeholder="08xxxxxxxxxx" oninput="checkFields()">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Aktif
                        <span class="required">*</span>
                    </label>
                    <input type="email" id="email" placeholder="email@example.com" oninput="checkFields()">
                </div>
            </div>
            
            <div class="form-group">
                <label for="file">
                    <i class="fas fa-upload"></i> Bukti Pembayaran
                    <span class="required">*</span>
                </label>
                <div class="file-upload" id="fileUploadBox" onclick="document.getElementById('file').click()">
                    <div class="upload-content">
                        <img id="preview" src="images/arrow.png" alt="Upload Icon">
                        <div class="upload-text-wrapper">
                            <p id="upload-text">Klik atau seret file ke sini</p>
                            <p id="format-text">Format: JPG, JPEG, PNG (Max: 2MB)</p>
                        </div>
                    </div>
                    <input type="file" id="file" accept="image/*" onchange="previewImage(event)" style="display: none;">
                    <span id="remove-btn" onclick="removeImage(event)">
                        <i class="fas fa-times"></i>
                    </span>
                </div>
            </div>
            </div>
            
            <!-- Modal untuk memperbesar gambar -->
            <div id="imageModal" class="modal" onclick="closeModal()">
                <span class="close">&times;</span>
                <img class="modal-content" id="modalImg">
            </div>

            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Harap periksa kembali data Anda dengan teliti. Kesalahan input bukan tanggung jawab admin.</p>
            </div>

            <button id="submitBtn" class="submit-btn" onclick="submitForm()">
                <i class="fas fa-paper-plane"></i> Kirim Pembayaran
            </button>
        </div>
    </div>

    <script>
    function checkFields() {
        let nik = $("#nik").val().trim();

        if (nik.length > 0) {
            $.ajax({
                url: '/get-rekening/' + nik,
                type: 'GET',
                success: function (response) {
                    console.log(response);

                    if (response.message) {
                        // Jika NIK tidak ditemukan
                        $("#nama").val("");
                        $("#jurusan").val("");
                        $("#jabatan").val("");
                        $("#no_rekening").text("XXXX-XXXX-XXXXX");
                        $("#nama_rekening").text("DWP Polije");
                    } else {
                        // Jika NIK ditemukan, tampilkan data
                        $("#nama").val(response.nama);
                        $("#jurusan").val(response.jurusan);
                        $("#jabatan").val(response.jabatan);
                        $("#no_rekening").text(response.rekening);
                        $("#nama_rekening").text(response.nama_rekening);
                    }
                },
                error: function () {
                    console.log("Error mengambil data");
                    $("#nama").val("");
                    $("#jurusan").val("");
                    $("#jabatan").val("");
                    $("#no_rekening").text("XXXX-XXXX-XXXXX");
                    $("#nama_rekening").text("DWP Polije");
                }
            });
        } else {
            $("#nama").val("");
            $("#jurusan").val("");
            $("#jabatan").val("");
            $("#no_rekening").text("XXXX-XXXX-XXXXX");
            $("#nama_rekening").text("DWP Polije");
        }
    }

    // Jalankan checkFields setiap kali input NIK berubah
    $(document).ready(function () {
        $("#nik").on("input", function () {
            checkFields();
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("nik").addEventListener("input", function () {
            let nik = this.value.trim();

            if (nik.length > 0) {
                fetch(`/get-rekening/${nik}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        if (data.message) {
                            document.getElementById("nama").value = "";
                            document.getElementById("jurusan").value = "";
                            document.getElementById("jabatan").value = "";
                            document.getElementById("no_rekening").textContent = "XXXX-XXXX-XXXXX";
                            document.getElementById("nama_rekening").textContent = "DWP Polije";
                        } else {
                            document.getElementById("nama").value = data.nama;
                            document.getElementById("jurusan").value = data.jurusan;
                            document.getElementById("jabatan").value = data.jabatan;
                            document.getElementById("no_rekening").textContent = data.rekening;
                            document.getElementById("nama_rekening").textContent = data.nama_rekening;
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        document.getElementById("nama").value = "";
                        document.getElementById("jurusan").value = "";
                        document.getElementById("jabatan").value = "";
                        document.getElementById("no_rekening").textContent = "XXXX-XXXX-XXXXX";
                        document.getElementById("nama_rekening").textContent = "DWP Polije";
                    });
            } else {
                document.getElementById("nama").value = "";
                document.getElementById("jurusan").value = "";
                document.getElementById("jabatan").value = "";
                document.getElementById("no_rekening").textContent = "XXXX-XXXX-XXXXX";
                document.getElementById("nama_rekening").textContent = "DWP Polije";
            }
        });
    });
</script>


    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const preview = document.getElementById('preview');
            const uploadText = document.getElementById('upload-text');
            const formatText = document.getElementById('format-text');
            const removeBtn = document.getElementById('remove-btn');
            const uploadBox = document.getElementById('fileUploadBox');

            if (file) {
                // Validate file size (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    Swal.fire({
                        icon: "error",
                        title: "File Terlalu Besar!",
                        text: "Ukuran file maksimal adalah 2MB",
                        scrollbarPadding: false
                    });
                    fileInput.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.maxWidth = "100%";
                    preview.style.maxHeight = "180px";
                    preview.style.borderRadius = "12px";
                    preview.style.objectFit = "contain";
                    preview.style.cursor = "pointer";
                    preview.style.boxShadow = "0 4px 12px rgba(0,0,0,0.15)";
                    preview.onclick = openModal;

                    uploadText.style.display = "none";
                    formatText.style.display = "none";
                    removeBtn.style.display = "flex";
                    uploadBox.style.borderColor = "#27ae60";
                    uploadBox.style.background = "linear-gradient(135deg, #f0fff4 0%, #e6ffed 100%)";
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage(event) {
            event.stopPropagation();

            const preview = document.getElementById('preview');
            const uploadText = document.getElementById('upload-text');
            const formatText = document.getElementById('format-text');
            const removeBtn = document.getElementById('remove-btn');
            const fileInput = document.getElementById('file');
            const uploadBox = document.getElementById('fileUploadBox');

            preview.src = "images/arrow.png";
            preview.style.maxWidth = "80px";
            preview.style.maxHeight = "80px";
            preview.style.cursor = "default";
            preview.style.boxShadow = "none";
            preview.onclick = null;

            uploadText.style.display = "block"; 
            formatText.style.display = "block";
            removeBtn.style.display = "none";
            fileInput.value = "";
            uploadBox.style.borderColor = "#3088d4";
            uploadBox.style.background = "linear-gradient(135deg, #f8fbff 0%, #f0f7ff 100%)";
        }

        function openModal() {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            const preview = document.getElementById('preview');
            const fileInput = document.getElementById('file');

            modal.style.display = "flex";
            modalImg.src = preview.src;

            fileInput.disabled = true;
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            const fileInput = document.getElementById('file');

            modal.style.display = "none";

            fileInput.disabled = false;
        }
    </script>

    <script>
    document.getElementById('nik').addEventListener('input', function() {
        let nik = this.value.trim();
        const submitBtn = document.getElementById('submitBtn');

        // Pastikan tombol submit selalu aktif
        submitBtn.disabled = false;
        submitBtn.classList.remove('disabled');

        // Jika NIK berisi huruf, munculkan pop-up dan kosongkan input
        if (/\D/.test(nik)) {  // \D = mendeteksi karakter selain angka
            Swal.fire({
                icon: "warning",
                title: "Input Tidak Valid!",
                text: "NIK hanya boleh berisi angka!",
                scrollbarPadding: false
            });
            this.value = ""; // Kosongkan input NIK
            return;
        }

        // Jika NIK dikosongkan, reset kolom Nama, Jurusan, dan Jabatan
        if (nik === "") {
            document.getElementById('nama').value = "";
            document.getElementById('jurusan').value = "";
            document.getElementById('jabatan').value = "";
        }
    });

    function submitForm() {
        const nik = document.getElementById('nik').value.trim();
        const hp = document.getElementById('hp').value.trim();
        const email = document.getElementById('email').value.trim();
        const fileInput = document.getElementById('file').files[0];

        // Validasi input tidak boleh kosong
        if (!nik || !hp || !email || !fileInput) {
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Harap lengkapi semua data!",
                scrollbarPadding: false
            });
            return;
        }

        // Validasi ekstensi file hanya boleh jpg, jpeg, png
        const allowedExtensions = ["image/jpeg", "image/png", "image/jpg"];
        if (!allowedExtensions.includes(fileInput.type)) {
            Swal.fire({
                icon: "error",
                title: "Format File Tidak Valid",
                text: "Hanya diperbolehkan file JPG, JPEG, dan PNG!",
                scrollbarPadding: false
            });
            return;
        }

        // Show loading popup before sending request
        Swal.fire({
            title: 'Sedang Mengirim...',
            text: 'Mohon tunggu sebentar',
            scrollbarPadding: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        let formData = new FormData();
        formData.append('nik', nik);
        formData.append('hp', hp);
        formData.append('email', email);
        formData.append('bukti_pembayaran', fileInput);

        // Ambil CSRF token
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Token CSRF tidak ditemukan.",
                scrollbarPadding: false
            });
            return;
        }
        const csrfToken = csrfTokenMeta.getAttribute('content');

        fetch('/submit-pembayaran', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "Form berhasil terkirim. Silahkan cek email dan whatsapp anda!",
                    scrollbarPadding: false
                }).then(() => {
                    window.location.href = "/";
                });

                if (data.pdf_url) {
                setTimeout(() => {
                const link = document.createElement('a');
                link.href = data.pdf_url;
                link.download = data.pdf_url.split('/').pop();
                link.click();
            }, 500); // 500ms delay
        }

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: data.error || "Terjadi kesalahan.",
                    scrollbarPadding: false
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Terjadi kesalahan dalam mengirim data.",
                scrollbarPadding: false
            });
            console.error("Error:", error);
        });
    }
    </script>

    <!-- DI HALAMAN FORM OTOMATIS NIK TERISI -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const nik = urlParams.get('nik');

        if (nik) {
            document.getElementById('nik').value = nik;
            checkFields(); // Panggil fungsi untuk mengambil data rekening
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>