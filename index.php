<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

$movies = getMovies($conn, false); // Get unwatched movies
$watched = getMovies($conn, true); // Get watched movies
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextFlick - Zoznam filmov</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>NextFlick</h1>
            <button id="addMovieBtn" class="btn-primary"><i class="fas fa-plus"></i> Pridať film</button>
        </header>

        <main>
            <section class="movie-section">
                <h2>Filmy na pozretie</h2>
                <div class="movie-list">
                    <?php if (empty($movies)): ?>
                        <p class="empty-list">Žiadne filmy na pozretie. Pridajte nový!</p>
                    <?php else: ?>
                        <?php foreach ($movies as $movie): ?>
                            <div class="movie-card" data-id="<?= htmlspecialchars($movie['id']) ?>">
                                <?php if (!empty($movie['image_url'])): ?>
                                    <div class="movie-image">
                                        <img src="<?= htmlspecialchars($movie['image_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="movie-header">
                                    <h3><?= htmlspecialchars($movie['title']) ?></h3>
                                    <div class="movie-actions">
                                        <button class="btn-watch" title="Označiť ako pozrené">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-delete" title="Vymazať">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="movie-details">
                                    <?php if (!empty($movie['year'])): ?>
                                        <span class="movie-year"><?= htmlspecialchars($movie['year']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($movie['genre'])): ?>
                                        <span class="movie-genre"><?= htmlspecialchars($movie['genre']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($movie['description'])): ?>
                                    <p class="movie-description"><?= htmlspecialchars($movie['description']) ?></p>
                                <?php endif; ?>
                                
                                <div class="ratings-container">
                                    <div class="user-rating">
                                        <span class="user-name">Miuš:</span>
                                        <div class="rating rating-mia" data-id="<?= htmlspecialchars($movie['id']) ?>" data-rating="<?= htmlspecialchars($movie['rating_mia'] ?? 0) ?>" data-rater="mia">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="dino-rating <?= ($i <= ($movie['rating_mia'] ?? 0)) ? 'active' : '' ?>" data-rating="<?= $i ?>">
                                                    <img src="img/svg_dino.svg" alt="Dinosaur" class="dino-img">
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="user-rating">
                                        <span class="user-name">Tomino:</span>
                                        <div class="rating rating-tomino" data-id="<?= htmlspecialchars($movie['id']) ?>" data-rating="<?= htmlspecialchars($movie['rating_tomino'] ?? 0) ?>" data-rater="tomino">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="dino-rating <?= ($i <= ($movie['rating_tomino'] ?? 0)) ? 'active' : '' ?>" data-rating="<?= $i ?>">
                                                    <img src="img/svg_dino.svg" alt="Dinosaur" class="dino-img">
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="user-rating average-rating">
                                        <span class="user-name">Priemer:</span>
                                        <div class="rating rating-average" data-id="<?= htmlspecialchars($movie['id']) ?>" data-rating="<?= htmlspecialchars($movie['rating'] ?? 0) ?>">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="dino-rating read-only <?= ($i <= ($movie['rating'] ?? 0)) ? 'active' : '' ?>" data-rating="<?= $i ?>">
                                                    <img src="img/svg_dino.svg" alt="Dinosaur" class="dino-img">
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section class="movie-section watched">
                <h2>Pozreté filmy</h2>
                <div class="movie-list">
                    <?php if (empty($watched)): ?>
                        <p class="empty-list">Žiadne pozreté filmy.</p>
                    <?php else: ?>
                        <?php foreach ($watched as $movie): ?>
                            <div class="movie-card watched" data-id="<?= htmlspecialchars($movie['id']) ?>">
                                <?php if (!empty($movie['image_url'])): ?>
                                    <div class="movie-image">
                                        <img src="<?= htmlspecialchars($movie['image_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="movie-header">
                                    <h3><?= htmlspecialchars($movie['title']) ?></h3>
                                    <div class="movie-actions">
                                        <button class="btn-unwatch" title="Označiť ako nepozrené">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button class="btn-delete" title="Vymazať">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="movie-details">
                                    <?php if (!empty($movie['year'])): ?>
                                        <span class="movie-year"><?= htmlspecialchars($movie['year']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($movie['genre'])): ?>
                                        <span class="movie-genre"><?= htmlspecialchars($movie['genre']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($movie['description'])): ?>
                                    <p class="movie-description"><?= htmlspecialchars($movie['description']) ?></p>
                                <?php endif; ?>
                                
                                <div class="ratings-container">
                                    <div class="user-rating">
                                        <span class="user-name">Miuš:</span>
                                        <div class="rating rating-mia read-only" data-id="<?= htmlspecialchars($movie['id']) ?>" data-rating="<?= htmlspecialchars($movie['rating_mia'] ?? 0) ?>" data-rater="mia">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="dino-rating <?= ($i <= ($movie['rating_mia'] ?? 0)) ? 'active' : '' ?>" data-rating="<?= $i ?>">
                                                    <img src="img/svg_dino.svg" alt="Dinosaur" class="dino-img">
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="user-rating">
                                        <span class="user-name">Tomino:</span>
                                        <div class="rating rating-tomino read-only" data-id="<?= htmlspecialchars($movie['id']) ?>" data-rating="<?= htmlspecialchars($movie['rating_tomino'] ?? 0) ?>" data-rater="tomino">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="dino-rating <?= ($i <= ($movie['rating_tomino'] ?? 0)) ? 'active' : '' ?>" data-rating="<?= $i ?>">
                                                    <img src="img/svg_dino.svg" alt="Dinosaur" class="dino-img">
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="user-rating average-rating">
                                        <span class="user-name">Priemer:</span>
                                        <div class="rating rating-average read-only" data-id="<?= htmlspecialchars($movie['id']) ?>" data-rating="<?= htmlspecialchars($movie['rating'] ?? 0) ?>">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="dino-rating <?= ($i <= ($movie['rating'] ?? 0)) ? 'active' : '' ?>" data-rating="<?= $i ?>">
                                                    <img src="img/svg_dino.svg" alt="Dinosaur" class="dino-img">
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <!-- Modal pre pridanie filmu -->
        <div id="addMovieModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pridať nový film</h2>
                <form id="addMovieForm" action="add_movie.php" method="post">
                    <div class="form-group">
                        <label for="title">Názov filmu*:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="image_url">Link na obrázok:</label>
                        <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="form-group">
                        <label for="description">Popis:</label>
                        <textarea id="description" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Pridať film</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html> 