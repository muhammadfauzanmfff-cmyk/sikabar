<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

// Query untuk permohonan informasi
$query = "SELECT DISTINCT p.*, t.status, t.kode_unik, t.tanggal_update
          FROM informasi_permohonan p 
          LEFT JOIN tracking t ON (t.id_permohonan_ref = p.id_permohonan AND t.jenis_permohonan = 'informasi')
          ORDER BY p.created_at DESC";

$result = $conn->query($query);

if ($result === FALSE) {
    echo "Error pada query permohonan informasi: " . $conn->error;
}

// Query untuk permohonan keberatan
$keberatan_query = "SELECT k.*, t.status, t.kode_unik, t.tanggal_update,
                    t_info.kode_unik as kode_permohonan_awal
                    FROM keberatan_permohonan k
                    LEFT JOIN tracking t ON (t.id_permohonan_ref = k.id_keberatan AND t.jenis_permohonan = 'keberatan')
                    LEFT JOIN tracking t_info ON k.kode_permohonan = t_info.kode_unik
                    ORDER BY k.created_at DESC";
                    
$keberatan_result = $conn->query($keberatan_query);

if ($keberatan_result === FALSE) {
    echo "Error pada query permohonan keberatan: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/templatemo-scholar.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #ffff;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100%;
            background-color: #387f39;
            padding-top: 20px;
        }

        .sidebar h1 {
            padding: 5px 30px;
            text-decoration: none;
            font-size: 35px;
            color: white;
            display: block;
        }
        .sidebar a {
            padding: 15px 30px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #2e5d29;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            font-size: 20px;
            text-transform: capitalize;
            padding: 20px;
            background-color: #387f39;
            color: white;
            text-align: center;
        }

        .content {
            margin-top: 20px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .content h2 {
            margin-top: 0;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-check {
            position: absolute;
            opacity: 0;
        }

        .btn-outline-primary {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #387f39;
            background-color: transparent;
            border: 2px solid #387f39;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background-color: #387f39;
            color: white;
        }

        .btn-check:checked + .btn-outline-primary {
            background-color: #387f39;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        .btn-outline-primary:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .content-sections {
            display: none;
        }

        .content-sections.active {
            display: block;
        }

        .table-wrapper {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .status {
    text-align: center;
}

.status .status-indicator {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    margin: 5px;
}

.status .status-success {
    background-color: #28a745; /* Green */
}

.status .status-pending {
    background-color: #ffc107; /* Yellow */
    color: #212529; /* Dark Text */
}

.status .status-failure {
    background-color: #dc3545; /* Red */
}

.form-group {
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

form select {
    border: 2px solid #ced4da;
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 14px;
    background-color: white;
    cursor: pointer;
    transition: border-color 0.3s;
}

form select:focus {
    border-color: #387f39;
    outline: none;
}

.pagination {
    text-align: center;
}

.pagination button {
    background-color: #387f39;
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.pagination button:hover {
    background-color: #2e5d29;
}

.pagination button.active {
    background-color: #2e5d29;
}
.details-content {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-top: 10px;
}

.status-select {
    width: 120px;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin-right: 10px;
}

.btn-save-status {
    padding: 8px 15px;
    background-color: #387f39;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: none;
}

.btn-save-status:hover {
    background-color: #2e5d29;
}

.status-indicator {
    margin-top: 5px;
    font-size: 14px;
}

.berkas-link {
    color: #387f39;
    text-decoration: none;
}

.berkas-link:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>SiKaBar</h1>
        <a href="#dashboard">Dashboard</a>
        <a href="#users">Users</a>
        <a href="#settings">Settings</a>
        <a href="#reports">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Admin SiKaBar</h1>
        </div>

        <div class="content">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="btnradio1">Permintaan Informasi</label>
              
                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Layanan Difabel</label>
              
                <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio3">Permohonan Keberatan</label>
            </div>

            <div class="content-sections active" id="info-content">
                <h2>Permintaan Informasi</h2>
                <div class="table-wrapper">
                    <table id="permohonan">
                        <thead>
                            <tr>
                                <th>Kode Tracking</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr data-id="' . $row['id_permohonan'] . '">';
                                    // Kolom Kode Tracking
                                    echo '<td>' . htmlspecialchars($row['kode_unik'] ?? 'N/A') . '</td>';
                                    
                                    // Kolom Nama dengan Details
                                    echo '<td>';
                                    echo '<details>';
                                    echo '<summary>' . htmlspecialchars($row['nama']) . '</summary>';
                                    echo '<div class="details-content">';
                                    echo '<p>Kategori Permohonan: ' . htmlspecialchars($row['kategori_permohonan']) . '</p>';
                                    echo '<p>Nomor Identitas: ' . htmlspecialchars($row['identitas']) . '</p>';
                                    echo '<p>Instansi: ' . htmlspecialchars($row['instansi']) . '</p>';
                                    echo '<p>Alamat: ' . htmlspecialchars($row['alamat']) . '</p>';
                                    echo '<p>Email: ' . htmlspecialchars($row['email']) . '</p>';
                                    echo '<p>No. HP: ' . htmlspecialchars($row['no_handphone']) . '</p>';
                                    echo '<p>Pekerjaan: ' . htmlspecialchars($row['pekerjaan']) . '</p>';
                                    echo '<p>Rincian: ' . htmlspecialchars($row['rincian']) . '</p>';
                                    echo '<p>Tujuan: ' . htmlspecialchars($row['tujuan']) . '</p>';
                                    echo '<p>Cara Memperoleh: ' . htmlspecialchars($row['cara_memperoleh']) . '</p>';
                                    echo '<p>Cara Mendapatkan: ' . htmlspecialchars($row['cara_mendapatkan']) . '</p>';
                                    if ($row['berkas']) {
                                        echo '<p>Berkas: <a href="uploads/informasi/' . htmlspecialchars($row['berkas']) . '" target="_blank" class="berkas-link">Lihat Berkas</a></p>';
                                    }
                                    echo '</div>';
                                    echo '</details>';
                                    echo '</td>';
                                    
                                    // Kolom Status dengan Dropdown
                                    echo '<td class="status">';
                                    echo '<select class="status-select" data-id="' . $row['id_permohonan'] . '" data-original-value="' . htmlspecialchars($row['status'] ?? 'Menunggu') . '">';
                                    $status_options = ['Menunggu', 'Diproses', 'Diterima', 'Ditolak'];
                                    foreach ($status_options as $option) {
                                        $selected = ($row['status'] === $option) ? 'selected' : '';
                                        echo "<option value=\"$option\" $selected>$option</option>";
                                    }
                                    echo '</select>';
                                    echo '<button class="btn-save-status" style="display:none;">Simpan</button>';
                                    echo '<div class="status-indicator"></div>';
                                    echo '</td>';
                                    
                                    // Kolom Tanggal
                                    echo '<td>' . date('d/m/Y', strtotime($row['created_at'])) . '</td>';
                                    
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">Tidak ada data permohonan</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="content-sections" id="objection-content">
                <h2>Permohonan Keberatan</h2>
                <div class="table-wrapper">
                    <table id="permohonan-keberatan">
                        <thead>
                            <tr>
                                <th>Kode Tracking</th>
                                <th>Kode Permohonan</th>
                                <th>Detail</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($keberatan_result && $keberatan_result->num_rows > 0) {
                                while ($row = $keberatan_result->fetch_assoc()) {
                                    echo '<tr data-id="' . $row['id_keberatan'] . '">';
                                    echo '<td>' . htmlspecialchars($row['kode_unik'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($row['kode_permohonan']) . '</td>';
                                    
                                    // Detail column
                                    echo '<td>';
                                    echo '<details>';
                                    echo '<summary>Lihat Detail</summary>';
                                    echo '<div class="details-content">';
                                    echo '<p><strong>Identitas:</strong> ' . htmlspecialchars($row['identitas']) . '</p>';
                                    if ($row['sertakan_kuasa']) {
                                        echo '<p><strong>Nama Kuasa:</strong> ' . htmlspecialchars($row['nama_kuasa']) . '</p>';
                                        echo '<p><strong>Alamat Kuasa:</strong> ' . htmlspecialchars($row['alamat_kuasa']) . '</p>';
                                        echo '<p><strong>No. HP Kuasa:</strong> ' . htmlspecialchars($row['no_hp']) . '</p>';
                                    }
                                    echo '<p><strong>Alasan:</strong> ' . htmlspecialchars($row['alasan']) . '</p>';
                                    echo '<p><strong>Keterangan:</strong> ' . nl2br(htmlspecialchars($row['keterangan'])) . '</p>';
                                    if ($row['berkas']) {
                                        echo '<p><strong>Berkas:</strong> <a href="uploads/keberatan/' . htmlspecialchars($row['berkas']) . '" target="_blank" class="berkas-link">Lihat Berkas</a></p>';
                                    }
                                    echo '</div>';
                                    echo '</details>';
                                    echo '</td>';
                                    
                                    // Status column
                                    echo '<td class="status">';
                                    echo '<select class="status-select" data-id="' . $row['id_keberatan'] . '" data-original-value="' . htmlspecialchars($row['status'] ?? 'Menunggu') . '" data-type="keberatan">';
                                    $status_options = ['Menunggu', 'Diproses', 'Diterima', 'Ditolak'];
                                    foreach ($status_options as $option) {
                                        $selected = ($row['status'] === $option) ? 'selected' : '';
                                        echo "<option value=\"$option\" $selected>$option</option>";
                                    }
                                    echo '</select>';
                                    echo '<button class="btn-save-status" style="display:none;">Simpan</button>';
                                    echo '<div class="status-indicator"></div>';
                                    echo '</td>';
                                    
                                    // Date column
                                    echo '<td>' . date('d/m/Y', strtotime($row['created_at'])) . '</td>';
                                    
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5">Tidak ada data permohonan keberatan</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const btnradio1 = document.getElementById('btnradio1');
        const btnradio2 = document.getElementById('btnradio2');
        const btnradio3 = document.getElementById('btnradio3');

        const infoContent = document.getElementById('info-content');
        const disabilityContent = document.getElementById('disability-content');
        const objectionContent = document.getElementById('objection-content');

        function updateContent() {
            infoContent.classList.toggle('active', btnradio1.checked);
            disabilityContent.classList.toggle('active', btnradio2.checked);
            objectionContent.classList.toggle('active', btnradio3.checked);
        }

        btnradio1.addEventListener('change', updateContent);
        btnradio2.addEventListener('change', updateContent);
        btnradio3.addEventListener('change', updateContent);

        // Initialize content visibility
        updateContent();
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(select => {
        // Store the original value
        const originalValue = select.getAttribute('data-original-value');
        select.setAttribute('data-original-value', originalValue);
        
        select.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const newStatus = this.value;
            const tr = this.closest('tr');
            const saveBtn = tr.querySelector('.btn-save-status');
            const statusIndicator = tr.querySelector('.status-indicator');
            
            if (newStatus !== originalValue) {
                saveBtn.style.display = 'inline-block';
                
                saveBtn.onclick = function() {
                    this.disabled = true;
                    this.textContent = 'Menyimpan...';
                    select.disabled = true;
                    
                    fetch('update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id_permohonan=${id}&status=${newStatus}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statusIndicator.textContent = '✓ Status berhasil diperbarui';
                            statusIndicator.style.color = '#28a745';
                            saveBtn.style.display = 'none';
                            select.setAttribute('data-original-value', newStatus);
                            
                            // Refresh halaman setelah delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Gagal mengupdate status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        statusIndicator.textContent = '❌ ' + error.message;
                        statusIndicator.style.color = '#dc3545';
                        select.value = originalValue;
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.textContent = 'Simpan';
                        select.disabled = false;
                    });
                };
            } else {
                saveBtn.style.display = 'none';
                statusIndicator.textContent = '';
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(select => {
        const originalValue = select.getAttribute('data-original-value');
        const type = select.getAttribute('data-type'); // 'keberatan' or undefined for regular
        
        select.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const newStatus = this.value;
            const tr = this.closest('tr');
            const saveBtn = tr.querySelector('.btn-save-status');
            const statusIndicator = tr.querySelector('.status-indicator');
            
            if (newStatus !== originalValue) {
                saveBtn.style.display = 'inline-block';
                
                saveBtn.onclick = function() {
                    this.disabled = true;
                    this.textContent = 'Menyimpan...';
                    select.disabled = true;
                    
                    // Determine which endpoint to use
                    const endpoint = type === 'keberatan' ? 'update_status_keberatan.php' : 'update_status.php';
                    const idParam = type === 'keberatan' ? 'id_keberatan' : 'id_permohonan';
                    
                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `${idParam}=${id}&status=${newStatus}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statusIndicator.textContent = '✓ Status berhasil diperbarui';
                            statusIndicator.style.color = '#28a745';
                            saveBtn.style.display = 'none';
                            select.setAttribute('data-original-value', newStatus);
                            
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Gagal mengupdate status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        statusIndicator.textContent = '❌ ' + error.message;
                        statusIndicator.style.color = '#dc3545';
                        select.value = originalValue;
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.textContent = 'Simpan';
                        select.disabled = false;
                    });
                };
            } else {
                saveBtn.style.display = 'none';
                statusIndicator.textContent = '';
            }
        });
    });
    
    // Add click handlers for tab buttons
    document.querySelectorAll('.btn-check').forEach(btn => {
        btn.addEventListener('change', function() {
            const contentSections = document.querySelectorAll('.content-sections');
            contentSections.forEach(section => section.classList.remove('active'));
            
            if (this.id === 'btnradio1') {
                document.getElementById('info-content').classList.add('active');
            } else if (this.id === 'btnradio2') {
                document.getElementById('disability-content').classList.add('active');
            } else if (this.id === 'btnradio3') {
                document.getElementById('objection-content').classList.add('active');
            }
        });
    });
});

// Add success animation styles
const style = document.createElement('style');
style.textContent = `
    .status-indicator {
        transition: all 0.3s ease;
    }
    
    .status-select {
        transition: all 0.3s ease;
    }
    
    .btn-save-status {
        transition: all 0.3s ease;
    }
    
    @keyframes fadeInOut {
        0% { opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; }
    }
    
    .status-message {
        animation: fadeInOut 2s ease-in-out;
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>
