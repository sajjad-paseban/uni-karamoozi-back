CREATE Table stu_semesters(
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester_id INT NOT NULL,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    status BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
    created_by int NULL,
    updated_by int NULL,
    Foreign Key (semester_id) REFERENCES semester(id),
    Foreign Key (group_id) REFERENCES uni_group(id),
    Foreign Key (user_id) REFERENCES users(id)
)