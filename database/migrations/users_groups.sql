CREATE Table users_groups(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    group_id INT NOT NULL,
    status BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (user_id) REFERENCES users(id),
    Foreign Key (role_id) REFERENCES roles(id),
    Foreign Key (group_id) REFERENCES uni_group(id)
)