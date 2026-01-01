<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Berhasil - SiKaBar</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #387f39;
            font-family: "Poppins", sans-serif;
        }
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0px 4px 100px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-icon {
            color: #387f39;
            font-size: 60px;
            margin-bottom: 20px;
        }
        .tracking-code {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .btn-custom {
            background-color: #387f39;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #2d6b2d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Permohonan Berhasil!</h2>
            <p class="mb-4">Terima kasih telah mengajukan permohonan informasi. Permohonan Anda telah berhasil direkam dalam sistem kami.</p>
            
            <div class="tracking-code">
                <?php 
                $kode = isset($_GET['kode']) ? htmlspecialchars($_GET['kode']) : 'KODE TIDAK TERSEDIA';
                echo $kode;
                ?>
            </div>
            
            <p class="mb-4">Silakan simpan kode tracking di atas untuk memantau status permohonan Anda.</p>
            
            <div class="mb-4">
                <a href="index.php" class="btn btn-custom mr-2">Kembali ke Beranda</a>
                <a href="javascript:void(0)" class="btn btn-custom" onclick="window.print()">Cetak Kode</a>
            </div>
            
            <small class="text-muted">Jika Anda memiliki pertanyaan, silakan hubungi kami melalui kontak yang tersedia.</small>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-kit-code.js"></script>
</body>
</html>