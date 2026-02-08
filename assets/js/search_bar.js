document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchGrayIcon = document.querySelector('.icon.search-gray');
    const searchCrossSurronded = document.querySelector('.search-cross-surronded');
    const searchCross = document.querySelector('.search-cross'); // Ajoute cette ligne
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
    
    // Fermeture de la search bar
    if (searchCross) {
        searchCross.addEventListener('click', function() {
            searchInput.value = ''; // Vide l'input
            searchGrayIcon.style.display = 'block';
            searchCrossSurronded.style.display = 'none';
            document.getElementById('search-results').innerHTML = ''; // Vide les résultats
            document.getElementById('search-bar').style.display = 'none'; // Ferme la search bar
        });
    }
    
    // Reste du code...
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length > 0) {
            searchGrayIcon.style.display = 'none';
            searchCrossSurronded.style.display = 'block';
        } else {
            searchGrayIcon.style.display = 'block';
            searchCrossSurronded.style.display = 'none';
            document.getElementById('search-results').innerHTML = '';
            return;
        }
        
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            fetchSearchResults(query);
        }, 300);
    });
    
    function fetchSearchResults(query) {
        fetch(`?action=search_users&q=${encodeURIComponent(query)}`)
            .then(response => response.text())
            .then(data => {
                try {
                    const jsonData = JSON.parse(data);
                    displayResults(jsonData);
                } catch(e) {
                    console.error('Erreur parsing JSON:', e);
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
                <div class="search-result-item" onclick="window.location.href='?action=user_profile&username=${user.username}'">
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