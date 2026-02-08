document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchGrayIcon = document.querySelector('.icon.search-gray');
    const searchCrossSurronded = document.querySelector('.search-cross-surronded');
    
    // Vérifie que les éléments existent
    if (!searchInput || !searchGrayIcon || !searchCrossSurronded) {
        console.error('Éléments manquants:', {
            searchInput,
            searchGrayIcon,
            searchCrossSurronded
        });
        return;
    }
    
    // Cache la croix au chargement
    searchCrossSurronded.style.display = 'none';
    
    // Quand on clique sur l'input
    searchInput.addEventListener('focus', function() {
        searchGrayIcon.style.display = 'none';
        searchCrossSurronded.style.display = 'block';
    });
    
    // Quand on quitte l'input
    searchInput.addEventListener('blur', function() {
        if (this.value.length === 0) {
            searchGrayIcon.style.display = 'block';
            searchCrossSurronded.style.display = 'none';
        }
    });
    
    // Écoute les changements dans l'input
    searchInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            searchGrayIcon.style.display = 'none';
            searchCrossSurronded.style.display = 'block';
        } else {
            searchGrayIcon.style.display = 'block';
            searchCrossSurronded.style.display = 'none';
        }
    });
    
    // Vider l'input quand on clique sur la croix
    searchCrossSurronded.addEventListener('click', function() {
        searchInput.value = '';
        searchGrayIcon.style.display = 'block';
        searchCrossSurronded.style.display = 'none';
        searchInput.focus();
    });
});