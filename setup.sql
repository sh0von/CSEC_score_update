-- Create the 'users' table for user registration
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cuet_id VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create the 'submissions' table to store user submissions
CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(255) NOT NULL,
    event VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    link VARCHAR(255) NOT NULL,
    screenshots TEXT NOT NULL,
    added_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the 'admin' table for administrator login
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
