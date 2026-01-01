<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_permohonan']);
    $identitas = mysqli_real_escape_string($conn, $_POST['identitas']);
    $instansi = mysqli_real_escape_string($conn, $_POST['instansi']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $pekerjaan = mysqli_real_escape_string($conn, $_POST['pekerjaan']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_handphone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rincian = mysqli_real_escape_string($conn, $_POST['rincian']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $cara_memperoleh = mysqli_real_escape_string($conn, $_POST['cara_memperoleh']);
    $cara_mendapatkan = mysqli_real_escape_string($conn, $_POST['cara_mendapatkan']);
    $konfirmasi = isset($_POST['konfirmasi']) ? 1 : 0;

    // Handle file upload
    $berkas = '';
    if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $filename = $_FILES['berkas']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $upload_path = 'uploads/' . time() . '_' . $filename;
            if (move_uploaded_file($_FILES['berkas']['tmp_name'], $upload_path)) {
                $berkas = $upload_path;
            }
        }
    }

    // Insert into database
    $query = "INSERT INTO informasi_permohonan (
        kategori_permohonan, identitas, instansi, nama, alamat, 
        pekerjaan, no_handphone, email, rincian, tujuan, 
        cara_memperoleh, cara_mendapatkan, berkas, konfirmasi
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssssssssssi", 
            $kategori, $identitas, $instansi, $nama, $alamat,
            $pekerjaan, $no_hp, $email, $rincian, $tujuan,
            $cara_memperoleh, $cara_mendapatkan, $berkas, $konfirmasi
        );

        if ($stmt->execute()) {
            $id_permohonan = $conn->insert_id;
            // Tracking code will be generated automatically by trigger
            
            // Redirect with success message
            header("Location: success.php?id=" . $id_permohonan);
            exit;
        }
        $stmt->close();
    }
    
    // If we get here, there was an error
    header("Location: form.php?error=1");
    exit;
}
?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulir Permohonan Informasi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/images/image.png" rel="icon" type="image/png" />
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

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
        background-color: #387f39; /* Warna latar belakang */
        color: #000000; /* Warna teks default */
        font-family: "Poppins", sans-serif;
      }

      .container {
        max-width: 700px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff; /* Warna latar belakang form */
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
        background-color: #fff; /* Warna background input */
        color: #333; /* Warna teks input */
        padding: 7px;
      }

      .btn-primary {
        background-color: #387f39;
        border: none;
        padding: 10px 20px;
        width: 100%;
        margin-top: 20px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: bold;
      }

      .btn-primary:hover {
        background-color: #387f39;
      }

      .custom-checkbox label {
        color: #a5a5a5;
      }
      .alert {
          margin-bottom: 20px;
          padding: 15px;
          border-radius: 5px;
      }

      .alert-danger {
          color: #721c24;
          background-color: #f8d7da;
          border-color: #f5c6cb;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>Formulir Permohonan Informasi</h2>
      <p class="text-center">Sampaikan permohonan informasi dengan benar agar permintaan informasi dapat segera diproses.</p>
      <form action="submit_form.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="kategori-permohonan">Kategori Permohonan</label>
          <select class="form-control" id="kategori-permohonan" name="kategori_permohonan" required>
            <option value="" disabled selected>Pilih Kategori</option>
            <option value="perorangan">Perorangan</option>
            <option value="lembaga">Lembaga/Organisasi</option>
            <option value="mahasiswa">Mahasiswa/Pelajar</option>
          </select>
        </div>
        <div class="form-group">
          <label for="identitas">Nomor Identitas (KTP/No. Badan Hukum/Nomor Induk Mahasiswa)</label>
          <input type="text" class="form-control" id="identitas" name="identitas" placeholder="Nomor Identitas" required />
        </div>
        <div class="form-group">
          <label for="instansi">Instansi Yang Dituju</label>
          <select class="form-control" id="instansi" name="instansi" required>
            <option value="" disabled selected>Pilih Instansi</option>
            <option value="dinkominfo">Dinkominfo</option>
            <!-- Tambahkan opsi instansi lainnya di sini jika ada -->
          </select>
        </div>
        <div class="form-group">
          <label for="nama">Nama</label>
          <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required />
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required />
        </div>
        <div class="form-group">
          <label for="pekerjaan">Pekerjaan</label>
          <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan" required />
        </div>
        <div class="form-group">
          <label for="no-handphone">No. Handphone</label>
          <input type="text" class="form-control" id="no-handphone" name="no_handphone" placeholder="No. Handphone" required />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
        </div>
        <div class="form-group">
          <label for="rincian">Rincian Yang Dibutuhkan</label>
          <textarea class="form-control" id="rincian" name="rincian" rows="3" placeholder="Rincian Yang Dibutuhkan" required></textarea>
        </div>
        <div class="form-group">
          <label for="tujuan">Tujuan Pengajuan Informasi</label>
          <textarea class="form-control" id="tujuan" name="tujuan" rows="3" placeholder="Tujuan Pengajuan Informasi" required></textarea>
        </div>
        <div class="form-group">
          <label for="cara-memperoleh">Cara Memperoleh Informasi</label>
          <select class="form-control" id="cara-memperoleh" name="cara_memperoleh" required>
            <option value="" disabled selected>Pilih Cara Memperoleh</option>
            <option value="melihat">Melihat/Membaca/Mendengarkan</option>
            <option value="salinan">Mendapat Salinan Informasi Berupa Hard Copy/Soft Copy</option>
          </select>
        </div>
        <div class="form-group">
          <label for="cara-mendapatkan">Cara Mendapatkan Informasi</label>
          <select class="form-control" id="cara-mendapatkan" name="cara_mendapatkan" required>
            <option value="" disabled selected>Pilih Cara Mendapatkan</option>
            <option value="langsung">Mengambil Langsung</option>
            <option value="kurir">Kurir/Organisasi</option>
            <option value="Pos">Pos</option>
            <option value="Fax">Fax</option>
          </select>
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
  </body>
</html>
