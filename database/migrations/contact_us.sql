-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi
CREATE Table contact_us(
    id int PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NULL,
    subject VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    description TEXT NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL
)