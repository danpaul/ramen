CREATE TABLE IF NOT EXISTS Users
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	email varchar(255) NOT NULL UNIQUE,
	user_name varchar(255),
	verified tinyint(1) NOT NULL DEFAULT 0,
	password varchar(255) NOT NULL,
	role tinyint NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Orders
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int NOT NULL,
	FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS Products
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL DEFAULT '',
	description text NOT NULL DEFAULT '',
	price int NOT NULL DEFAULT 0,
	inventory int NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS OrderItems
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	quantity int NOT NULL DEFAULT 0,
	order_id int NOT NULL,
	product_id int NOT NULL,
	FOREIGN KEY (order_id) REFERENCES Orders(id),
	FOREIGN KEY (product_id) REFERENCES Products(id)
);

CREATE TABLE IF NOT EXISTS CartItems
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	status int NOT NULL DEFAULT 0,
	quantity int NOT NULL DEFAULT 0,
	product_id int NOT NULL,
	user_id int NOT NULL,
	FOREIGN KEY (product_id) REFERENCES Products(id),
	FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS Addresses
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	address_1 varchar(255) NOT NULL DEFAULT '',
	address_2 varchar(255) NOT NULL DEFAULT '',
	city varchar(64) NOT NULL DEFAULT '',
	state varchar(32) NOT NULL DEFAULT '',
	zip varchar(16) NOT NULL DEFAULT '',
	user_id int NOT NULL,
	FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS EmailVerifications
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	code varchar(255) NOT NULL,
	email varchar(255) NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	INDEX (code)
);

CREATE TABLE IF NOT EXISTS PasswordResets
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	time int NOT NULL,
	secret char(64) NOT NULL,
	email varchar(255) NOT NULL,
	valid tinyint(1) NOT NULL DEFAULT 1,
	INDEX (secret)
);

CREATE TABLE IF NOT EXISTS Categories
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	parent int DEFAULT NULL,
	category_type char(32) NOT NULL,
	name char(64) NOT NULL,
	INDEX (name),
	INDEX (category_type)
);

CREATE TABLE IF NOT EXISTS Tags
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	category char(32) NOT NULL,
	type char(64) NOT NULL,
	name char(64) NOT NULL,
	INDEX (category),
	INDEX (type),
	INDEX (name)
);

CREATE TABLE IF NOT EXISTS ProductTags
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	tag_id int NOT NULL,
	product_id int NOT NULL,
	FOREIGN KEY (tag_id) REFERENCES Tags(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ProductCategories
(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	category_id int NOT NULL,
	product_id int NOT NULL,
	FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);

ALTER TABLE Users ADD salt VARCHAR(32) NOT NULL;