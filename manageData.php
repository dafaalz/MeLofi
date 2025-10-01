<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles specific to this page */
        .manage-data-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .form-section {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        
        .form-section:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .form-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #111111;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 0.75rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #555555;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            background-color: #fafafa;
            color: #111111;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-size: 0.95rem;
        }
        
        input[type="text"]:focus,
        input[type="file"]:focus,
        select:focus,
        textarea:focus {
            border-color: #111111;
            box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
            font-family: "Helvetica Neue", sans-serif;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .manage-data-container {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        /* Loading state for album select */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        /* Success/error message styles */
        .message {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* File input styling */
        .file-input-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-container input[type="file"] {
            padding: 0.75rem 1rem;
            background: #fafafa;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            cursor: pointer;
        }
        
        .file-input-container::after {
            content: "Browse";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #f5f5f5;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #555555;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Kelola Data Musik</h1>
            <div class="header-actions">
                <a href="adminPage.php" class="button secondary">Kembali ke Dashboard</a>
                <a href="logout.php" class="button secondary">Log Out</a>
            </div>
        </div>
    </header>
    
    <main>
        <div class="manage-data-container">
            <section class="form-section">
                <h2>Tambah Artis</h2>
                <form action="upload_artis.php" method="post" enctype="multipart/form-data" id="artisForm">
                    <div class="form-group">
                        <label for="nama_artis">Nama Artis</label>
                        <input type="text" id="nama_artis" name="nama_artis" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="foto_profil">Foto Profil</label>
                        <div class="file-input-container">
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi_artis">Deskripsi</label>
                        <textarea id="deskripsi_artis" name="deskripsi" placeholder="Tambahkan deskripsi tentang artis..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <input type="submit" value="Tambah Artis" class="button primary">
                    </div>
                </form>
            </section>

            <section class="form-section">
                <h2>Tambah Album</h2>
                <form action="upload_album.php" method="post" enctype="multipart/form-data" id="albumForm">
                    <div class="form-group">
                        <label for="id_artis_album">Pilih Artis</label>
                        <select id="id_artis_album" name="id_artis" required>
                            <option value="">-- Pilih Artis --</option>
                            <?php while($ar = mysqli_fetch_assoc($artis)) { ?>
                                <option value="<?= $ar['id_artis'] ?>"><?= htmlspecialchars($ar['nama_artis']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama_album">Nama Album</label>
                        <input type="text" id="nama_album" name="nama_album" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cover_album">Cover Album</label>
                        <div class="file-input-container">
                            <input type="file" id="cover_album" name="cover_album" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi_album">Deskripsi</label>
                        <textarea id="deskripsi_album" name="deskripsi" placeholder="Tambahkan deskripsi tentang album..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <input type="submit" value="Tambah Album" class="button primary">
                    </div>
                </form>
            </section>

            <section class="form-section">
                <h2>Tambah Lagu</h2>
                <form action="upload_lagu.php" method="post" enctype="multipart/form-data" id="laguForm">
                    <div class="form-group">
                        <label for="selectArtis">Pilih Artis</label>
                        <select id="selectArtis" name="id_artis" required>
                            <option value="">-- Pilih Artis --</option>
                            <?php mysqli_data_seek($artis, 0); while($ar = mysqli_fetch_assoc($artis)) { ?>
                                <option value="<?= $ar['id_artis'] ?>"><?= htmlspecialchars($ar['nama_artis']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="selectAlbum">Pilih Album</label>
                        <select id="selectAlbum" name="id_album" required>
                            <option value="">-- Pilih Album --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="judul">Judul Lagu</label>
                        <input type="text" id="judul" name="judul" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="filename">File Lagu (.mp3)</label>
                        <div class="file-input-container">
                            <input type="file" id="filename" name="filename" accept="audio/mp3" required>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <input type="submit" value="Tambah Lagu" class="button primary">
                    </div>
                </form>
            </section>
        </div>
    </main>

    <script>
        // Load album berdasarkan artis
        document.getElementById('selectArtis').addEventListener('change', function() {
            const artisId = this.value;
            const albumSelect = document.getElementById('selectAlbum');
            
            if (!artisId) {
                albumSelect.innerHTML = '<option value="">-- Pilih Album --</option>';
                return;
            }
            
            albumSelect.classList.add('loading');
            albumSelect.innerHTML = '<option value="">Loading...</option>';
            
            fetch('get_album.php?id_artis=' + artisId)
                .then(response => response.json())
                .then(data => {
                    albumSelect.innerHTML = '<option value="">-- Pilih Album --</option>';
                    data.forEach(album => {
                        const opt = document.createElement('option');
                        opt.value = album.id_album;
                        opt.textContent = album.nama_album;
                        albumSelect.appendChild(opt);
                    });
                    albumSelect.classList.remove('loading');
                })
                .catch(error => {
                    console.error('Error:', error);
                    albumSelect.innerHTML = '<option value="">Error loading albums</option>';
                    albumSelect.classList.remove('loading');
                });
        });
        
        // Form validation and feedback
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                // Basic validation - you can enhance this further
                const requiredFields = form.querySelectorAll('[required]');
                let valid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.style.borderColor = '#d9534f';
                    } else {
                        field.style.borderColor = '#eaeaea';
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                }
            });
        });
        
        // File input feedback
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
                const container = this.closest('.file-input-container');
                
                // Update the pseudo-element text if needed
                if (container) {
                    container.setAttribute('data-file', fileName);
                }
            });
        });
    </script>
</body>
</html>