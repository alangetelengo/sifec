<script>
    (function () {
        function resetSubmit(btn) {
            if (!btn) return;
            btn.removeAttribute('data-sifec-submitting');
            btn.disabled = false;
            btn.removeAttribute('aria-busy');
            btn.classList.remove('sifec-btn-loading');
            var html = btn.getAttribute('data-sifec-html');
            if (html) btn.innerHTML = html;
        }
        $(document).on('submit', 'form.js-sff-form', function () {
            var btn = this.querySelector('button.sff-btn-submit[type="submit"]');
            if (!btn || btn.getAttribute('data-sifec-submitting') === '1') return;
            btn.setAttribute('data-sifec-submitting', '1');
            if (!btn.getAttribute('data-sifec-html')) {
                btn.setAttribute('data-sifec-html', btn.innerHTML);
            }
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            btn.classList.add('sifec-btn-loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Enregistrement…';
        });
    })();
</script>
