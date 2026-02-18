<?php if (!isset($_COOKIE['cookie_consent'])): ?>
<dialog id="cookie-modal">
  <div class="cookie-header">
    <img src="assets/images/icon/cookie.svg" alt="cookie" width="24" height="24">
    <span>Cookies</span>
  </div>
  <p>Nous utilisons des cookies pour améliorer votre expérience sur Camagru.</p>
  <div class="cookie-actions">
    <button id="accept-cookies">Accepter</button>
    <button id="decline-cookies">Refuser</button>
  </div>
</dialog>
<?php endif; ?>