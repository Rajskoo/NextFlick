document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('addMovieModal');
    const addBtn = document.getElementById('addMovieBtn');
    const closeBtn = document.querySelector('.close');
    
    addBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });
    
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Watch/Unwatch movie functionality
    document.querySelectorAll('.btn-watch').forEach(button => {
        button.addEventListener('click', function() {
            const movieCard = this.closest('.movie-card');
            const movieId = movieCard.dataset.id;
            
            // Send request to mark movie as watched
            fetch('update_movie.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${movieId}&action=watch`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the movie card from the unwatched section and add to watched
                    movieCard.remove();
                    
                    // Reload the page to show updated lists
                    location.reload();
                } else {
                    alert('Chyba: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Nastala chyba pri označovaní filmu ako pozretého.');
            });
        });
    });

    document.querySelectorAll('.btn-unwatch').forEach(button => {
        button.addEventListener('click', function() {
            const movieCard = this.closest('.movie-card');
            const movieId = movieCard.dataset.id;
            
            // Send request to mark movie as unwatched
            fetch('update_movie.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${movieId}&action=unwatch`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the movie card from the watched section and add to unwatched
                    movieCard.remove();
                    
                    // Reload the page to show updated lists
                    location.reload();
                } else {
                    alert('Chyba: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Nastala chyba pri označovaní filmu ako nepozretého.');
            });
        });
    });

    // Delete movie functionality
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Naozaj chcete vymazať tento film?')) {
                const movieCard = this.closest('.movie-card');
                const movieId = movieCard.dataset.id;
                
                // Send request to delete movie
                fetch('delete_movie.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${movieId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the movie card from the DOM
                        movieCard.remove();
                    } else {
                        alert('Chyba: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Nastala chyba pri vymazávaní filmu.');
                });
            }
        });
    });

    // Rating functionality
    document.querySelectorAll('.dino-rating').forEach(dino => {
        dino.addEventListener('click', function() {
            const ratingContainer = this.closest('.rating');
            const movieId = ratingContainer.dataset.id;
            const rating = this.dataset.rating;
            
            // Update UI
            const allDinos = ratingContainer.querySelectorAll('.dino-rating');
            allDinos.forEach(d => {
                if (d.dataset.rating <= rating) {
                    d.classList.add('active');
                } else {
                    d.classList.remove('active');
                }
            });
            
            // Send request to update rating
            fetch('update_rating.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${movieId}&rating=${rating}`
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Chyba: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Nastala chyba pri hodnotení filmu.');
            });
        });
    });

    // Rating functionality for Mia and Tomino (only for unwatched movies)
    document.querySelectorAll('.rating-mia:not(.read-only) .dino-rating, .rating-tomino:not(.read-only) .dino-rating').forEach(dino => {
        dino.addEventListener('click', function() {
            const ratingContainer = this.closest('.rating');
            const movieId = ratingContainer.dataset.id;
            const rating = this.dataset.rating;
            const rater = ratingContainer.dataset.rater;
            
            // Update UI
            const allDinos = ratingContainer.querySelectorAll('.dino-rating');
            allDinos.forEach(d => {
                if (d.dataset.rating <= rating) {
                    d.classList.add('active');
                } else {
                    d.classList.remove('active');
                }
            });
            
            // Send request to update rating
            fetch('update_user_rating.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${movieId}&rating=${rating}&rater=${rater}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload to update average
                    location.reload();
                } else {
                    alert('Chyba: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Nastala chyba pri hodnotení filmu.');
            });
        });
    });

    // Remove any existing event listeners on the average rating
    document.querySelectorAll('.rating-average .dino-rating').forEach(dino => {
        dino.style.pointerEvents = 'none'; // Additional measure to prevent interaction
    });

    // Form submission
    const addMovieForm = document.getElementById('addMovieForm');
    
    addMovieForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('add_movie.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the modal and reset the form
                modal.style.display = 'none';
                addMovieForm.reset();
                
                // Reload the page to show the new movie
                location.reload();
            } else {
                alert('Chyba: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Nastala chyba pri pridávaní filmu.');
        });
    });
}); 