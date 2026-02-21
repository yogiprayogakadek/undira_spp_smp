<script src="{{ asset('assets/backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/app.init.js') }}"></script>
<script src="{{ asset('assets/backend/js/theme.js') }}"></script>
<script src="{{ asset('assets/backend/js/app.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/sidebarmenu-default.js') }}"></script>

<!-- solar icons -->
<script src="{{ asset('assets/backend/js/iconify-icon.min.js') }}"></script>

<!-- highlight.js (code view) -->
<script src="{{ asset('assets/backend/js/highlight.min.js') }}"></script>
<script>
    hljs.initHighlightingOnLoad();


    document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
        codeBlock.textContent = codeBlock.innerHTML;
    });
</script>

{{-- STACK SCRIPT / JS --}}
@stack('script')
