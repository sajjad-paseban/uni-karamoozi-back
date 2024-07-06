-- Active: 1718361914524@@127.0.0.1@3306@uni_karamoozi

CREATE Table media(
    id int PRIMARY KEY AUTO_INCREMENT,
    alt VARCHAR(255) NULL,
    path VARCHAR(255) NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL
)