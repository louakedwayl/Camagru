USE camagru_db;

CREATE TABLE users 
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL UNIQUE,
    full_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    validated BOOLEAN NOT NULL DEFAULT FALSE,
    avatar_path VARCHAR(255) DEFAULT NULL,
    validation_code VARCHAR(6) DEFAULT NULL,
    validation_code_expires_at TIMESTAMP DEFAULT NULL,
    reset_code VARCHAR(6) DEFAULT NULL,
    reset_code_expires_at TIMESTAMP DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO users (username, full_name, email, password, validated)
VALUES ('Wayl', 'Wayl Louaked','louakedwayl@protonmail.com', 'password', TRUE);