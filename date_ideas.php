<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Get all date ideas
$dateIdeas = getDateIdeas($conn);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextFlick - Nápady na rande</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/date_ideas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Nápady na rande</h1>
            <button id="addDateIdeaBtn" class="btn-primary"><i class="fas fa-plus"></i> Pridať nápad</button>
        </header>

        <main>
            <section class="date-ideas-section">
                <div class="date-ideas-list">
                    <?php if (empty($dateIdeas)): ?>
                        <p class="empty-list">Žiadne nápady na rande. Pridajte nový!</p>
                    <?php else: ?>
                        <?php foreach ($dateIdeas as $idea): ?>
                            <div class="date-idea-card" data-id="<?= htmlspecialchars($idea['id']) ?>">
                                <div class="date-idea-header">
                                    <h3><?= htmlspecialchars($idea['title']) ?></h3>
                                    <?php if (!empty($idea['planned_date'])): ?>
                                        <span class="planned-date"><?= htmlspecialchars($idea['planned_date']) ?></span>
                                    <?php endif; ?>
                                    <div class="date-idea-actions">
                                        <?php if ($idea['status'] === 'open'): ?>
                                            <button class="btn-close-idea" title="Uzavrieť nápad">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn-delete" title="Vymazať">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="date-idea-content">
                                    <div class="idea-thread">
                                        <?php 
                                        $textItems = [];
                                        $imageItems = [];
                                        foreach ($idea['thread'] as $item) {
                                            if ($item['type'] === 'text') {
                                                $textItems[] = $item;
                                            } else {
                                                $imageItems[] = $item;
                                            }
                                        }
                                        ?>
                                        
                                        <!-- Text items -->
                                        <?php foreach ($textItems as $item): ?>
                                            <div class="thread-item text">
                                                <div class="thread-item-header">
                                                    <p><?= htmlspecialchars($item['content']) ?></p>
                                                    <?php if ($idea['status'] === 'open'): ?>
                                                        <button class="btn-delete-thread-item" data-item-id="<?= htmlspecialchars($item['id']) ?>" title="Vymazať">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="timestamp"><?= htmlspecialchars($item['created_at']) ?></span>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- Image gallery -->
                                        <?php if (!empty($imageItems)): ?>
                                            <div class="image-gallery">
                                                <h4>Galéria</h4>
                                                <div class="gallery-grid">
                                                    <?php foreach ($imageItems as $item): ?>
                                                        <div class="gallery-item">
                                                            <img src="<?= htmlspecialchars($item['content']) ?>" 
                                                                 alt="Date idea image"
                                                                 onclick="openImageModal(this.src)">
                                                            <?php if ($idea['status'] === 'open'): ?>
                                                                <button class="btn-delete-thread-item" 
                                                                        data-item-id="<?= htmlspecialchars($item['id']) ?>" 
                                                                        title="Vymazať">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                            <span class="timestamp"><?= htmlspecialchars($item['created_at']) ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($idea['status'] === 'open'): ?>
                                        <form class="add-thread-item-form" data-idea-id="<?= htmlspecialchars($idea['id']) ?>">
                                            <div class="form-group">
                                                <textarea name="content" placeholder="Pridajte poznámku..."></textarea>
                                                <input type="url" name="image_url" placeholder="URL obrázka" class="image-url">
                                                <button type="submit" class="btn-primary">Pridať</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <!-- Modal pre pridanie nápadu -->
        <div id="addDateIdeaModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pridať nový nápad</h2>
                <form id="addDateIdeaForm" action="add_date_idea.php" method="post">
                    <div class="form-group">
                        <label for="title">Názov nápadu*:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="planned_date">Plánovaný dátum:</label>
                        <input type="date" id="planned_date" name="planned_date">
                    </div>
                    <button type="submit" class="btn-primary">Pridať nápad</button>
                </form>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="modalImage">
        </div>
    </div>

    <script src="js/date_ideas.js"></script>
</body>
</html> 