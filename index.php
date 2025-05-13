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
        // Redirect to avoid resubmission
        header('Location: index.php');
        exit;
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

// Separate active and past countdowns
$active_countdowns = [];
$past_countdowns = [];
$now = new DateTime();

foreach ($countdowns as $countdown) {
    $target_date = new DateTime($countdown['target_date']);
    if ($target_date > $now) {
        $active_countdowns[] = $countdown;
    } else {
        $past_countdowns[] = $countdown;
    }
}

// Helper for 'how long ago'
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return 'pred ' . $diff->y . ' rokmi';
    if ($diff->m > 0) return 'pred ' . $diff->m . ' mesiacmi';
    if ($diff->d > 6) return 'pred ' . floor($diff->d / 7) . ' týždňami';
    if ($diff->d > 0) return 'pred ' . $diff->d . ' dňami';
    if ($diff->h > 0) return 'pred ' . $diff->h . ' hodinami';
    if ($diff->i > 0) return 'pred ' . $diff->i . ' minútami';
    return 'pred chvíľou';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            position: relative;
        }
        
        .countdown-card h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
            padding-right: 60px;
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

        .countdown-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit, .btn-delete {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .btn-edit {
            color: #3498db;
        }

        .btn-delete {
            color: #e74c3c;
        }

        .btn-edit:hover {
            background: #ebf5fb;
        }

        .btn-delete:hover {
            background: #fdedec;
        }

        .section-title {
            margin: 2rem 0 1rem;
            color: #333;
            font-size: 1.5rem;
        }

        .past-countdowns {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 10px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="countdown-container">
        <!-- Active Countdowns -->
        <?php if (!empty($active_countdowns)): ?>
            <div class="countdown-list">
                <?php foreach ($active_countdowns as $countdown): ?>
                    <div class="countdown-card">
                        <div class="countdown-actions">
                            <button class="btn-edit" onclick="editCountdown(<?php echo $countdown['id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteCountdown(<?php echo $countdown['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
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
        <?php endif; ?>

        <!-- Add New Countdown Form -->
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
        
        <!-- Past Countdowns -->
        <?php if (!empty($past_countdowns)): ?>
            <div class="past-countdowns">
                <h2 class="section-title">Uplynulé odpočítavania</h2>
                <div class="countdown-list">
                    <?php foreach ($past_countdowns as $countdown): ?>
                        <div class="countdown-card">
                            <div class="countdown-actions">
                                <button class="btn-edit" onclick="editCountdown(<?php echo $countdown['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteCountdown(<?php echo $countdown['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <h3><?php echo htmlspecialchars($countdown['title']); ?></h3>
                            <?php if (!empty($countdown['description'])): ?>
                                <div class="description"><?php echo htmlspecialchars($countdown['description']); ?></div>
                            <?php endif; ?>
                            <div class="timer">Uplynulé</div>
                            <div class="date">
                                <?php echo date('d.m.Y H:i', strtotime($countdown['target_date'])); ?>
                                <span style="color:#aaa; font-size:0.95em; margin-left:10px;">
                                    (<?php echo timeAgo($countdown['target_date']); ?>)
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Upraviť odpočítavanie</h2>
            <form id="editForm" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label for="edit_title">Názov</label>
                    <input type="text" id="edit_title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="edit_description">Popis</label>
                    <textarea id="edit_description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_target_date">Dátum a čas</label>
                    <input type="datetime-local" id="edit_target_date" name="target_date" required>
                </div>
                <button type="submit" class="btn">Uložiť zmeny</button>
            </form>
        </div>
    </div>

    <script>
        function updateCountdowns() {
            document.querySelectorAll('.timer:not(.expired)').forEach(timer => {
                const targetDate = new Date(timer.dataset.target).getTime();
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    timer.innerHTML = "Uplynulé";
                    timer.classList.add('expired');
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

        // Modal functionality
        const modal = document.getElementById('editModal');
        const closeBtn = document.querySelector('.close');

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function editCountdown(id) {
            // Here you would typically fetch the countdown data from the server
            // For now, we'll just show the modal
            document.getElementById('edit_id').value = id;
            modal.style.display = "block";
        }

        function deleteCountdown(id) {
            if (confirm('Naozaj chcete vymazať toto odpočítavanie?')) {
                // Here you would typically send a delete request to the server
                window.location.href = `delete_countdown.php?id=${id}`;
            }
        }
    </script>
</body>
</html> 