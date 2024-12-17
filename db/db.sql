CREATE DATABASE petandcare;

USE petandcare;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(100) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pets Table
CREATE TABLE pets (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT,
    size ENUM('small', 'medium', 'large'),
    description TEXT,
    image_url VARCHAR(255),
    adoption_status ENUM('available', 'adopted') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

-- Adoption Applications Table
CREATE TABLE adoption_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pet_id INT NOT NULL,
    application_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE
);
