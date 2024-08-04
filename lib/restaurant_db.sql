-- Create the database
CREATE DATABASE IF NOT EXISTS restaurant_db;

-- Switch to the newly created database
USE restaurant_db;

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
('Truffle Mushroom Risotto', 'A creamy Arborio rice dish infused with earthy truffle essence and mixed wild mushrooms. Slowly cooked to perfection, each grain is enveloped in a velvety Parmesan sauce. Finished with a drizzle of truffle oil and fresh chives, this risotto offers a luxurious blend of textures and flavors that dance on your palate.', 18.99, 2),
('Seared Ahi Tuna Steak', 'Premium grade Ahi tuna, expertly seared to achieve a perfectly rare center. The steak is encrusted with sesame seeds and served on a bed of wakame seaweed salad. Accompanied by pickled ginger, wasabi, and a zesty ponzu sauce, this dish offers a harmonious balance of flavors from land and sea.', 24.50, 2),
('Coq au Vin', 'A classic French dish of chicken braised in red wine, bacon lardons, and pearl onions. The tender chicken absorbs the rich flavors of the wine sauce, enhanced by garlic, thyme, and bay leaves. Served with buttery mashed potatoes, this hearty dish embodies rustic elegance.', 21.00, 2),
('Lobster Mac and Cheese', 'A gourmet twist on a comfort classic. Cavatappi pasta is smothered in a creamy blend of Gruyère, sharp cheddar, and Parmesan cheeses, then studded with succulent chunks of fresh lobster meat. Topped with a crispy herb breadcrumb crust and baked until golden, it''s an indulgent delight.', 23.50, 2),
('Vegetable Wellington', 'A vegetarian take on the classic Beef Wellington. A medley of roasted vegetables and mushroom duxelles, wrapped in layers of prosciutto and flaky puff pastry. Served with a rich vegetable jus and honey-glazed baby carrots, it''s a dish that proves vegetarian cuisine can be both elegant and satisfying.', 19.99, 2),
('Miso Glazed Black Cod', 'Alaskan black cod marinated in sweet miso, sake, and mirin, then broiled to perfection. The fish is flaky and buttery, with a caramelized exterior that balances sweet and savory. Served with sautéed baby bok choy and steamed jasmine rice, it''s a harmonious blend of Japanese flavors.', 26.00, 2),
('Osso Buco alla Milanese', 'Tender veal shanks slow-cooked in white wine and vegetable broth until the meat falls off the bone. Served on a bed of saffron risotto and topped with gremolata, this Milanese specialty offers a perfect balance of rich, hearty flavors and bright, zesty notes.', 28.50, 2),
('Pumpkin Spice Crème Brûlée', 'A seasonal twist on the classic French dessert. Silky smooth pumpkin-infused custard is delicately flavored with cinnamon, nutmeg, and ginger. Topped with a layer of caramelized sugar that cracks satisfyingly with each spoonful, it''s autumn in a ramekin.', 8.50, 3),
('Deconstructed Lemon Meringue Pie', 'A playful reimagining of the classic dessert. Tart lemon curd is layered with buttery shortbread crumble and topped with pillowy peaks of torched Italian meringue. Garnished with candied lemon zest and a sprinkle of edible flowers, it''s a visual and gustatory delight.', 9.00, 3),
('Chocolate Lava Cake', 'A decadent chocolate lover''s dream. This individual cake features a rich, moist exterior that gives way to a molten center of flowing dark chocolate. Served warm with a scoop of vanilla bean ice cream and a dusting of cocoa powder, it''s an irresistible end to any meal.', 8.75, 3),
('Caprese Skewers', 'A delightful twist on the classic Caprese salad. Cherry-sized mozzarella balls are skewered with ripe cherry tomatoes and fresh basil leaves. Drizzled with aged balsamic reduction and extra virgin olive oil, then sprinkled with sea salt and cracked black pepper, these skewers offer a perfect balance of flavors in every bite.', 9.50, 1),
('Crispy Calamari', 'Tender rings of calamari lightly coated in seasoned flour and flash-fried to golden perfection. Served with a zesty marinara sauce and garlic aioli for dipping, and garnished with fried parsley and lemon wedges. The calamari is crispy on the outside, tender on the inside, and utterly addictive.', 11.25, 1),
('Butternut Squash Soup', 'A velvety smooth soup that embodies the essence of autumn. Roasted butternut squash is blended with caramelized onions, apple, and a hint of warming spices. Finished with a swirl of crème fraîche and toasted pumpkin seeds, this soup is both comforting and elegant.', 7.50, 1),
('Beef Carpaccio', 'Paper-thin slices of premium beef tenderloin, served raw and dressed with extra virgin olive oil, fresh lemon juice, and shaved Parmesan. Topped with peppery arugula, capers, and a drizzle of truffle oil, this appetizer is a sophisticated palate pleaser.', 13.50, 1),
('Spinach and Artichoke Dip', 'A crowd-pleasing classic elevated to gourmet status. Creamy spinach and tender artichoke hearts are blended with a mix of cheeses and baked until golden and bubbly. Served with a variety of house-made crostini and vegetable crudités, it''s perfect for sharing.', 10.75, 1);



