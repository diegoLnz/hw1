CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name TEXT,
    file_extension TEXT,
    file_path TEXT
);

CREATE TABLE IF NOT EXISTS nasaimages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    network_path TEXT
);

CREATE TABLE IF NOT EXISTS userdata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_surname VARCHAR(255),
    email VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    userdata_id INT,
    profile_pic_id INT,
    FOREIGN KEY (userdata_id) REFERENCES userdata(id),
    FOREIGN KEY (profile_pic_id) REFERENCES images(id)
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_description TEXT,
    publish_date DATETIME,
    user_id INT,
    image_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (image_id) REFERENCES images(id)
);

CREATE TABLE IF NOT EXISTS nasaposts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_description TEXT,
    publish_date DATETIME,
    image_id INT,
    FOREIGN KEY (image_id) REFERENCES nasaimages(id)
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    created_at DATETIME,
    user_id INT,
    post_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE IF NOT EXISTS follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    followed_user_id INT,
    FOREIGN KEY (follower_id) REFERENCES users(id),
    FOREIGN KEY (followed_user_id) REFERENCES users(id),
    UNIQUE (follower_id, followed_user_id)
);