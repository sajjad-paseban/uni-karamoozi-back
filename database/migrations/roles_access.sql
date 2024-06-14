-- Active: 1718361914524@@127.0.0.1@3306@uni_karamoozi

CREATE Table roles_access(
    id int PRIMARY KEY AUTO_INCREMENT,
    role_id int NOT NULL,
    menu_id int NOT NULL,
    status BOOLEAN DEFAULT 1 NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (role_id) REFERENCES roles(id),
    Foreign Key (menu_id) REFERENCES menu(id)
)