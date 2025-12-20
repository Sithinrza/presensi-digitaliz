<!-- PWA Component -->
<meta name="theme-color" content="#6777ef">
<link rel="manifest" href="/manifest.json?v=1">
<link rel="apple-touch-icon" href="/icons/digitaliz.png">

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => console.log('Service Worker registered:', reg))
            .catch(err => console.error('Service Worker registration failed:', err));
    });
}
</script>
