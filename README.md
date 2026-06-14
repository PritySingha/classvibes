# ClassVibes – Smart Classroom Management System

ClassVibes is a web-based classroom management system designed to digitize attendance and streamline academic administration using QR code technology.

---

## 🚀 Features

- QR Code–based Attendance System  
- Role-Based Authentication (Admin, Teacher, Student)  
- Course & Class Management  
- Attendance Reports & Analytics  
- Responsive UI (Bootstrap)  
- Light / Dark Theme Toggle  

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript, Bootstrap  
- **Backend:** PHP  
- **Database:** MySQL  
- **Server:** XAMPP (Apache + MySQL)  

---

## 📂 Project Structure
```
CLASSVIBES/
├── admin/ # Admin dashboard & management
├── teacher/ # Teacher modules & attendance
├── student/ # Student dashboard & QR scan
├── auth/ # Login, logout, register
├── actions/ # CRUD operations
├── includes/ # DB connection, auth, layout
├── assets/ # CSS, JS, images, uploads
├── database.sql # Database schema
└── index.php # Entry point
```
---

## ⚙️ Setup Instructions (XAMPP)

1. Install **XAMPP**

2. Copy the `CLASSVIBES` folder to:
    C:\xampp\htdocs\

3. Open **XAMPP Control Panel**
- Start **Apache**
- Start **MySQL**

4. Open phpMyAdmin:
    http://localhost/phpmyadmin 

5. Create a database named: classvibes

6. Import the database:
- Select `database.sql`

7. Configure database connection:
- File: `includes/db.php`

8. Run the project: http://localhost/CLASSVIBES

---

## 👥 User Roles

### Admin
- Manage users & classes
- Generate reports
- System configuration

### Teacher
- Start class sessions
- Generate QR codes
- View attendance reports

### Student
- Scan QR code
- View attendance history
- Manage profile

---

## 📈 Future Improvements

- Push Notifications
- Leave Request System
- Live Deployment
- Advanced Analytics

---

## 📚 What I Learned

- PHP–MySQL integration
- Session & authentication handling
- QR-based attendance logic
- Role-based access control
