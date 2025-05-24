<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="main-header">
    <nav class="nav-container">
        <div class="logo">
            <span class="logo-main">mypookie</span>
            <span class="logo-sub">EVERY DAY WITH YOU IS A BETTER DAY!</span>
        </div>
        <button class="nav-toggle" aria-label="Otvoriť menu" onclick="toggleNavMenu()">
            &#9776;
        </button>
        <div class="nav-links">
            <a href="index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>" onclick="closeNavMenuOnMobile()">
                Odpočítavanie
            </a>
            <a href="movies.php" class="<?php echo $current_page === 'movies.php' ? 'active' : ''; ?>" onclick="closeNavMenuOnMobile()">
                Filmy
            </a>
            <a href="date_ideas.php" class="<?php echo $current_page === 'date_ideas.php' ? 'active' : ''; ?>" onclick="closeNavMenuOnMobile()">
                Nápady na rande
            </a>
        </div>
        <div class="menu-overlay" onclick="toggleNavMenu()"></div>
    </nav>
</header>

<style>
.main-header {
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1.2rem 0 0.7rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.logo {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 0;
    margin-right: 3rem;
    justify-content: center;
}
.logo-main {
    font-family: 'Georgia', serif;
    font-size: 2rem;
    font-weight: 500;
    letter-spacing: 1px;
    color: #222;
    line-height: 1.1;
    white-space: nowrap;
}
.logo-sub {
    font-size: 0.7rem;
    letter-spacing: 0.12em;
    color: #bbb;
    font-family: 'Roboto', Arial, sans-serif;
    margin-top: 0.2rem;
    font-weight: 400;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.nav-links {
    display: flex;
    gap: 2rem;
    justify-content: flex-end;
    align-items: center;
    z-index: 1102;
}

.nav-links a {
    color: #333;
    text-decoration: none;
    font-size: 1rem;
    padding: 0.5rem 0.8rem;
    border-radius: 5px;
    transition: background 0.2s, color 0.2s, text-decoration 0.2s;
    white-space: nowrap;
}

.nav-links a:hover {
    background: #eaf4fb;
    color: #217dbb;
    text-decoration: underline;
}

.nav-links a.active {
    color: #3498db;
    font-weight: 500;
}

.nav-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.7rem;
    cursor: pointer;
    color: #3498db;
    z-index: 1103;
    margin-left: 1.5rem;
    padding: 0.5rem 0.7rem;
    border-radius: 6px;
    transition: background 0.2s;
}
.nav-toggle:active, .nav-toggle:focus {
    background: #eaf4fb;
}

.menu-overlay {
    display: none;
    position: fixed;
    z-index: 1099;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.18);
}
.menu-overlay.active {
    display: block;
}

@media (max-width: 900px) {
    .nav-links {
        gap: 1rem;
    }
    .logo-main {
        font-size: 1.5rem;
    }
}

@media (max-width: 600px) {
    .nav-container {
        padding: 0 0.2rem;
    }
    .logo {
        align-items: flex-start;
        margin: 0;
        padding-top: 0;
        flex: 1;
    }
    .logo-main { font-size: 1.3rem; }
    .logo-sub { font-size: 0.6rem; color: #ccc; }
    .nav-toggle {
        display: block;
        position: static;
        margin: 0 0 0 1rem;
        align-self: center;
    }
    .nav-links {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        position: fixed;
        top: 56px;
        left: 0;
        right: 0;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 1rem 0 1.5rem 0;
        display: none;
        z-index: 1102;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        animation: fadeInMenu 0.2s;
    }
    .nav-links.open {
        display: flex;
    }
    .nav-links a {
        font-size: 1.1rem;
        padding: 1rem 0.5rem;
        text-align: center;
        width: 100%;
    }
}

@keyframes fadeInMenu {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<script>
function toggleNavMenu() {
    var navLinks = document.querySelector('.nav-links');
    var overlay = document.querySelector('.menu-overlay');
    navLinks.classList.toggle('open');
    overlay.classList.toggle('active');
}
function closeNavMenuOnMobile() {
    if(window.innerWidth <= 600) {
        var navLinks = document.querySelector('.nav-links');
        var overlay = document.querySelector('.menu-overlay');
        navLinks.classList.remove('open');
        overlay.classList.remove('active');
    }
}
</script> 
</style> 