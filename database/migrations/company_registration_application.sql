-- Active: 1718361914524@@127.0.0.1@3306@uni_karamoozi
CREATE Table company_registration_application(
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NULL,
    company_manager_name VARCHAR(255) NULL,
    company_supervisor_name VARCHAR(255) NULL,
    company_supervisor_phone VARCHAR(255) NULL,
    company_telephone VARCHAR(255) NULL,
    company_address VARCHAR(255) NULL,
    description TEXT NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (user_id) REFERENCES users(id)
)