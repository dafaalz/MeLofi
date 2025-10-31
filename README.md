# Melo-fi Music Store 🎵

A comprehensive music store web application built with PHP, MySQL, HTML, CSS, and JavaScript.

## ✨ Features

### User Features
- **Authentication System**
  - User registration with auto-login
  - Secure login with role-based access (Admin/User)
  - Session management
  - Password hashing (MD5, upgradeable to bcrypt)

- **Global Search**
  - Search songs, artists, and albums
  - Real-time search results
  - Modal-based search interface
  - Click-through to detailed pages

- **Music Store**
  - Browse all available songs
  - Pagination (12 items per page)
  - Filter by artist or album
  - Search within store
  - 30-second preview for non-purchased songs
  - Preview timer with visual countdown
  - Price display for each song
  - Add songs to shopping cart

- **Shopping Cart System**
  - Persistent cart using localStorage
  - Visual cart counter in header
  - Add/remove items
  - Clear all items
  - View total price
  - Cart modal interface

- **Checkout Process**
  - Review order items
  - Check for already-owned songs
  - Order summary with totals
  - Payment method selection
  - Purchase confirmation
  - Automatic cart clearing after purchase

- **Music Library**
  - View all purchased songs
  - Full-featured music player
  - Album artwork display
  - Play/pause controls
  - Previous/next track navigation
  - Progress bar with time display
  - Volume control
  - 15-second skip forward/backward
  - Queue management system
  - Add songs to queue
  - Shuffle queue
  - Clear queue
  - Song recommendations (unpurchased songs)

- **Artist & Album Details**
  - Dedicated detail pages
  - View all songs/albums
  - Artist biography
  - Album information
  - Cover artwork display

### Admin Features
- **Admin Dashboard**
  - Overview of all songs
  - Search and filter functionality
  - Pagination
  - Quick audio preview
  - Edit/delete actions

- **Data Management**
  - Add/Edit/Delete Artists
  - Add/Edit/Delete Albums
  - Add/Edit/Delete Songs
  - Upload images (covers, profile photos)
  - Upload audio files (.mp3)
  - Dynamic album loading based on artist selection
  - Validation for all inputs
  - Data tables with pagination

## 🏗️ System Architecture

### Data Flow Diagram (DFD)
- **Level 0**: Context diagram showing external entities and system
- **Level 1**: Detailed processes including:
  - Authentication Process
  - Browse & Search Process
  - Shopping Cart Process
  - Purchase Process
  - Library & Playback Process
  - Admin Management Process

### Entity Relationship Diagram (ERD)
- **Users** (1:M) → **Transactions**
- **Artists** (1:M) → **Albums**
- **Albums** (1:M) → **Songs**
- **Songs** (1:M) → **Transactions**

### Flowcharts
1. **Login & Registration Flow**
2. **Browse & Search Songs Flow**
3. **Shopping Cart & Purchase Flow**
4. **Library & Music Playback Flow**
5. **Admin Data Management Flow**

## 📁 Project Structure

```
Project TA/
├── index.php              # Login/Registration page
├── login.php              # Authentication logic
├── logout.php             # Session destruction
├── header.php             # Global header with search & cart
├── sidebar.php            # Navigation sidebar
├── footer.php             # Global footer
├── style.css              # Global styles
│
├── connect.php            # Database connection
├── search.php             # Global search API
│
├── store.php              # Music store page
├── checkout.php           # Checkout page
├── process_purchase.php   # Purchase processing
├── buy.php                # Quick buy (legacy)
│
├── library.php            # User library & player
├── artisDetail.php        # Artist detail page
├── albumDetail.php        # Album detail page
│
├── adminPage.php          # Admin dashboard
├── manageData.php         # Data management page
├── upload_lagu.php        # Song upload handler
├── upload_artis.php       # Artist upload handler
├── upload_album.php       # Album upload handler
├── edit.php               # Edit song
├── editArtis.php          # Edit artist
├── editAlbum.php          # Edit album
├── edit_proses.php        # Edit processing
├── delete.php             # Delete song
├── deleteArtis.php        # Delete artist
├── deleteAlbum.php        # Delete album
├── get_album.php          # Get albums by artist (API)
│
├── songs/                 # Audio files directory
├── cover/                 # Album covers directory
├── artis/                 # Artist photos directory
│
├── DATABASE_SCHEMA.md     # Database documentation
├── README.md              # This file
│
└── Diagrams/              # System diagrams (in artifacts)
    ├── DFD Level 0
    ├── DFD Level 1
    ├── ERD
    └── Flowcharts (6 types)
```

## 🗄️ Database Schema

### Tables
1. **users** - User accounts (admin/user)
2. **artis** - Artist information
3. **album** - Album details
4. **lagu** - Song information
5. **transaksi** - Purchase transactions

See `DATABASE_SCHEMA.md` for complete schema details.

## 🚀 Installation

### Prerequisites
- MAMP/XAMPP/WAMP (PHP 7.4+ and MySQL 5.7+)
- Web browser (Chrome, Firefox, Safari, Edge)

### Setup Steps

1. **Clone or download the project**
   ```bash
   git clone [your-repo-url]
   cd "Project TA"
   ```

