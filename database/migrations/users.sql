    -- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi
CREATE Table users(
    id int PRIMARY KEY AUTO_INCREMENT,
    fname VARCHAR(255) NULL,
    lname VARCHAR(255) NULL,
    image_path VARCHAR(255) NULL,
    birthdate DATE NULL,
    nationalcode INT UNIQUE NULL,
    phone VARCHAR(11) NULL,
    email VARCHAR(255) UNIQUE NULL,
    password VARCHAR(255) NULL,
    status BOOLEAN DEFAULT 0 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL
)