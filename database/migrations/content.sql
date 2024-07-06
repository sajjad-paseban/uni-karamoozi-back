-- Active: 1718361914524@@127.0.0.1@3306@uni_karamoozi

CREATE Table content(
    id int PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NULL,
    seo_description TEXT NULL,
    content TEXT NULL,
    banner_path VARCHAR(255) NULL,
    has_star BOOLEAN DEFAULT 0 NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL
)