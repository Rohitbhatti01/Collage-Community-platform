# 🎓 College Community Platform - Laravel + Vue 3

A secure, closed community platform to connect current students, alumni, and fresh graduates. Built with **Laravel (API backend)** and **Vue 3 (frontend)** using a **MySQL** database.

---

## 🌟 Key Features

* 🔐 Admin-controlled user approval before login
* 👤 User profiles with batch, course, skills, and social links
* 💬 Direct chat system with connection requests
* 📢 Post system for jobs, guidance, and questions
* 🛠 Built using Laravel 11 (Sanctum Auth) & Vue 3 + Vite

---

## 📁 Project Structure

```
backend/               # Laravel API backend
frontend/              # Vue 3 + Vite SPA frontend
```

---

## ⚙️ Tech Stack

* **Backend**: Laravel 11, Sanctum, MySQL
* **Frontend**: Vue 3, Pinia, Vue Router, Axios
* **Auth**: Email/password with admin approval

---

## 🚀 Installation

### 🧪 Requirements

* PHP 8.2+
* Composer
* Node.js 18+
* MySQL 8+

### 🧩 Backend Setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### 🌐 Frontend Setup

```bash
cd frontend
npm install
npm run dev
```

---

## ✅ Admin Login

Default admin user (created by seeder):

```
Email: admin@platform.com
Password: admin123
```

---

## 🔄 API Flow Summary

### 1. User Registration (Pending by Default)

* POST `/api/register`
* Fields: name, email, password, college\_id
* Status: `pending`

### 2. Admin Approval

* GET `/api/admin/pending-users`
* POST `/api/admin/approve/{id}` or `/reject/{id}`

### 3. User Login

* POST `/api/login`
* Allowed only if `status == approved`

---

## 📬 Messaging Flow

* Users can send connection requests
* Chat allowed after accepted
* Stored in `messages` table

---

## 📢 Post System

* Users can create job/guidance/questions posts
* Like, comment, and reply features

---

## 🔐 Security

* Laravel Sanctum tokens
* Middleware-based route protection
* Admin routes protected by role guard

---

## 🤝 Contributions

PRs are welcome. Fork the repo, make changes, and submit a pull request.

---

## 📃 License

[MIT License](LICENSE)
