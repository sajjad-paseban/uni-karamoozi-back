-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi

CREATE Table auth_token(
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    token TEXT NOT NULL,
    type int NULL DEFAULT 0,
    status BOOLEAN NULL DEFAULT 1,
    expire_date TIMESTAMP NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (user_id) REFERENCES users(id)
)