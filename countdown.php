<?php
require_once 'db.php';
require_once 'functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $target_date = $_POST['target_date'] ?? '';
    
    if (!empty($title) && !empty($target_date)) {
        $stmt = $conn->prepare("INSERT INTO countdowns (title, description, target_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $target_date);
        $stmt->execute();
        $stmt->close();
    }
}


// Get all countdowns
$countdowns = [];
$result = $conn->query("SELECT * FROM countdowns ORDER BY target_date ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $countdowns[] = $row;
    }
    $result->free();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odpočítavanie | NextFlick</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        .countdown-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .countdown-form {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .countdown-form h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        .countdown-list {
            display: grid;
            gap: 1.5rem;
        }
        
        .countdown-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .countdown-card h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }
        
        .countdown-card .description {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .countdown-card .timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e74c3c;
        }
        
        .countdown-card .date {
            color: #888;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .btn {
            background: #3498db;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="countdown-container">
        <div class="countdown-form">
            <h2>Pridať nové odpočítavanie</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Názov</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Popis</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="target_date">Dátum a čas</label>
                    <input type="datetime-local" id="target_date" name="target_date" required>
                </div>
                <button type="submit" class="btn">Pridať odpočítavanie</button>
            </form>
        </div>
        
        <div class="countdown-list">
            <?php foreach ($countdowns as $countdown): ?>
                <div class="countdown-card">
                    <h3><?php echo htmlspecialchars($countdown['title']); ?></h3>
                    <?php if (!empty($countdown['description'])): ?>
                        <div class="description"><?php echo htmlspecialchars($countdown['description']); ?></div>
                    <?php endif; ?>
                    <div class="timer" data-target="<?php echo $countdown['target_date']; ?>">
                        Načítavam...
                    </div>
                    <div class="date">
                        <?php echo date('d.m.Y H:i', strtotime($countdown['target_date'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function updateCountdowns() {
            document.querySelectorAll('.timer').forEach(timer => {
                const targetDate = new Date(timer.dataset.target).getTime();
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    timer.innerHTML = "Uplynulé";
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timer.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            });
        }
        
        // Update countdowns every second
        setInterval(updateCountdowns, 1000);
        updateCountdowns(); // Initial update
    </script>
</body>
</html> 