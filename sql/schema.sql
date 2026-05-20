CREATE TABLE IF NOT EXISTS languages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(80) NOT NULL,
    native_name VARCHAR(80) NOT NULL,
    direction ENUM('ltr', 'rtl') NOT NULL DEFAULT 'ltr',
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    name VARCHAR(160) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(80) NULL,
    password_hash VARCHAR(255) NOT NULL,
    email_verified_at DATETIME NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    INDEX idx_users_role (role),
    INDEX idx_users_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    INDEX idx_password_resets_user (user_id),
    INDEX idx_password_resets_expires (expires_at),
    CONSTRAINT fk_password_resets_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    language_code VARCHAR(10) NOT NULL DEFAULT 'sq',
    name VARCHAR(160) NOT NULL,
    phone VARCHAR(80) NOT NULL,
    email VARCHAR(190) NULL,
    service VARCHAR(120) NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'archived') NOT NULL DEFAULT 'new',
    source VARCHAR(80) NOT NULL DEFAULT 'website',
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    read_at DATETIME NULL,
    INDEX idx_contact_messages_status (status),
    INDEX idx_contact_messages_created (created_at),
    INDEX idx_contact_messages_language (language_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_type ENUM('string', 'text', 'bool', 'int', 'json') NOT NULL DEFAULT 'string',
    is_public TINYINT(1) NOT NULL DEFAULT 0,
    updated_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO languages (code, name, native_name, direction, is_default, is_active, sort_order)
VALUES
    ('sq', 'Albanian', 'Shqip', 'ltr', 1, 1, 1),
    ('en', 'English', 'English', 'ltr', 0, 1, 2)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    native_name = VALUES(native_name),
    direction = VALUES(direction),
    is_active = VALUES(is_active),
    sort_order = VALUES(sort_order);

INSERT INTO site_settings (setting_key, setting_value, setting_type, is_public)
VALUES
    ('site_name', 'Creative Art by Serxho', 'string', 1),
    ('site_url', 'https://creativeartbyserxho.gt.tc', 'string', 1),
    ('default_language', 'sq', 'string', 1),
    ('contact_email', '', 'string', 1),
    ('contact_phone', '', 'string', 1)
ON DUPLICATE KEY UPDATE
    setting_value = VALUES(setting_value),
    setting_type = VALUES(setting_type),
    is_public = VALUES(is_public);
