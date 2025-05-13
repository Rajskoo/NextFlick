CREATE TABLE IF NOT EXISTS date_ideas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    planned_date DATE,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS date_idea_thread (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_idea_id INT NOT NULL,
    content TEXT NOT NULL,
    type ENUM('text', 'image') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (date_idea_id) REFERENCES date_ideas(id) ON DELETE CASCADE
); 