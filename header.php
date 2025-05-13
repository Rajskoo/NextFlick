<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="main-header">
    <nav class="nav-container">
        <div class="nav-links">
            <a href="index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                Odpočítavanie
            </a>
            <a href="movies.php" class="<?php echo $current_page === 'movies.php' ? 'active' : ''; ?>">
                Filmy
            </a>
            <a href="date_ideas.php" class="<?php echo $current_page === 'date_ideas.php' ? 'active' : ''; ?>">
                Nápady na rande
            </a>
        </div>
    </nav>
</header>

<style>
.main-header {
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.nav-links {
    display: flex;
    gap: 2rem;
    justify-content: center;
}

.nav-links a {
    color: #333;
    text-decoration: none;
    font-size: 1.1rem;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.nav-links a:hover {
    background: #f5f5f5;
    color: #3498db;
}

.nav-links a.active {
    color: #3498db;
    font-weight: 500;
}
</style> 