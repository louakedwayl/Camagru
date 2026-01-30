const iconHome = document.querySelector("img.house");
iconHome.src = "assets/images/icon/home_black.svg"

document.addEventListener('DOMContentLoaded', function() {
    const moreLinks = document.querySelectorAll('.more-link');
    
    moreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const captionContainer = this.closest('.caption-container');
            const caption = captionContainer.querySelector('.post-caption');
            const fullText = caption.getAttribute('data-full-text');
            
            // Afficher le texte complet
            caption.textContent = fullText;
            caption.classList.add('expanded');
            
            // Supprimer complètement le lien "more"
            this.remove();
        });
    });
});