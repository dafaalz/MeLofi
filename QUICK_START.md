# Quick Start Guide - Melo-fi Music Store

## 🚀 5-Minute Setup

### Prerequisites
- MAMP/XAMPP/WAMP installed
- Web browser

### Step 1: Database Setup (2 minutes)
```bash
1. Start MAMP/XAMPP
2. Open phpMyAdmin (http://localhost:8888/phpMyAdmin)
3. Click "New" to create database
4. Name it: melofi
5. Click "Import" tab
6. Choose file: database_setup.sql
7. Click "Go"
```

### Step 2: Configure Connection (1 minute)
Edit `connect.php` if your settings differ:
```php
$hostname = "localhost:8889";  // Change port if needed
$username = "root";            // Your MySQL username
$password = "root";            // Your MySQL password
$database = "melofi";
```

### Step 3: Set Permissions (1 minute)
Make sure these folders exist and are writable:
```bash
chmod 755 songs/
chmod 755 cover/
chmod 755 artis/
```

### Step 4: Access Application (1 minute)
```
1. Open browser
2. Go to: http://localhost:8888/Project TA/
3. Login with: admin / admin123
```

## 🎯 First Steps After Login

### As User:
1. Click "Store" in navigation
2. Browse songs or use search
3. Click "Preview" to listen (30 seconds)
4. Click "Add to Cart"
5. Click cart icon (🛒) in header
6. Click "Checkout"
7. Select payment method
8. Click "Complete Purchase"
9. Go to "Library" to play your music

### As Admin:
1. Login with admin credentials
2. Click "Manage Data" button
3. Add Artist:
   - Fill form
   - Upload photo
   - Click submit
4. Add Album:
   - Select artist
   - Fill form
   - Upload cover
   - Click submit
5. Add Song:
   - Select artist & album
   - Fill form
   - Upload MP3
   - Click submit

## 📋 Feature Checklist

Test these features:

**User Features:**
- [ ] Register new account
- [ ] Login
- [ ] Global search (🔍 icon)
- [ ] Browse store with pagination
- [ ] Filter by artist/album
- [ ] Preview songs (30-second limit)
- [ ] Add to cart
- [ ] View cart
- [ ] Checkout and purchase
- [ ] View library
- [ ] Play music with full player
- [ ] Add to queue
- [ ] Shuffle queue

**Admin Features:**
- [ ] Login as admin
- [ ] Search songs in dashboard
- [ ] Filter by artist
- [ ] Navigate pages
- [ ] Click "Manage Data"
- [ ] Add new artist
- [ ] Add new album
- [ ] Add new song
- [ ] Edit existing records
- [ ] Delete records
- [ ] Preview songs

## 🐛 Troubleshooting

**Can't connect to database?**
- Check MySQL is running
- Verify port in connect.php
- Check username/password

**Files won't upload?**
- Check folder permissions
- Verify max_upload_size in php.ini
- Ensure folders exist

**Songs won't play?**
- Check audio file path
- Verify browser audio support
- Check file exists in songs/

**Cart not working?**
- Enable localStorage in browser
- Clear browser cache
- Check JavaScript console

## 📚 Documentation

- Full guide: README.md
- Database schema: DATABASE_SCHEMA.md
- All enhancements: ENHANCEMENTS_SUMMARY.md
- System diagrams: See artifacts in conversation

## 🎓 Key Concepts

### Database Relationships
```
artis (1) → (M) album → (M) lagu
users (1) → (M) transaksi ← (M) lagu
```

### User Flow
```
Register → Login → Browse → Preview → Cart → Checkout → Purchase → Library → Play
```

### Admin Flow
```
Login → Dashboard → Manage Data → Add/Edit/Delete → Preview → Done
```

## 💡 Pro Tips

1. **Search**: Type at least 2 characters
2. **Preview**: Limited to 30 seconds for unpurchased songs
3. **Cart**: Stored locally, persists across sessions
4. **Pagination**: Shows 12 items per page
5. **Filters**: Combine search + filter for best results
6. **Queue**: Add multiple songs for continuous playback
7. **Admin**: Delete artist deletes all albums and songs (CASCADE)

## 🔑 Default Accounts

**Admin:**
- Username: admin
- Password: admin123

**Test User:**
- Username: john_doe
- Password: password123

## 📞 Support

**Common Issues:**
1. Port conflicts: Change port in connect.php
2. Upload errors: Check php.ini settings
3. Permission errors: chmod 755 folders
4. Path issues: Verify Apache DocumentRoot

## ⚡ Quick Commands

**Clear cart (JavaScript console):**
```javascript
localStorage.removeItem('cart')
```

**Check cart contents:**
```javascript
console.log(localStorage.getItem('cart'))
```

**Reset database:**
```bash
mysql -u root -p melofi < database_setup.sql
```

## 🎉 You're Ready!

Your music store is now fully functional with:
✅ User authentication
✅ Shopping cart system
✅ Purchase flow
✅ Music player
✅ Admin management
✅ Search & filters
✅ Pagination
✅ Preview restrictions

**Enjoy your music store!** 🎵
