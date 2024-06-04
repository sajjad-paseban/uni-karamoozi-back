-- Active: 1716540807319@@127.0.0.1@3306@uni_karamoozi
CREATE TABLE setting(
	id int PRIMARY KEY AUTO_INCREMENT,
  uni_name VARCHAR(255) NULL,
  uni_logo_path VARCHAR(255) NULL,
  footer_description TEXT NULL,
  location JSON NULL,
  telephone VARCHAR(255) NULL,
  email VARCHAR(255) NULL,
  fax VARCHAR(255) NULL,
  address VARCHAR(255) NULL,
  description TEXT NULL,
  register_rules TEXT NULL,
  status BOOLEAN DEFAULT 1 NULL,
  create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL,
  created_by int NULL,
  updated_by int NULL
)