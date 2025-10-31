-- ============================================
-- Melo-fi Music Store Database Setup
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS melofi;
USE melofi;

-- ============================================
-- Table: users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level_access ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: artis (Artists)
-- ============================================
CREATE TABLE IF NOT EXISTS artis (
    id_artis INT PRIMARY KEY AUTO_INCREMENT,
    nama_artis VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    foto_profil VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: album
-- ============================================
CREATE TABLE IF NOT EXISTS album (
    id_album INT PRIMARY KEY AUTO_INCREMENT,
    id_artis INT NOT NULL,
    nama_album VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    cover_album VARCHAR(255),
    release_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_artis) REFERENCES artis(id_artis) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: lagu (Songs)
-- ============================================
CREATE TABLE IF NOT EXISTS lagu (
    id_lagu INT PRIMARY KEY AUTO_INCREMENT,
    id_album INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    duration_seconds INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0.99,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_album) REFERENCES album(id_album) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: transaksi (Transactions)
-- ============================================
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lagu_id INT NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50),
    amount DECIMAL(10,2) DEFAULT 0.99,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (lagu_id) REFERENCES lagu(id_lagu) ON DELETE CASCADE,
    UNIQUE KEY unique_purchase (user_id, lagu_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Indexes for Performance
-- ============================================

-- Song searches
CREATE INDEX idx_song_title ON lagu(judul);
CREATE INDEX idx_song_album ON lagu(id_album);

-- Artist searches
CREATE INDEX idx_artist_name ON artis(nama_artis);

-- Album searches
CREATE INDEX idx_album_name ON album(nama_album);
CREATE INDEX idx_album_artist ON album(id_artis);

-- Transaction queries
CREATE INDEX idx_user_purchases ON transaksi(user_id, purchase_date);
CREATE INDEX idx_song_purchases ON transaksi(lagu_id);

-- ============================================
-- Sample Data
-- ============================================

-- Insert default users
INSERT INTO users (username, password, level_access) VALUES
('admin', MD5('admin123'), 'admin'),
('john_doe', MD5('password123'), 'user'),
('jane_smith', MD5('password123'), 'user');

-- Insert sample artists
INSERT INTO artis (nama_artis, deskripsi, foto_profil) VALUES
('Porter Robinson', 'American electronic music producer and DJ known for his emotive and melodic productions', 'artis/pfp-porter.png'),
('Hindia', 'Indonesian indie pop singer-songwriter with poetic lyrics and dreamy soundscapes', 'artis/Baskara_Hindia.jpeg'),
('Maliq & D Essentials', 'Indonesian jazz and soul band with smooth grooves and soulful vocals', 'artis/mnm.png');

-- Insert sample albums
INSERT INTO album (id_artis, nama_album, deskripsi, cover_album, release_date) VALUES
(1, 'Nurture', 'Second studio album by Porter Robinson featuring uplifting electronic melodies', 'cover/pfp-porter.png', '2021-04-23'),
(2, 'Menari Dengan Bayangan', 'Debut album by Hindia exploring themes of self-reflection and growth', 'cover/mdb.jpg', '2019-03-15'),
(3, 'Doves', 'Album by Maliq & D Essentials with smooth jazz and R&B fusion', 'cover/doves.png', '2015-06-12'),
(2, 'Lagipula Hidup Akan Berakhir', 'Second album exploring existential themes with indie pop sound', 'cover/LHAB.png', '2021-09-10');

-- Insert sample songs
INSERT INTO lagu (id_album, judul, filename, duration_seconds, price) VALUES
(1, 'Clarity (Porter Robinson Remix)', 'Zedd - Clarity (feat. Foxes) (Porter Robinson Remix) (Live).mp3', 312, 0.99),
(2, 'Secukupnya', 'Secukupnya [Db4cJuoltKA].mp3', 245, 0.99),
(2, 'Tarot', 'Tarot [iLOq3u5LH0U].mp3', 198, 0.99),
(3, 'Kita Ke Sana', 'Kita Ke Sana [tq3JwQiDmlM].mp3', 267, 0.99),
(4, 'Membangun dan Menghancurkan', 'menghancurkan [YesLQzmQr3g].mp3', 234, 0.99');

-- Insert sample transactions (user purchases)
INSERT INTO transaksi (user_id, lagu_id, payment_method, amount, status) VALUES
(2, 1, 'credit_card', 0.99, 'completed'),
(2, 2, 'paypal', 0.99, 'completed'),
(2, 4, 'credit_card', 0.99, 'completed'),
(3, 1, 'bank_transfer', 0.99, 'completed'),
(3, 3, 'credit_card', 0.99, 'completed');

-- ============================================
-- Views for Common Queries
-- ============================================

-- View: Complete song information with artist and album
CREATE OR REPLACE VIEW v_songs_full AS
SELECT 
    l.id_lagu,
    l.judul AS song_title,
    l.filename,
    l.duration_seconds,
    l.price,
    a.id_album,
    a.nama_album AS album_name,
    a.cover_album,
    ar.id_artis,
    ar.nama_artis AS artist_name,
    ar.foto_profil AS artist_photo
FROM lagu l
JOIN album a ON l.id_album = a.id_album
JOIN artis ar ON a.id_artis = ar.id_artis;

-- View: User purchase history
CREATE OR REPLACE VIEW v_user_purchases AS
SELECT 
    u.id_user,
    u.username,
    t.id_transaksi,
    t.purchase_date,
    t.payment_method,
    t.amount,
    l.judul AS song_title,
    ar.nama_artis AS artist_name,
    a.nama_album AS album_name
FROM transaksi t
JOIN users u ON t.user_id = u.id_user
JOIN lagu l ON t.lagu_id = l.id_lagu
JOIN album a ON l.id_album = a.id_album
JOIN artis ar ON a.id_artis = ar.id_artis;

-- ============================================
-- Stored Procedures
-- ============================================

-- Procedure: Get user's library
DELIMITER //
CREATE PROCEDURE sp_get_user_library(IN p_user_id INT)
BEGIN
    SELECT 
        l.id_lagu,
        l.judul,
        l.filename,
        l.duration_seconds,
        a.nama_album,
        a.cover_album,
        ar.nama_artis,
        ar.id_artis,
        a.id_album,
        t.purchase_date
    FROM transaksi t
    JOIN lagu l ON t.lagu_id = l.id_lagu
    JOIN album a ON l.id_album = a.id_album
    JOIN artis ar ON a.id_artis = ar.id_artis
    WHERE t.user_id = p_user_id
    ORDER BY t.purchase_date DESC;
END //
DELIMITER ;

-- Procedure: Get songs not owned by user (for store)
DELIMITER //
CREATE PROCEDURE sp_get_store_songs(IN p_user_id INT)
BEGIN
    SELECT 
        l.id_lagu,
        l.judul,
        l.filename,
        l.price,
        a.nama_album,
        a.cover_album,
        ar.nama_artis,
        ar.id_artis,
        a.id_album
    FROM lagu l
    JOIN album a ON l.id_album = a.id_album
    JOIN artis ar ON a.id_artis = ar.id_artis
    WHERE l.id_lagu NOT IN (
        SELECT lagu_id FROM transaksi WHERE user_id = p_user_id
    )
    ORDER BY l.id_lagu DESC;
END //
DELIMITER ;

-- ============================================
-- Triggers
-- ============================================

-- Trigger: Update album count when song is added
DELIMITER //
CREATE TRIGGER tr_after_song_insert
AFTER INSERT ON lagu
FOR EACH ROW
BEGIN
    -- You can add custom logic here
    -- For example, updating an album songs count field if you add one
    -- This is a placeholder for future enhancements
    -- SELECT 'Song added successfully';
END //
DELIMITER ;

-- ============================================
-- User Privileges (Optional)
-- ============================================

-- Create application user (recommended for production)
-- CREATE USER 'melofi_app'@'localhost' IDENTIFIED BY 'your_secure_password';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON melofi.* TO 'melofi_app'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================
-- Database Info
-- ============================================

SELECT 'Database setup complete!' AS status;
SELECT COUNT(*) AS user_count FROM users;
SELECT COUNT(*) AS artist_count FROM artis;
SELECT COUNT(*) AS album_count FROM album;
SELECT COUNT(*) AS song_count FROM lagu;
SELECT COUNT(*) AS transaction_count FROM transaksi;
