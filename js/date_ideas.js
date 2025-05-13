document.addEventListener('DOMContentLoaded', function() {
    // Modal handling
    const modal = document.getElementById('addDateIdeaModal');
    const addBtn = document.getElementById('addDateIdeaBtn');
    const closeBtn = document.querySelector('.close');

    addBtn.onclick = function() {
        modal.style.display = "block";
    }

    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Image Modal handling
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const imageModalClose = imageModal.querySelector('.close');

    window.openImageModal = function(src) {
        imageModal.style.display = "block";
        modalImage.src = src;
    }

    imageModalClose.onclick = function() {
        imageModal.style.display = "none";
    }

    imageModal.onclick = function(event) {
        if (event.target === imageModal) {
            imageModal.style.display = "none";
        }
    }

    // Form submission for new date idea
    const addDateIdeaForm = document.getElementById('addDateIdeaForm');
    addDateIdeaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('add_date_idea.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Chyba pri pridávaní nápadu: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Nastala chyba pri pridávaní nápadu');
        });
    });

    // Thread item form submission
    document.querySelectorAll('.add-thread-item-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const ideaId = this.dataset.ideaId;
            formData.append('date_idea_id', ideaId);
            
            // Check if there's content or an image URL
            const content = formData.get('content');
            const imageUrl = formData.get('image_url');
            
            if (!content && !imageUrl) {
                return; // Don't submit if both are empty
            }
            
            fetch('add_thread_item.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset the form
                    this.reset();
                    window.location.reload();
                } else {
                    alert('Chyba pri pridávaní položky: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Nastala chyba pri pridávaní položky');
            });
        });
    });

    // Close idea button
    document.querySelectorAll('.btn-close-idea').forEach(button => {
        button.addEventListener('click', function() {
            const ideaId = this.closest('.date-idea-card').dataset.id;
            
            fetch('close_date_idea.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: ideaId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Chyba pri uzatváraní nápadu: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Nastala chyba pri uzatváraní nápadu');
            });
        });
    });

    // Delete idea button
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Naozaj chcete vymazať tento nápad?')) {
                const ideaId = this.closest('.date-idea-card').dataset.id;
                
                fetch('delete_date_idea.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: ideaId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Chyba pri mazaní nápadu: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Nastala chyba pri mazaní nápadu');
                });
            }
        });
    });

    // Delete thread item button
    document.querySelectorAll('.btn-delete-thread-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent image modal from opening when clicking delete
            if (confirm('Naozaj chcete vymazať túto položku?')) {
                const itemId = this.dataset.itemId;
                
                fetch('delete_thread_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: itemId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Chyba pri mazaní položky: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Nastala chyba pri mazaní položky');
                });
            }
        });
    });

    // Mobile menu toggle
    document.getElementById('navbarToggle').addEventListener('click', function() {
        document.getElementById('navbarLinks').classList.toggle('open');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const navbar = document.querySelector('.navbar');
        const navbarLinks = document.getElementById('navbarLinks');
        const navbarToggle = document.getElementById('navbarToggle');
        
        if (!navbar.contains(event.target) && navbarLinks.classList.contains('open')) {
            navbarLinks.classList.remove('open');
        }
    });
}); 