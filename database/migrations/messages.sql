-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi

CREATE Table message(
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    message TEXT NOT NULL,
    seen BOOLEAN NULL DEFAULT 0,
    status BOOLEAN NULL DEFAULT 1,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (user_id) REFERENCES users(id)
)