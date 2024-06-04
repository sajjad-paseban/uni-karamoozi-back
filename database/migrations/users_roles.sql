-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi

CREATE Table users_roles(
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (user_id) REFERENCES users(id),
    Foreign Key (role_id) REFERENCES roles(id)
)