2. **Start MAMP/XAMPP**
   - Start Apache and MySQL servers
   - Default ports: Apache (80/8888), MySQL (3306/8889)

3. **Create Database**
   ```sql
   CREATE DATABASE melofi;
   ```

4. **Import Database Schema**
   - Open phpMyAdmin
   - Select `melofi` database
   - Run SQL from `DATABASE_SCHEMA.md`
   - Or import provided SQL file if available

5. **Configure Database Connection**
   Edit `connect.php` if needed:
   ```php
   $hostname = "localhost:8889"; // Your MySQL port
   $username = "root";
   $password = "root";
   $database = "melofi";
   ```

6. **Create Required Directories**
   ```bash
   mkdir -p songs cover artis
   ```

7. **Set Permissions** (for upload directories)
   ```bash
   chmod 755 songs cover artis
   ```

8. **Access Application**
   - Open browser
   - Navigate to `http://localhost:8888/Project TA/`
   - Or your configured MAMP/XAMPP URL

## 👤 Default Accounts

### Admin Account
- Username: `admin`
- Password: `admin123`

### Test User Account
- Username: `user`
- Password: `password123`

*Note: Create through registration for additional users*

## 💡 Usage Guide

### For Users

1. **Registration/Login**
   - Register new account or login
   - Automatically redirected to library

2. **Browse Store**
   - Click "Store" in navigation
   - Use search bar to find songs
   - Apply filters (artist, album)
   - Preview songs (30-second limit)
   - Add songs to cart

3. **Shopping Cart**
   - Click cart icon in header
   - Review items
   - Remove items if needed
   - Click "Checkout"

4. **Purchase**
   - Review order summary
   - Select payment method
   - Click "Complete Purchase"
   - Songs added to library

5. **Listen to Music**
   - Go to Library
   - Click play on any song
   - Use player controls
   - Manage queue
   - Explore recommendations

### For Administrators

1. **Login as Admin**
   - Use admin credentials
   - Access admin dashboard

2. **View All Songs**
   - Search and filter
   - Preview any song
   - Quick edit/delete

3. **Manage Data**
   - Click "Manage Data"
   - Add new artists, albums, songs
   - Edit existing records
   - Delete records (with cascade checks)
   - View data tables

4. **Upload Files**
   - Select appropriate form
   - Fill required fields
   - Choose files (images/audio)
   - Submit form

## 🎨 Key Features Explained

### Search Feature
- Global search accessible from header
- Searches across songs, artists, albums
- Real-time results
- Click to navigate to details

### Preview Restriction
- Non-purchased songs: 30-second preview only
- Visual timer countdown
- Automatic stop at 30 seconds
- Prompt to purchase

### Shopping Cart
- Client-side storage (localStorage)
- Persistent across sessions
- Visual counter badge
- Modal interface
- Price calculation

### Pagination
- 12 items per page
- Page navigation
- Results count display
- Maintains filters and search

### Filter System
- Filter by artist
- Filter by album
- Combine with search
- URL parameter preservation

### Music Player
- Full playback controls
- Queue management
- Shuffle functionality
- Progress tracking
- Volume control
- Auto-play next

## 🔒 Security Features

### Implemented
- Session-based authentication
- Role-based access control
- SQL input sanitization
- File upload validation
- Password hashing (MD5)

### Recommended Upgrades
- Use bcrypt instead of MD5
- Implement prepared statements
- Add CSRF tokens
- Enable HTTPS
- Input validation enhancement
- File type verification
- Rate limiting

## 📊 Database Relationships

```
artis (1) ──→ (M) album
album (1) ──→ (M) lagu
users (1) ──→ (M) transaksi
lagu (1) ──→ (M) transaksi
```

### Cascade Deletes
- Deleting artist → deletes albums → deletes songs
- Deleting user → deletes transactions
- Maintains referential integrity

## 🎯 Future Enhancements

- [ ] Playlists feature
- [ ] Social sharing
- [ ] User reviews/ratings
- [ ] Wishlist system
- [ ] Gift songs to other users
- [ ] Advanced analytics dashboard
- [ ] Email notifications
- [ ] Payment gateway integration
- [ ] Download purchased songs
- [ ] Mobile responsive improvements
- [ ] PWA capabilities
- [ ] Dark/light theme toggle
- [ ] Multi-language support
- [ ] Advanced search filters (genre, year, etc.)

## 🐛 Known Issues

1. MD5 password hashing (should upgrade to bcrypt)
2. Basic SQL injection protection (needs prepared statements)
3. File upload validation needs improvement
4. No CSRF protection
5. Session timeout not implemented

## 📝 Development Notes

### Technologies Used
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Audio**: HTML5 Audio API
- **Storage**: localStorage for cart

### Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Performance Considerations
- Pagination for large datasets
- Lazy loading images
- Audio streaming (not download)
- Indexed database queries
- Optimized CSS/JS

## 👥 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is for educational purposes.

## 📞 Support

For issues or questions:
- Create an issue in the repository
- Contact project maintainer

## 🙏 Acknowledgments

- AdminLTE for UI components
- Font Awesome for icons
- Google Fonts for typography
- Community contributors

---

**Version**: 2.0
**Last Updated**: October 2025
**Status**: Active Development