-- Adding image URLs for each menu item (assuming the last INSERT resulted in item_id values from 1 to ...)
INSERT INTO Images (item_id, image_url) VALUES
(1, 'https://images.unsplash.com/photo-1633964913295-ceb43826e7c9'),
(1, 'https://images.unsplash.com/photo-1476124369491-e7addf5db371'),
(1, 'https://images.unsplash.com/photo-1512058556646-c4da40fba323'),
(2, 'https://images.unsplash.com/photo-1600891964092-4316c288032e'),
(2, 'https://images.unsplash.com/photo-1501595091296-3aa970afb3ff'),
(2, 'https://images.unsplash.com/photo-1574484284002-952d92456975'),
(3, 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6'),
(3, 'https://images.unsplash.com/photo-1600891964092-4316c288032e'),
(3, 'https://images.unsplash.com/photo-1518492104633-130d0cc84637'),
(4, 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9'),
(4, 'https://images.unsplash.com/photo-1543339494-b4cd4f7ba686'),
(4, 'https://images.unsplash.com/photo-1481931098730-318b6f776db0'),
(5, 'https://plus.unsplash.com/premium_photo-1668618296364-bbbabe5c32eb'),
(5, 'https://images.unsplash.com/photo-1631292784640-2b24be784d5d'),
(5, 'https://images.unsplash.com/photo-1546549032-9571cd6b27df'),
(6, 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2'),
(6, 'https://images.unsplash.com/photo-1535140728325-a4d3707eee61'),
(6, 'https://plus.unsplash.com/premium_photo-1667807522888-911d7e8f0791'),
(7, 'https://images.unsplash.com/photo-1626509653291-18d9a934b9db'),
(7, 'https://images.unsplash.com/photo-1544025162-d76694265947'),
(7, 'https://images.unsplash.com/photo-1504674900247-0877df9cc836'),
(8, 'https://images.unsplash.com/photo-1488477181946-6428a0291777'),
(8, 'https://images.unsplash.com/photo-1509461399763-ae67a981b254'),
(8, 'https://plus.unsplash.com/premium_photo-1669261881636-70f0dcd427a3'),
(9, 'https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81'),
(9, 'https://images.unsplash.com/photo-1519915028121-7d3463d20b13'),
(9, 'https://images.unsplash.com/photo-1488477181946-6428a0291777'),
(10, 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51'),
(10, 'https://images.unsplash.com/photo-1511911063855-2bf39afa5b2e'),
(10, 'https://images.unsplash.com/photo-1541783245831-57d6fb0926d3'),
(11, 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71'),
(11, 'https://images.unsplash.com/photo-1482012792084-a0c3725f289f'),
(11, 'https://images.unsplash.com/photo-1546793665-c74683f339c1'),
(12, 'https://images.unsplash.com/photo-1604909052743-94e838986d24'),
(12, 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0'),
(12, 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58'),
(13, 'https://images.unsplash.com/photo-1476718406336-bb5a9690ee2a'),
(13, 'https://images.unsplash.com/photo-1547592166-23ac45744acd'),
(13, 'https://images.unsplash.com/photo-1605908580297-f3e1c02e64ff'),
(14, 'https://images.unsplash.com/photo-1513104890138-7c749659a591'),
(14, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38'),
(14, 'https://images.unsplash.com/photo-1555072956-7758afb20e8f'),
(15, 'https://images.unsplash.com/photo-1703219339970-98cd69cc896f'),
(15, 'https://images.unsplash.com/photo-1591299177061-2151e53fcaea'),
(15, 'https://plus.unsplash.com/premium_photo-1679746246490-1d6b18f6d7ec');