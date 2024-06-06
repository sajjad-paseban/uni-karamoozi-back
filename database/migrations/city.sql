-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi

CREATE Table city(
    id int PRIMARY KEY AUTO_INCREMENT,
    province_id int null,
    title VARCHAR(255) NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (province_id) REFERENCES province(id)
)