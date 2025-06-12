CREATE DATABASE amazon_clone;
USE amazon_clone;

/* POSSIBLE CHANGES:
 * users.email as PRIMARY KEY ( no doubles )
 * users.username as SECONDARY KEY ( no doubles )
 * products.name as PRIMARY KEY ( no doubles )
*/
 

-- create user table
-- gets username (max 50 char)
-- gets email (max 100 char)
-- gets password (max 255 char)
-- Primary key is "id"
CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT,
	username VARCHAR( 50 ),
	email VARCHAR( 100 ),
	password VARCHAR( 255 ),
	
	PRIMARY KEY( id ),
	);
	
-- create products table
-- gets name max (max 100 char)
-- gets description (txt)
-- get price (10 num, 2 decimal)
-- gets image (char 255 for path)
-- Primary key is "id"
CREATE TABLE products (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR( 100 ),
	description TEXT,
	price DECIMAL( 10, 2 ),
	image VARCHAR( 255 ),
	
	PRIMARY KEY( id )
	);
	
-- gets user id from users table
-- gets product id from products table
-- gets quantity from user input
-- Primary key is "id"
CREATE TABLE cart (
	id INT NOT NULL AUTO_INCREMENT,
	user_id INT,
	product_id INT,
	quantity INT,
	
	PRIMARY KEY( id )
	);
	
-- create orders table
CREATE TABLE orders (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(100),
    email VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    payment_method VARCHAR(50),
    total_amount DECIMAL(10, 2),
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

-- create order_items table
CREATE TABLE order_items (
    id INT NOT NULL AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    
    PRIMARY KEY(id),
    FOREIGN KEY(order_id) REFERENCES orders(id),
    FOREIGN KEY(product_id) REFERENCES products(id)
);