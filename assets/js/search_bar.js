document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchGrayIcon = document.querySelector('.icon.search-gray');
    const searchCrossSurronded = document.querySelector('.search-cross-surronded');
    let searchTimeout;
    
    searchCrossSurronded.style.display = 'none';
    
    searchInput.addEventListener('focus', function() {
        searchGrayIcon.style.display = 'none';
        searchCrossSurronded.style.display = 'block';
    });
    
    searchInput.addEventListener('blur', function() {
        if (this.value.length === 0) {
            searchGrayIcon.style.display = 'block';
            searchCrossSurronded.style.display = 'none';
        }
    });
    
    searchCrossSurronded.addEventListener('click', function() {
        searchInput.value = '';
        searchGrayIcon.style.display = 'block';
        searchCrossSurronded.style.display = 'none';
        document.getElementById('search-results').innerHTML = '';
        searchInput.focus();
    });
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Gestion icônes
        if (query.length > 0) {
            searchGrayIcon.style.display = 'none';
            searchCrossSurronded.style.display = 'block';
        } else {
            searchGrayIcon.style.display = 'block';
            searchCrossSurronded.style.display = 'none';
            document.getElementById('search-results').innerHTML = '';
            return;
        }
        
        // Annule la recherche précédente
        clearTimeout(searchTimeout);
        
        // Attend 300ms après que l'utilisateur arrête de taper
        searchTimeout = setTimeout(() => {
            fetchSearchResults(query);
        }, 300);
    });
    
function fetchSearchResults(query) {
    fetch(`?action=search_users&q=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(data => {
            console.log('Réponse brute:', data);
            try {
                const jsonData = JSON.parse(data);
                console.log('JSON parsé:', jsonData); // AJOUTE ÇA
                displayResults(jsonData);
            } catch(e) {
                console.error('Erreur parsing JSON:', e);
                console.error('Données reçues:', data);
            }
        })
        .catch(error => {
            console.error('Erreur recherche:', error);
        });
}
    
    function displayResults(users) {
        const resultsContainer = document.getElementById('search-results');
        
        if (users.length === 0) {
            resultsContainer.innerHTML = '<div class="search-empty">No results found.</div>';
            return;
        }
        
        let html = '';
        users.forEach(user => {
            html += `
                <div class="search-result-item" onclick="window.location.href='?action=profile&username=${user.username}'">
                    <img class="search-result-avatar" src="${user.avatar}" alt="${user.username}">
                    <div class="search-result-info">
                        <div class="search-result-username">${user.username}</div>
                        <div class="search-result-fullname">${user.fullname}</div>
                    </div>
                </div>
            `;
        });
        
        resultsContainer.innerHTML = html;
    }
});