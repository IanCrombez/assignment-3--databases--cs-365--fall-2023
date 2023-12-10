DROP DATABASE IF EXISTS student_passwords;
CREATE DATABASE student_passwords DEFAULT CHARACTER SET utf8mb4;

USE student_passwords;

CREATE USER 'passwords_user'@'localhost' IDENTIFIED BY '12qw!@QW';
GRANT ALL PRIVILEGES ON  student_passwords.* TO 'passwords_user'@'localhost';

SET @Key = UNHEX('213584861aeeee35851');

CREATE TABLE IF NOT EXISTS sites(
    site_id INT AUTO_INCREMENT,
    site_name VARCHAR(265) NOT NULL,
    site_url VARCHAR(256) NOT NULL,
    
    PRIMARY KEY (site_id)
);

CREATE TABLE IF NOT EXISTS accounts(
    account_id INT AUTO_INCREMENT,
    site_id INT NOT NULL,
    username VARCHAR(64) NOT NULL,
    password VARBINARY(256) NOT NULL,
    email VARCHAR(256) NOT NULL,
    comment TEXT,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_username (username, site_id)
    PRIMARY KEY (account_id)
);

INSERT INTO sites (site_name, site_url) VALUES
    ('Steam', 'https://www.store.steampowered.com'),
    ('Twitch', 'https://www.twitch.com')
    ('Amazon', 'https://www.amazon.com');

INSERT INTO accounts (site_id, username, password, email, comment) VALUES
    (1, 'iac009', AES_ENCRYPT('potato1234!', @Key), 'icrombez@crombezclan.com', 'steam account'),
    (2, 'iac009', AES_ENCRYPT('Ilikechicken4lunch', @Key), 'icrombez@crombezclan.com', 'twitch account'),
    (3, 'iac009', AES_ENCRYPT('runningout0fIdeas', @Key), 'icrombez@crombezclan.com', 'amazon account'),
    (1, 'iac010', AES_ENCRYPT('Lastpassword0000', @Key), 'chunky.boby@yahoo.com', 'test steam account'),
    