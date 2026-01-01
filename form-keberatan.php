<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/images/image.png" rel="icon" type="image/png">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css" />
    <link rel="stylesheet" href="assets/css/templatemo-scholar.css" />
    <link rel="stylesheet" href="assets/css/owl.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <style>
        body {
            background-color: #387f39;
            color: #000000;
            font-family: "Poppins", sans-serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0px 4px 100px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group label {
            color: #000000;
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            background-color: #fff;
            color: #333;
            padding: 7px;
        }

        .btn-primary {
            background-color: #387f39;
            border: none;
            padding: 10px 20px;
            width: 100%;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #2e6930;
        }

        .custom-checkbox label {
            color: #a5a5a5;
        }

        /* Hide the form-lanjutan by default */
        #form-lanjutan {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulir Permohonan Keberatan</h2>
        <p class="text-center">Sampaikan permohonan keberatan dengan benar agar permohonan anda dapat segera diproses.</p>
        <form action="submit_keberatan.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="kode-permohonan">Kode Permohonan</label>
                <input type="text" class="form-control" id="kode-permohonan" name="kode-permohonan" placeholder="Kode Permohonan" required />
            </div>
            <div class="form-group">
                <label for="identitas">Nomor Identitas (KTP/No. Badan Hukum/Nomor Induk Mahasiswa)</label>
                <input type="text" class="form-control" id="identitas" name="identitas" placeholder="Nomor Identitas" required />
            </div>
            <div class="form-group">
                <label for="sertakan-kuasa">Sertakan Identitas Kuasa?</label>
                <div class="form-group form-check custom-checkbox">
                    <input type="checkbox" class="form-check-input" id="sertakan-kuasa" name="sertakan-kuasa" />
                    <label class="form-check-label" for="sertakan-kuasa" id="label-checkbox">Tidak</label>
                </div>
            </div>

            <!-- Form yang akan berubah -->
            <div id="form-lanjutan">
                <!-- Form Default: Jika checkbox tidak dicentang -->
                <div class="form-group">
                    <label for="nama-kuasa">Nama Kuasa</label>
                    <input type="text" class="form-control" id="nama-kuasa" name="nama-kuasa" placeholder="Nama Kuasa" />
                </div>
                <div class="form-group">
                    <label for="alamat-kuasa">Alamat Kuasa</label>
                    <input type="text" class="form-control" id="alamat-kuasa" name="alamat-kuasa" placeholder="Alamat Kuasa" />
                </div>
                <div class="form-group">
                    <label for="no-hp">No. Handphone Kuasa</label>
                    <input type="text" class="form-control" id="no-hp" name="no-hp" placeholder="No. Handphone Kuasa" />
                </div>
            </div>

            <div class="form-group">
                <label for="alasan">Alasan Pengajuan Keberatan</label>
                <select class="form-control" id="alasan" name="alasan" required>
                    <option value="" disabled selected>Pilih Alasan Pengajuan Keberatan</option>
                    <option value="permohonan">Permohonan Informasi Ditolak</option>
                    <option value="info-berkala">Informasi Berkala Tidak Disediakan</option>
                    <option value="tidak-ditanggapi">Permintaan Informasi Tidak Ditanggapi</option>
                    <option value="ditanggapi-tidak-sesuai">Permintaan Informasi Ditanggapi Tidak Sesuai Sebagaimana Diminta</option>
                    <option value="tidak-dipenuhi">Permintaan Informasi Tidak Dipenuhi</option>
                    <option value="biaya">Biaya Yang Dikenakan Tidak Wajar</option>
                    <option value="melebihi-jangka-waktu">Informasi Disampaikan Melebihi Jangka Waktu Ditentukan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keterangan">Tujuan Pengajuan Keberatan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan Tambahan" required></textarea>
            </div>
            <div class="form-group">
                <label for="berkas">Upload Berkas Identitas, Surat Ijin Penelitian</label>
                <input type="file" class="form-control-file" id="berkas" name="berkas" required />
                <small class="form-text text-muted">Dijadikan 1 berkas (jpg, jpeg, png, pdf) Maksimal 5MB</small>
            </div>
            <div class="form-group form-check custom-checkbox">
                <input type="checkbox" class="form-check-input" id="konfirmasi" name="konfirmasi" required />
                <label class="form-check-label" for="konfirmasi">Informasi Yang Saya Masukkan Sudah Benar</label>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
        </form>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript untuk mengubah form berdasarkan checkbox -->
    <script>
        document.getElementById('sertakan-kuasa').addEventListener('change', function() {
            var formLanjutan = document.getElementById('form-lanjutan');
            var labelCheckbox = document.getElementById('label-checkbox');
            if (this.checked) {
                formLanjutan.style.display = 'block'; // Menampilkan form-lanjutan
                labelCheckbox.innerText = 'Iya';
            } else {
                formLanjutan.style.display = 'none'; // Menyembunyikan form-lanjutan
                labelCheckbox.innerText = 'Tidak';
            }
        });
    </script>
</body>
</html>
