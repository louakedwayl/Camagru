<?php if (!isset($_COOKIE['cookie_consent'])): ?>
<dialog id="cookie-modal">
  <div class="cookie-header">
    <img src="assets/images/icon/cookie.svg" alt="cookie" width="24" height="24">
    <span>Cookies</span>
  </div>
  <p>Nous utilisons des cookies pour améliorer votre expérience sur Camagru.</p>
  <div class="cookie-demo">
    <p class="cookie-demo-text">Pour tester toutes les fonctionnalités, connectez-vous avec le compte de démonstration ou créez le vôtre.</p>
    <div class="cookie-demo-credentials">
      <span><strong>Identifiant :</strong> Wayl</span>
      <span><strong>Mot de passe :</strong> Password123</span>
    </div>
  </div>
  <div class="cookie-actions">
    <button id="accept-cookies">Accepter</button>
    <button id="decline-cookies">Refuser</button>
  </div>
</dialog>
<?php endif; ?>