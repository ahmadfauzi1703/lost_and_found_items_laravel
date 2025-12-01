  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#6777ef">
  <script src="{{ asset('pwa-install.js') }}" defer></script>
  <script>
    (function registerServiceWorker() {
      if (!('serviceWorker' in navigator)) return;

      const swUrl = "{{ asset('sw.js') }}";

      window.addEventListener('load', function () {
        navigator.serviceWorker
          .register(swUrl)
          .then(function (reg) {
            console.log('Service worker registered for scope:', reg.scope);
          })
          .catch(function (err) {
            console.error('Service worker registration failed:', err);
          });
      });
    })();
  </script>
