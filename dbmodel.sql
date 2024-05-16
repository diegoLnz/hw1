CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    userdata_id INT
);

CREATE TABLE userdata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_surname VARCHAR(255),
    email VARCHAR(255),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE users
ADD CONSTRAINT fk_userdata_id FOREIGN KEY (userdata_id) REFERENCES userdata(id);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_description TEXT,
    publish_date DATETIME,
    user_id INT,
    image_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name TEXT,
    file_extension TEXT,
    file_path TEXT,
    post_id INT,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

ALTER TABLE posts
ADD CONSTRAINT fk_image_id FOREIGN KEY (image_id) REFERENCES images(id);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    created_at DATETIME,
    user_id INT,
    post_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);