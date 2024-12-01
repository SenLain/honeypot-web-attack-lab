PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;

-- Create the 'users' table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

-- Insert user data
INSERT INTO users (username, password) VALUES ('admin', 'Themostsecurepassw0rd!');
INSERT INTO users (username, password) VALUES ('jdoe1984', 'password123');
INSERT INTO users (username, password) VALUES ('samantha_xoxo', 'ilovecats!');
INSERT INTO users (username, password) VALUES ('tech_guy99', 'admin@2023');
INSERT INTO users (username, password) VALUES ('coolkid_56', 'summer2024');
INSERT INTO users (username, password) VALUES ('bookworm77', 'readmore#books');
INSERT INTO users (username, password) VALUES ('gamerboy42', 'fortnite4ever');
INSERT INTO users (username, password) VALUES ('yogagirl', 'zen_life#');
INSERT INTO users (username, password) VALUES ('fastcar88', 'drive2023!');
INSERT INTO users (username, password) VALUES ('nature_lover', 'greenearth');
INSERT INTO users (username, password) VALUES ('urban_nomad', 'citylights#');
INSERT INTO users (username, password) VALUES ('happy_panda', 'bamboo123');
INSERT INTO users (username, password) VALUES ('codingqueen', 'python_rules!');
INSERT INTO users (username, password) VALUES ('travelbug', 'wanderlust9');
INSERT INTO users (username, password) VALUES ('fit_freak', 'workout@home');
INSERT INTO users (username, password) VALUES ('movie_buff', 'cinema4life');
INSERT INTO users (username, password) VALUES ('coffee_addict', 'latte_love');
INSERT INTO users (username, password) VALUES ('crypto_king', 'btc@moon2023');
INSERT INTO users (username, password) VALUES ('foodie123', 'tacosarelife!');
INSERT INTO users (username, password) VALUES ('skaterboi', 'kickflip4days');
INSERT INTO users (username, password) VALUES ('dream_chaser', 'never_giveup!');
INSERT INTO users (username, password) VALUES ('sleepyhead', 'zzz@123');
INSERT INTO users (username, password) VALUES ('star_gazer', 'milkyway@space');
INSERT INTO users (username, password) VALUES ('beach_bum', 'surfsup77');
INSERT INTO users (username, password) VALUES ('rockclimber', 'reach@thetop');
INSERT INTO users (username, password) VALUES ('puzzle_master', 'solve@this');
INSERT INTO users (username, password) VALUES ('hiking_enthusiast', 'trail_life2024');
INSERT INTO users (username, password) VALUES ('kdrama_fan', 'oppa_love');
INSERT INTO users (username, password) VALUES ('history_buff', 'past@present');
INSERT INTO users (username, password) VALUES ('meme_lord', 'lol@123');
INSERT INTO users (username, password) VALUES ('plant_parent', 'succulentsrock');

-- Create the 'products' table
CREATE TABLE products (
    product_id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_name TEXT NOT NULL,
    description TEXT,
    category TEXT
);

-- Insert product data
INSERT INTO products (product_name, description, category) VALUES ('Laptop', 'High-performance laptop with 16GB RAM and 512GB SSD.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Chair', 'Ergonomic office chair with adjustable height.', 'Furniture');
INSERT INTO products (product_name, description, category) VALUES ('Smartphone', 'Latest model with 5G connectivity and 128GB storage.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Table', 'Wooden dining table that seats six.', 'Furniture');
INSERT INTO products (product_name, description, category) VALUES ('Headphones', 'Noise-canceling over-ear headphones.', 'Accessories');
INSERT INTO products (product_name, description, category) VALUES ('Coffee Maker', 'Brews up to 12 cups of coffee with a programmable timer.', 'Appliances');
INSERT INTO products (product_name, description, category) VALUES ('Backpack', 'Durable waterproof backpack with multiple compartments.', 'Accessories');
INSERT INTO products (product_name, description, category) VALUES ('Blender', 'High-speed blender with a 1.5-liter capacity.', 'Appliances');
INSERT INTO products (product_name, description, category) VALUES ('Desk Lamp', 'LED desk lamp with adjustable brightness levels.', 'Lighting');
INSERT INTO products (product_name, description, category) VALUES ('Shoes', 'Comfortable running shoes with excellent grip.', 'Footwear');
INSERT INTO products (product_name, description, category) VALUES ('Bookshelf', 'Five-tier wooden bookshelf for organizing your space.', 'Furniture');
INSERT INTO products (product_name, description, category) VALUES ('Monitor', '24-inch HD monitor with anti-glare technology.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Keyboard', 'Mechanical keyboard with RGB lighting.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Jacket', 'Waterproof winter jacket with thermal insulation.', 'Apparel');
INSERT INTO products (product_name, description, category) VALUES ('Watch', 'Stylish analog watch with leather strap.', 'Accessories');
INSERT INTO products (product_name, description, category) VALUES ('Tablet', 'Lightweight tablet with a 10-inch display and 64GB storage.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Sofa', 'Modern three-seater sofa with comfortable cushions.', 'Furniture');
INSERT INTO products (product_name, description, category) VALUES ('Refrigerator', 'Energy-efficient refrigerator with a 300L capacity.', 'Appliances');
INSERT INTO products (product_name, description, category) VALUES ('Microwave', 'Compact microwave with multiple cooking presets.', 'Appliances');
INSERT INTO products (product_name, description, category) VALUES ('Gloves', 'Warm and durable winter gloves.', 'Apparel');
INSERT INTO products (product_name, description, category) VALUES ('Rug', 'Large area rug with a modern design.', 'Home Decor');
INSERT INTO products (product_name, description, category) VALUES ('Sunglasses', 'Polarized sunglasses with UV protection.', 'Accessories');
INSERT INTO products (product_name, description, category) VALUES ('Gaming Console', 'Next-gen gaming console with 1TB storage.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Water Bottle', 'Reusable stainless steel water bottle, 1L.', 'Accessories');
INSERT INTO products (product_name, description, category) VALUES ('Camera', 'Digital SLR camera with 24MP resolution and 4K video support.', 'Electronics');
INSERT INTO products (product_name, description, category) VALUES ('Curtains', 'Blackout curtains for a cozy living space.', 'Home Decor');
INSERT INTO products (product_name, description, category) VALUES ('Yoga Mat', 'Non-slip yoga mat with extra cushioning.', 'Fitness');
INSERT INTO products (product_name, description, category) VALUES ('Helmet', 'Safety helmet for cycling and outdoor activities.', 'Accessories');
INSERT INTO products (product_name, description, category) VALUES ('Grill', 'Electric grill with adjustable temperature control.', 'Appliances');
INSERT INTO products (product_name, description, category) VALUES ('Bed Frame', 'Queen-sized bed frame with a sleek wooden design.', 'Furniture');

COMMIT;
