-- Active: 1718361914524@@127.0.0.1@3306@uni_karamoozi
CREATE Table intern_recruitment_application(
    id int PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(255) NOT NULL UNIQUE,
    semester_id INT NOT NULL,
    user_id INT NOT NULL,
    cra_id INT NOT NULL,
    group_id INT NOT NULL,
    capacity INT NOT NULL,
    description TEXT NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (semester_id) REFERENCES semester(id),
    Foreign Key (user_id) REFERENCES users(id),
    Foreign Key (cra_id) REFERENCES company_registration_application(id),
    Foreign Key (group_id) REFERENCES uni_group(id)
)