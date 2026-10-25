CREATE DATABASE IF NOT EXISTS transport_app;
USE transport_app;


CREATE TABLE modes (
    mode_id INT AUTO_INCREMENT PRIMARY KEY,
    mode_name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(150) NOT NULL,
    origin VARCHAR(150) NOT NULL,
    destination VARCHAR(150) NOT NULL,
    mode_id INT NOT NULL,

    FOREIGN KEY (mode_id)
        REFERENCES modes(mode_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

