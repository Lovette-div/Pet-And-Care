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

--comments table
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tip_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (tip_id) REFERENCES care_tips(tip_id) ON DELETE CASCADE
);


--Care tips table
CREATE TABLE care_tips (
    tip_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO care_tips (user_id, title, content)
VALUES 
(4, 'How to Groom Your Dog', 'Regular grooming is essential to keep your dog healthy and clean. Brush their coat daily and bathe them monthly.'),
(4, 'Feeding Your Cat', 'Cats need high-protein food for energy. Provide fresh water daily and avoid overfeeding.'),
(4, 'Caring for Parrots', 'Parrots need mental stimulation. Provide toys and spend time socializing with them daily.'),
(5, 'Ferret Care Basics', 'Ferrets are curious pets. Keep them in a large cage and ensure they have ample playtime outside the cage.'),
(5, 'Preventing Pet Obesity', 'Maintain a healthy diet and exercise routine for your pets to prevent obesity-related health problems.');

