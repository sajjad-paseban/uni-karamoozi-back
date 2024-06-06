-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi
CREATE TABLE menu(
    id int PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NULL,
    path VARCHAR(255) NULL,
    key_param VARCHAR(255) UNIQUE NULL,
    logo VARCHAR(255) NULL,
    parent_id int NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL
)