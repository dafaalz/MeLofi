# Database Schema - Melo-fi Music Store

## Database Tables

### 1. users
Stores user account information including admins and regular users.

```sql
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level_access ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Fields:**
- `id_user`: Primary key, auto-increment
- `username`: Unique username for login
- `password`: Hashed password (MD5, should upgrade to bcrypt)
- `level_access`: User role (admin or user)
- `created_at`: Account creation timestamp

---

### 2. artis (Artists)
Stores artist/performer information.

```sql
CREATE TABLE artis (
    id_artis INT PRIMARY KEY AUTO_INCREMENT,
    nama_artis VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    foto_profil VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Fields:**
- `id_artis`: Primary key, auto-increment
- `nama_artis`: Artist name
- `deskripsi`: Artist biography/description
- `foto_profil`: Path to profile photo file
- `created_at`: Record creation timestamp

---

### 3. album
Stores album information, linked to artists.

```sql
CREATE TABLE album (
    id_album INT PRIMARY KEY AUTO_INCREMENT,
    id_artis INT NOT NULL,
    nama_album VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    cover_album VARCHAR(255),
    release_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_artis) REFERENCES artis(id_artis) ON DELETE CASCADE
);
```

**Fields:**
- `id_album`: Primary key, auto-increment
- `id_artis`: Foreign key to artis table
- `nama_album`: Album title
- `deskripsi`: Album description
- `cover_album`: Path to album cover image
- `release_date`: Album release date
- `created_at`: Record creation timestamp

---

### 4. lagu (Songs)
Stores individual song information, linked to albums.

```sql
CREATE TABLE lagu (
    id_lagu INT PRIMARY KEY AUTO_INCREMENT,
    id_album INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    duration_seconds INT,
    price DECIMAL(10,2) DEFAULT 0.99,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_album) REFERENCES album(id_album) ON DELETE CASCADE
);
```

**Fields:**
- `id_lagu`: Primary key, auto-increment
- `id_album`: Foreign key to album table
- `judul`: Song title
- `filename`: Audio file name (stored in songs/ directory)
- `duration_seconds`: Song length in seconds
- `price`: Song price in USD
- `created_at`: Record creation timestamp

---

### 5. transaksi (Transactions)
Stores purchase transactions linking users to purchased songs.

```sql
CREATE TABLE transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lagu_id INT NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50),
    amount DECIMAL(10,2),
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (lagu_id) REFERENCES lagu(id_lagu) ON DELETE CASCADE,
    UNIQUE KEY unique_purchase (user_id, lagu_id)
);
```

**Fields:**
- `id_transaksi`: Primary key, auto-increment
- `user_id`: Foreign key to users table
- `lagu_id`: Foreign key to lagu table
- `purchase_date`: Transaction timestamp
- `payment_method`: Payment type (credit_card, paypal, bank_transfer)
- `amount`: Transaction amount
- `status`: Transaction status
- `unique_purchase`: Ensures user can't buy same song twice

---

## Relationships

### One-to-Many Relationships

1. **artis → album**
   - One artist can have many albums
   - `album.id_artis` references `artis.id_artis`
   - CASCADE DELETE: Deleting artist deletes all albums

2. **album → lagu**
   - One album can contain many songs
   - `lagu.id_album` references `album.id_album`
   - CASCADE DELETE: Deleting album deletes all songs

3. **users → transaksi**
   - One user can have many purchases
   - `transaksi.user_id` references `users.id_user`
   - CASCADE DELETE: Deleting user deletes purchase history

4. **lagu → transaksi**
   - One song can be purchased by many users
   - `transaksi.lagu_id` references `lagu.id_lagu`
   - CASCADE DELETE: Deleting song removes from transactions

---

## Sample Data Insertion

```sql
-- Insert Users
INSERT INTO users (username, password, level_access) VALUES
('admin', MD5('admin123'), 'admin'),
('john_doe', MD5('password123'), 'user'),
('jane_smith', MD5('password123'), 'user');

-- Insert Artists
INSERT INTO artis (nama_artis, deskripsi, foto_profil) VALUES
('Porter Robinson', 'American electronic music producer and DJ', 'artis/pfp-porter.png'),
('Hindia', 'Indonesian indie pop singer-songwriter', 'artis/Baskara_Hindia.jpeg'),
('Maliq & D Essentials', 'Indonesian jazz and soul band', 'artis/mnm.png');

-- Insert Albums
INSERT INTO album (id_artis, nama_album, deskripsi, cover_album, release_date) VALUES
(1, 'Nurture', 'Second studio album by Porter Robinson', 'cover/pfp-porter.png', '2021-04-23'),
(2, 'Menari Dengan Bayangan', 'Debut album by Hindia', 'cover/mdb.jpg', '2019-03-15'),
(3, 'Doves', 'Album by Maliq & D Essentials', 'cover/doves.png', '2015-06-12');

-- Insert Songs
INSERT INTO lagu (id_album, judul, filename, duration_seconds, price) VALUES
(1, 'Clarity (Porter Robinson Remix)', 'Zedd - Clarity (feat. Foxes) (Porter Robinson Remix) (Live).mp3', 312, 0.99),
(2, 'Secukupnya', 'Secukupnya [Db4cJuoltKA].mp3', 245, 0.99),
(2, 'Tarot', 'Tarot [iLOq3u5LH0U].mp3', 198, 0.99),
(3, 'Kita Ke Sana', 'Kita Ke Sana [tq3JwQiDmlM].mp3', 267, 0.99');

-- Insert Transactions
INSERT INTO transaksi (user_id, lagu_id, payment_method, amount) VALUES
(2, 1, 'credit_card', 0.99),
(2, 2, 'paypal', 0.99),
(3, 1, 'bank_transfer', 0.99');
```

---

## Indexes for Performance

```sql
-- Index for faster searches
CREATE INDEX idx_song_title ON lagu(judul);
CREATE INDEX idx_artist_name ON artis(nama_artis);
CREATE INDEX idx_album_name ON album(nama_album);

-- Index for user purchases
CREATE INDEX idx_user_purchases ON transaksi(user_id, purchase_date);
CREATE INDEX idx_song_purchases ON transaksi(lagu_id);
```

---

## Database Connection Configuration

**File: connect.php**
```php
<?php
$hostname = "localhost:8889";
$username = "root";
$password = "root";
$database = "melofi";

$connect = mysqli_connect($hostname, $username, $password, $database);
if(!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

---

## Security Recommendations

1. **Password Hashing**: Upgrade from MD5 to bcrypt
   ```php
   // Registration
   $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
   
   // Login verification
   if (password_verify($_POST['password'], $row['password'])) {
       // Login successful
   }
   ```

2. **SQL Injection Prevention**: Use prepared statements
   ```php
   $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
   $stmt->bind_param("s", $username);
   $stmt->execute();
   ```

3. **Session Security**:
   - Set httponly and secure flags on session cookies
   - Regenerate session ID after login
   - Implement session timeout

4. **File Upload Validation**:
   - Validate file types and sizes
   - Sanitize file names
   - Store uploaded files outside web root

---

## Backup Strategy

1. **Daily Backups**:
   ```bash
   mysqldump -u root -p melofi > backup_$(date +%Y%m%d).sql
   ```

2. **Restore from Backup**:
   ```bash
   mysql -u root -p melofi < backup_20250101.sql
   ```
