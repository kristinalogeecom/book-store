USE bookstore;

CREATE TABLE authors (
                         id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
                         first_name VARCHAR(100) NOT NULL,
                         last_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE books (
                       id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
                       title VARCHAR(250),
                       year INT,
                       author_id INT,
                       FOREIGN KEY (author_id) REFERENCES authors(id)
) ENGINE=InnoDB;