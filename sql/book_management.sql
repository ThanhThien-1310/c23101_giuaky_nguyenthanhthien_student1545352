CREATE DATABASE book_management;
USE book_management;

CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(255) NOT NULL,
    book_numbers INT DEFAULT 0
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT,
    category_id INT,
    publisher VARCHAR(255),
    publish_year YEAR,
    quantity INT DEFAULT 0,
    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);


-- Insert authors
INSERT INTO authors (author_name, book_numbers) VALUES 
('J.K. Rowling', 7),
('George Orwell', 5),
('J.R.R. Tolkien', 4),
('Agatha Christie', 85),
('Jane Austen', 6);

-- Insert categories
INSERT INTO categories (category_name) VALUES 
('Fantasy'),
('Science Fiction'),
('Mystery'),
('Classics'),
('Historical Fiction');

-- Insert books
INSERT INTO books (title, author_id, category_id, publisher, publish_year, quantity) VALUES 
('Harry Potter and the Philosopher\'s Stone', 1, 1, 'Bloomsbury', 1997, 120),
('1984', 2, 2, 'Secker & Warburg', 1949, 50),
('The Hobbit', 3, 1, 'George Allen & Unwin', 1937, 80),
('Murder on the Orient Express', 4, 3, 'Collins Crime Club', 1934, 100),
('Pride and Prejudice', 5, 4, 'T. Egerton', 1813, 70);
