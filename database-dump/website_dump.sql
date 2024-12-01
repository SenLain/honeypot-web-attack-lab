PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user',
    avatar TEXT
);

INSERT INTO users (username, password, role, avatar) 
VALUES ('admin', '$2y$10$5tOSGPYtrGJEo0HBhvk5qOoJdNy54T3PJtpCzkAbF2WxIdZ3l9L2a', 'admin', 'default.jpg'); -- admin user with: admin:ThisIsTheM0stSecureServerEver


COMMIT;