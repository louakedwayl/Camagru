<?php if (!isset($_COOKIE['cookie_consent'])): ?>
<dialog id="cookie-modal">
  <div class="cookie-body">
    <img class="cookie-icon" src="assets/images/icon/cookie.svg" alt="cookie" width="48" height="48">
    <h2 class="cookie-title">Autoriser les cookies&nbsp;?</h2>
    <p class="cookie-text">Nous utilisons des cookies pour am&eacute;liorer votre exp&eacute;rience sur Camagru.</p>
    <div class="cookie-demo">
      <p class="cookie-demo-text">Pour tester toutes les fonctionnalit&eacute;s, connectez-vous avec le compte de d&eacute;monstration ou cr&eacute;ez le v&ocirc;tre.</p>
      <div class="cookie-demo-credentials">
        <span><strong>Identifiant&nbsp;:</strong> Wayl</span>
        <span><strong>Mot de passe&nbsp;:</strong> Password123</span>
      </div>
    </div>
  </div>
  <div class="cookie-actions">
    <button id="accept-cookies">Accepter</button>
    <button id="decline-cookies">Refuser</button>
  </div>
</dialog>
<?php endif; ?>
