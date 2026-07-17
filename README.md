# 🎒 UiTEMU - UiTM Lost & Found Management System

> A web-based Lost & Found Management System developed for **UiTM Puncak Perdana**. UiTEMU provides an easy and centralized platform for students to report lost or found items, submit claims, and manage item handovers efficiently.

---

## 📖 Table of Contents

- Features
- Technologies Used
- System Modules
- User Roles
- Requirements
- Installation
- Database Setup
- Test Accounts
- Usage Guide
- Workflow
- Project Structure
- Team Members
- License

---

# ✨ Features

### 👤 Authentication
- User Registration
- User Login
- Role-Based Access Control (Admin & Student)
- Secure Password Authentication

### 📦 Lost & Found Items
- Report Lost Item
- Report Found Item
- Upload Item Images
- Search & Filter Items
- View Item Details

### 📋 Claims Management
- Submit Item Claims
- Claim Status Tracking
- Handover Confirmation
- Admin Approval & Rejection

### 🏆 Certificate Management
- Automatic Certificate Generation
- Download Certificate as PDF

### 📊 Dashboard
#### Student Dashboard
- My Lost Reports
- My Found Reports
- My Claims
- My Certificates
- Activity Charts

#### Admin Dashboard
- Total Users
- Lost Items
- Found Items
- Claims Overview
- Pending Claims
- Monthly Reports
- Top Helpful Users

### 👥 User Management (Admin)
- View Users
- Edit Users
- Delete Users
- Manage User Roles

### 📈 Reports & Analytics
- System Overview
- Monthly Report Summary
- Activity Charts
- Download Monthly Reports

---

# 🛠 Technologies Used

- CakePHP 5
- PHP 8.3
- MySQL
- Bootstrap 5
- HTML5
- CSS3
- JavaScript
- Laragon
- Composer
- Visual Studio Code

---

# 📂 System Modules

- Authentication
- Dashboard
- Lost & Found Items
- Claims
- Certificates
- User Management (Admin)
- Reports & Analytics

---

# 👥 User Roles

## 👨‍🎓 Student

Students can:

- Register Account
- Login
- Report Lost Items
- Report Found Items
- Browse All Items
- Submit Claims
- Track Claim Status
- View Certificates
- Download Certificates

---

## 👨‍💼 Administrator

Administrators can:

- Manage Users
- Manage Lost Items
- Manage Found Items
- Manage Claims
- Approve or Reject Claims
- Generate Certificates
- View Dashboard Statistics
- Download Monthly Reports

---

# 💻 Requirements

- PHP 8.3+
- MySQL 8+
- Composer
- CakePHP 5
- Laragon (Recommended)
- Visual Studio Code

---

# ⚙ Installation

## Step 1

Clone the repository

```bash
git clone https://github.com/yourusername/uitemu.git
```

---

## Step 2

Move into the project folder

```bash
cd uitemu
```

---

## Step 3

Install dependencies

```bash
composer install
```

---

## Step 4

Copy environment file

```bash
cp config/.env.example config/.env
```

Update the database configuration.

---

## Step 5

Run database migrations

```bash
bin/cake migrations migrate
```

---

## Step 6

Start the server

```bash
bin/cake server
```

Open:

```
http://localhost:8765
```

---

# 🗄 Database Setup

1. Create a MySQL database

```
uitemu
```

2. Update

```
config/app_local.php
```

3. Run

```bash
bin/cake migrations migrate
```

---

# 🔑 Test Accounts

## Administrator

Email

```
admin@uitemu.com
```

Password

```
admin123
```

---

## Student

Email

```
student@uitemu.com
```

Password

```
student123
```

---

# 📚 Usage Guide

## Students

1. Register an account
2. Login
3. Report lost item
4. Report found item
5. Browse available items
6. Submit a claim
7. Track claim status
8. Download certificate after successful claim

---

## Administrators

1. Login as Admin
2. Manage Users
3. Manage Lost & Found Items
4. Review Claims
5. Approve / Reject Claims
6. Generate Certificates
7. Download Monthly Reports

---

# 🔄 Workflow

Student

```
Register
      ↓
Login
      ↓
Report Lost / Found Item
      ↓
Browse Items
      ↓
Submit Claim
      ↓
Admin Review
      ↓
Claim Approved
      ↓
Certificate Generated
```

---

# 📁 Project Structure

```
uitemu/
│
├── config/
├── plugins/
├── src/
│   ├── Controller/
│   ├── Model/
│   ├── Template/
│   └── View/
│
├── templates/
├── webroot/
├── tests/
├── vendor/
├── composer.json
└── README.md
```

---

# 👨‍👩‍👧‍👦 Team Members

| Name | Role |
|------|------|
| Adani Sofia binti Mohd Asri | Project Leader |
| Farisha Nadzirah binti Ahmad Afandy | System Developer |
| Nur Zafirah binti Aminuldin | Database Developer |
| Nurin Irdina binti Abdullah | Programmer Developer |

---

# 📄 License

This project was developed as part of the **IMS566 – Web-Based Information Systems** course at **Universiti Teknologi MARA (UiTM)**.

**Faculty of Information Management**

UiTM Puncak Perdana

2026
