document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('accept-cookies-btn');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        var tokenMeta = document.querySelector('meta[name="csrf-token"]');
        var token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        fetch('/cookies/accept', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(function (resp) {
            if (!resp.ok) throw new Error('Network response was not ok');
            return resp.json();
        })
        .then(function (data) {
            var banner = document.getElementById('cookie-consent-banner');
            if (banner) banner.style.display = 'none';
            try { localStorage.setItem('cookie_consent_accepted', '1'); } catch (e) {}
        })
        .catch(function (err) {
            console.error('Error accepting cookies:', err);
        });
    });
});
