@push('styles')
<style>
    #devtools-warning {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(0,0,0,0.97);
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        text-align: center;
        padding: 2rem;
    }
    #devtools-warning.show { display: flex; }
    #devtools-warning i { font-size: 3rem; color: #ff5b1d; }
    #devtools-warning h2 { color: #fff; font-size: 1.5rem; font-weight: 700; }
    #devtools-warning p { color: #94a3b8; font-size: 0.95rem; max-width: 420px; }
</style>
@endpush

{{-- DevTools Warning Overlay --}}
<div id="devtools-warning" role="alert">
    <i class="fas fa-shield-alt"></i>
    <h2>Access Restricted</h2>
    <p>Developer tools are not permitted on this page to protect copyrighted content. Please close DevTools to continue watching.</p>
</div>

@push('scripts')
<script>
(function() {
    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });

    // Block DevTools keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // F12
        if (e.key === 'F12') { e.preventDefault(); return false; }
        // Ctrl+Shift+I / Ctrl+Shift+J / Ctrl+Shift+C
        if (e.ctrlKey && e.shiftKey && ['I','i','J','j','C','c'].includes(e.key)) {
            e.preventDefault(); return false;
        }
        // Ctrl+U (view source)
        if (e.ctrlKey && ['U','u'].includes(e.key)) {
            e.preventDefault(); return false;
        }
        // Ctrl+S (save page)
        if (e.ctrlKey && ['S','s'].includes(e.key)) {
            e.preventDefault(); return false;
        }
    });

    // Detect DevTools via window size heuristic
    var devtoolsWarning = document.getElementById('devtools-warning');
    var threshold = 160; // px — typical DevTools panel width/height

    function checkDevTools() {
        var widthDiff  = window.outerWidth  - window.innerWidth;
        var heightDiff = window.outerHeight - window.innerHeight;
        if (widthDiff > threshold || heightDiff > threshold) {
            devtoolsWarning?.classList.add('show');
        } else {
            devtoolsWarning?.classList.remove('show');
        }
    }

    // Console-trap approach (secondary signal)
    var devtools = { open: false };
    var element = new Image();
    Object.defineProperty(element, 'id', {
        get: function() {
            devtools.open = true;
            devtoolsWarning?.classList.add('show');
        }
    });

    setInterval(function() {
        checkDevTools();
        devtools.open = false;
        console.log('%c', element); // triggers getter if DevTools console is open
        if (devtools.open) {
            devtoolsWarning?.classList.add('show');
        }
    }, 1000);

    window.addEventListener('resize', checkDevTools);
})();
</script>
@endpush
