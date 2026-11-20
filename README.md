# Simple Student Management System

A beginner-friendly **Student Management System** built with **Core PHP**, **MySQL** (using prepared statements), **HTML5**, **CSS3**, and **Bootstrap 5**.

---

## 📁 Project Folder Structure

```text
student_management_system/
│
├── database.sql           # Database schema & sample records
├── db.php                 # MySQL database connection (MySQLi)
│
├── index.php              # Dashboard (Metrics, Welcome banner, Recent list)
├── add_student.php        # Form & script to insert a new student
├── view_students.php      # Table view of all students with Edit/Delete actions
├── edit_student.php       # Form & script to update existing student record
├── delete_student.php     # Script to delete student record securely
│
├── includes/
│   ├── header.php         # Shared navbar & Bootstrap head assets
│   └── footer.php         # Shared footer & scripts
│
└── assets/
    └── css/
        └── style.css      # Custom styling tweaks
```

---

## 🗄️ Database Setup (`database.sql`)

1. Open your browser and go to **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click on the **Databases** tab and create a database named `student_db`.
3. Select `student_db` from the left sidebar and click on the **Import** tab.
4. Choose the `database.sql` file provided in this project and click **Go** (or **Import**).

*Alternatively, execute the following SQL code directly in the SQL tab:*

```sql
CREATE DATABASE IF NOT EXISTS `student_db`;
USE `student_db`;

CREATE TABLE IF NOT EXISTS `students` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `reg_no` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `year` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data
INSERT INTO `students` (`name`, `reg_no`, `email`, `phone`, `department`, `year`) VALUES
('John Doe', 'REG2024001', 'john.doe@example.com', '9876543210', 'Computer Science', '3rd Year'),
('Jane Smith', 'REG2024002', 'jane.smith@example.com', '9876543211', 'Information Technology', '2nd Year'),
('Michael Brown', 'REG2024003', 'michael.b@example.com', '9876543212', 'Mechanical Engineering', '4th Year');
```

---

## 🚀 How to Run the Project Using XAMPP

### Step 1: Install and Start XAMPP
1. Download and install [XAMPP](https://www.apachefriends.org/).
2. Open the **XAMPP Control Panel**.
3. Click the **Start** button for both **Apache** and **MySQL**. Both should display green status indicators.

### Step 2: Place the Project in `htdocs`
1. Navigate to your XAMPP installation directory:
   - **Windows:** `C:\xampp\htdocs\`
2. Create a folder named `student_management` inside `htdocs`:
   - `C:\xampp\htdocs\student_management\`
3. Copy all files from this project into that directory.

### Step 3: Configure Database Connection (If Required)
Open `db.php` in a code editor. The default settings match standard XAMPP configurations:
- **Host:** `localhost`
- **Username:** `root`
- **Password:** `""` (blank by default in XAMPP)
- **Database:** `student_db`

### Step 4: Run the Application
Open your web browser and navigate to:
```
http://localhost/student_management/
```
Or directly:
```
http://localhost/student_management/index.php
```

---

## ✨ Features Included

- **Dashboard:**
  - Dynamic total students count counter.
  - Quick action shortcuts to Add and View records.
  - Recent students table overview.
- **Add Student:**
  - Full validation (Email format check, duplicate Register Number check).
  - Secure insertion using MySQL prepared statements.
- **View Students:**
  - Clean responsive Bootstrap 5 data table with badges.
  - Direct Edit and Delete action buttons for each record.
- **Edit Student:**
  - Pre-populated form fields.
  - Unique Register Number check excluding the current record.
  - Safe prepared statement updates.
- **Delete Student:**
  - Client-side confirmation alert before deleting.
  - Server-side ID verification and prepared statement deletion.
- **Security:**
  - 100% SQL-injection safe using prepared statements (`$stmt->bind_param()`).
  - XSS protection via `htmlspecialchars()` on all rendered outputs.
