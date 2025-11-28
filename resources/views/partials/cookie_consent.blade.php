@if (!request()->cookie('cookie_consent') && (empty($cookieConsentNeeded) || $cookieConsentNeeded))
    <div id="cookie-consent-banner" style="position: fixed; bottom: 0; left: 0; right: 0; background: #333; color: #fff; padding: 1rem; z-index: 9999; text-align: center;">
        <p style="margin: 0 0 0.5rem 0;">Utilizamos cookies para mejorar su experiencia. Al continuar navegando, acepta nuestra <a href="{{ route('privacy.show') }}" style="color: #ffc107;">Política de Privacidad</a>.</p>
        <button id="accept-cookies-btn" style="background: #ffc107; border: none; padding: 0.5rem 1.5rem; cursor: pointer; font-weight: bold;">Aceptar</button>
    </div>

    <script src="{{ asset('js/cookie-consent.js') }}"></script>
@endif
