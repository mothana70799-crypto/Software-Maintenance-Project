CREATE DATABASE IF NOT EXISTS himam_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE himam_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500) DEFAULT 'https://via.placeholder.com/300x300?text=Product',
    category ENUM('electronics', 'clothes', 'perfumes', 'accessories') NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2),
    status ENUM('pending', 'processing', 'shipped', 'delivered') DEFAULT 'pending',
    address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE seller_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    store_name VARCHAR(200) NOT NULL,
    category VARCHAR(50),
    description TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    rating INT DEFAULT 5,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@himam.com', 'admin123', 'admin'),
('user1', 'user1@himam.com', '123456', 'user');

INSERT INTO products (name, description, price, image, category, stock) VALUES
('سماعات بلوتوث', 'سماعات لاسلكية بجودة صوت عالية مع خاصية إلغاء الضوضاء', 299.99, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop', 'electronics', 50),
('ساعة ذكية', 'ساعة ذكية مع مراقبة معدل ضربات القلب وتتبع اللياقة', 599.99, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop', 'electronics', 30),
('لابتوب احترافي', 'لابتوب بمعالج قوي وشاشة عالية الدقة للمحترفين', 3999.99, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=300&fit=crop', 'electronics', 15),
('كاميرا رقمية', 'كاميرا احترافية بدقة 4K مع عدسة متعددة الاستخدامات', 2499.99, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=300&h=300&fit=crop', 'electronics', 20),
('قميص رجالي أنيق', 'قميص قطني بتصميم عصري مناسب لجميع المناسبات', 149.99, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=300&h=300&fit=crop', 'clothes', 100),
('فستان نسائي', 'فستان أنيق بتصميم حديث مناسب للسهرات', 399.99, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=300&h=300&fit=crop', 'clothes', 60),
('جاكيت جلد', 'جاكيت جلد طبيعي فاخر بتصميم كلاسيكي', 899.99, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=300&h=300&fit=crop', 'clothes', 25),
('بنطلون جينز', 'بنطلون جينز مريح بقصة عصرية', 199.99, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=300&h=300&fit=crop', 'clothes', 80),
('عطر oud فاخر', 'عطر عربي فاخر بمزيج العود والمسك', 449.99, 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=300&h=300&fit=crop', 'perfumes', 40),
('عطر زهري', 'عطر نسائي برائحة الزهور الطبيعية الفاخرة', 349.99, 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?w=300&h=300&fit=crop', 'perfumes', 55),
('مجموعة عطور مصغرة', 'مجموعة من 5 عطور مصغرة فاخرة في علبة هدايا', 599.99, 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=300&h=300&fit=crop', 'perfumes', 35),
('سلسلة ذهبية', 'سلسلة ذهبية عيار 18 بتصميم راقي', 1299.99, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=300&h=300&fit=crop', 'accessories', 20),
('نظارة شمسية', 'نظارة شمسية ماركة فاخرة بحماية UV400', 249.99, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=300&h=300&fit=crop', 'accessories', 70),
('حقيبة يد جلدية', 'حقيبة يد نسائية من الجلد الطبيعي', 699.99, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=300&h=300&fit=crop', 'accessories', 45),
('حزام جلد فاخر', 'حزام رجالي من الجلد الإيطالي الفاخر', 179.99, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300&h=300&fit=crop', 'accessories', 90);
