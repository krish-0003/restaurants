-- Create Users Table
CREATE TABLE Users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash CHAR(60) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_type ENUM('admin', 'customer') NOT NULL
);

-- Create Categories Table
CREATE TABLE Categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT
);

-- Create Menu Items Table
CREATE TABLE MenuItems (
  item_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  category_id INT,
  FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

-- Create Images Table
CREATE TABLE Images (
  image_id INT AUTO_INCREMENT PRIMARY KEY,
  item_id INT NOT NULL,
  image_url VARCHAR(255) NOT NULL,
  FOREIGN KEY (item_id) REFERENCES MenuItems(item_id)
);

CREATE TABLE Orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  order_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE OrderItems (
  order_item_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  item_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES Orders(order_id),
  FOREIGN KEY (item_id) REFERENCES MenuItems(item_id)
);


-- Inserting sample categories (assuming they are not already created)
INSERT INTO Categories (name, description) VALUES 
('Appetizers', 'Starter dishes to begin your meal'), 
('Main Courses', 'Hearty and fulfilling primary dishes'),
('Desserts', 'Sweet treats to conclude your meal');

-- Inserting sample menu items
INSERT INTO MenuItems (name, description, price, category_id) VALUES
('Tomato Basil Soup', 'Creamy tomato soup with fresh basil and a touch of spice', 5.99, 1),
('Caesar Salad', 'Crisp romaine lettuce, parmesan cheese, and croutons, served with Caesar dressing', 7.50, 1),
('Margherita Pizza', 'Classic pizza with fresh tomatoes, mozzarella, and basil', 12.00, 2),
('Grilled Salmon', 'Perfectly grilled salmon with a lemon butter drizzle', 15.50, 2),
('Ribeye Steak', '10 oz ribeye steak grilled to your preference', 22.00, 2),
('Tiramisu', 'Traditional Italian coffee-flavored dessert', 6.50, 3),
('Cheesecake', 'Rich and creamy cheesecake with a graham cracker crust', 6.00, 3);

-- Adding image URLs for each menu item (assuming the last INSERT resulted in item_id values from 1 to 7)
INSERT INTO Images (item_id, image_url) VALUES
(1, 'https://therecipecritic.com/wp-content/uploads/2017/12/The-Absolute-Best-Most-Popular-Recipes-of-2017-8.jpg'),
(1, 'https://www.allrecipes.com/thmb/-qNhTFemY_EY4zDJIMpzmWw_iio=/750x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/Vegan-Quiche-49803-hero-b2f3a8e0b75e462aa7e032f1636f4f7c.jpg'),
(1, 'https://thestayathomechef.com/wp-content/uploads/2017/12/Homemade-Chicken-Noodle-Soup-1.jpg'),
(2, 'https://www.allrecipes.com/thmb/JTWOAlVY5FPxLrf-_Jeae0BNmQ0=/750x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/Spinach-and-Strawberry-Salad-50521-hero-02ecb2c0087748d3b24fbb5f1212be2c.jpg'),
(2, 'https://natashaskitchen.com/wp-content/uploads/2019/04/Spinach-Salad-with-Bacon-and-Egg-3.jpg'),
(2, 'https://www.fromvalerieskitchen.com/wordpress/wp-content/uploads/2019/04/Easy-Greek-Salad-5.jpg'),
(3, 'https://www.cookingclassy.com/wp-content/uploads/2021/06/Grilled-Pizza-20.jpg'),
(3, 'https://cdn.loveandlemons.com/wp-content/uploads/2020/05/homemade-pizza.jpg'),
(3, 'https://cookieandkate.com/images/2021/07/classic-margherita-pizza.jpg'),
(4, 'https://www.dinneratthezoo.com/wp-content/uploads/2019/04/baked-salmon-5.jpg'),
(4, 'https://www.thespruceeats.com/thmb/HgM2H4zz1HGEcSWeAFgHnJ7Yjx8=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/classic-baked-salmon-4687147-hero-01-b3b53e33d9e34667ae79bda6ec064562.jpg'),
(4, 'https://www.cookingclassy.com/wp-content/uploads/2021/02/baked-salmon-11.jpg'),
(5, 'https://jjandstefanie.com/wp-content/uploads/2021/09/Steak-Recipe.jpg'),
(5, 'https://diethood.com/wp-content/uploads/2021/02/ribeye-steak.jpg'),
(5, 'https://www.wholesomeyum.com/wp-content/uploads/2020/07/wholesomeyum-seared-steak-4.jpg'),
(6, 'https://www.gimmesomeoven.com/wp-content/uploads/2014/08/Tiramisu-1.jpg'),
(6, 'https://bakewithzoha.com/wp-content/uploads/2023/01/Tiramisu-3.jpg'),
(6, 'https://static01.nyt.com/images/2017/04/05/dining/05COOKING-TIRAMISU1/05COOKING-TIRAMISU1-articleLarge.jpg'),
(7, 'https://www.allrecipes.com/thmb/DHOsjm3NundSDP1q6w9F5tr1adY=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/Simple-Cheesecake-74484-hero-d6e7d11964d84dc1b882d110715bba5b.jpg'),
(7, 'https://www.cookingclassy.com/wp-content/uploads/2015/09/cheesecake-17.jpg'),
(7, 'https://handletheheat.com/wp-content/uploads/2015/10/How-to-Make-Cheesecake-2.jpg');