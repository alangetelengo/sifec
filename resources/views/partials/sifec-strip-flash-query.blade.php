{{--
  Retire sifec_inline / sifec_err_inline / sifec_notice de la barre d’adresse après lecture côté serveur
  (session-flash, session-toastr). Exécution synchrone dès le <head> : évite l’effet « URL longue puis retour »
  après le chargement complet + jQuery document ready.
--}}
@if (request()->query('sifec_inline') || request()->query('sifec_err_inline') || request()->query('sifec_notice'))
    <script>
        (function () {
            if (!window.history || !window.history.replaceState) {
                return;
            }
            try {
                var u = new URL(window.location.href);
                if (!u.searchParams.has('sifec_inline') && !u.searchParams.has('sifec_err_inline') && !u.searchParams.has('sifec_notice')) {
                    return;
                }
                u.searchParams.delete('sifec_inline');
                u.searchParams.delete('sifec_err_inline');
                u.searchParams.delete('sifec_notice');
                var q = u.searchParams.toString();
                window.history.replaceState({}, '', u.pathname + (q ? '?' + q : '') + u.hash);
            } catch (e) { /* navigateur ancien */ }
        })();
    </script>
@endif
