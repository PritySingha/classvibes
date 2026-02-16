-- Users Table
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'teacher', 'student') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Classes Table
CREATE TABLE classes (
  class_id VARCHAR(20) PRIMARY KEY,
  teacher_id INT NOT NULL,
  class_name VARCHAR(100) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (teacher_id) REFERENCES users(id)
);

-- Attendance Table
CREATE TABLE attendance (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  class_id VARCHAR(20) NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES users(id),
  FOREIGN KEY (class_id) REFERENCES classes(class_id)
);
-- class_students table
CREATE TABLE class_students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    class_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);


-- Settings Table
CREATE TABLE settings (
    name VARCHAR(50) PRIMARY KEY,
    value TEXT NOT NULL,
    type ENUM('text', 'number', 'boolean', 'email') NOT NULL,
    description TEXT
);

-- Initial Settings
INSERT INTO settings (name, value, type, description) VALUES
('site_title', 'ClassVibes', 'text', 'Website title displayed in header'),
('default_user_role', 'student', 'text', 'Default role for new registrations'),
('attendance_threshold', '75', 'number', 'Minimum attendance percentage required'),
('email_notifications', '1', 'boolean', 'Enable email notifications');