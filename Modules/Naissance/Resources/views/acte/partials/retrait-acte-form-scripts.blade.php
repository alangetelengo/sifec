@php
    /** @var \Modules\Naissance\Entities\ActeNaissance $acte */
    $niuppRetrait = $acte->niupp ?? '';
@endphp
@if($niuppRetrait !== '')
<script>
(function ($) {
    var RETRAIT_NIUPP = @json($niuppRetrait);
    var RETRAIT_URL = @json(route('acteNaissance.retrait'));
    var CSRF = @json(csrf_token());

    function initModalRetrait() {
        var now = new Date();
        var dateStr = now.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        $('#date_retrait').val(dateStr);

        $('#modal-retrait-acte').off('show.bs.modal.sifecRetrait').on('show.bs.modal.sifecRetrait', function () {
            openRetraitModal();
        });

        $('#nom_interesse, #telephone_interesse').off('input.sifecRetrait').on('input.sifecRetrait', function () {
            validateField($(this));
        });

        $('#telephone_interesse').off('blur.sifecRetrait').on('blur.sifecRetrait', function () {
            validatePhone($(this));
        });

        $('#btn-retrait').off('click.sifecRetrait').on('click.sifecRetrait', function (e) {
            e.preventDefault();
            handleRetraitActe();
        });

        $('#modal-retrait-acte').off('hidden.bs.modal.sifecRetrait').on('hidden.bs.modal.sifecRetrait', function () {
            resetRetraitForm();
        });
    }

    function openRetraitModal() {
        $('#code_acte').val(RETRAIT_NIUPP);
        $('#leniupp').val(RETRAIT_NIUPP);

        var now = new Date();
        var dateStr = now.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        $('#date_retrait').val(dateStr);

        $('#nom_interesse').val('').removeClass('is-valid is-invalid');
        $('#prenom_interesse').val('').removeClass('is-valid is-invalid');
        $('#telephone_interesse').val('').removeClass('is-valid is-invalid');
        $('#piece_identite').val('');
        $('#numero_piece_identite').val('');
        $('#observations_retrait').val('');
        $('#form-retrait-acte .invalid-feedback').text('');

        setTimeout(function () {
            $('#nom_interesse').focus();
        }, 400);
    }

    function validateField(field) {
        var value = field.val().trim();
        var fieldId = field.attr('id');
        var errorDiv = $('#' + fieldId + '_error');

        field.removeClass('is-invalid is-valid');
        errorDiv.text('');

        if (field.prop('required') && !value) {
            field.addClass('is-invalid');
            errorDiv.text('Ce champ est obligatoire');
            return false;
        }

        if (value) {
            field.addClass('is-valid');
        }
        return true;
    }

    function validatePhone(field) {
        var phone = field.val().trim();
        var phoneRegex = /^[+]?[0-9\s\-\(\)]{8,20}$/;

        if (phone && !phoneRegex.test(phone)) {
            field.addClass('is-invalid');
            $('#telephone_interesse_error').text('Format de téléphone invalide');
            return false;
        }
        return true;
    }

    function validateRetraitForm() {
        var isValid = true;
        ['#nom_interesse', '#telephone_interesse'].forEach(function (fieldId) {
            if (!validateField($(fieldId))) {
                isValid = false;
            }
        });
        if (!validatePhone($('#telephone_interesse'))) {
            isValid = false;
        }
        return isValid;
    }

    function handleRetraitActe() {
        if (!validateRetraitForm()) {
            if (typeof flashAlert === 'function') {
                flashAlert('Erreur de validation', 'error', 'Veuillez corriger les erreurs dans le formulaire.');
            }
            return;
        }

        var btn = $('#btn-retrait');
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(btn[0], 'Enregistrement...');
        }

        var data = {
            niupp: $('#leniupp').val(),
            nominteresse: $('#nom_interesse').val().trim(),
            prenominteresse: $('#prenom_interesse').val().trim(),
            telephoneinteresse: $('#telephone_interesse').val().trim(),
            piece_identite: $('#piece_identite').val(),
            numero_piece_identite: $('#numero_piece_identite').val().trim(),
            observations: $('#observations_retrait').val().trim(),
            _token: CSRF
        };

        $.ajax({
            url: RETRAIT_URL,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.code == '200') {
                    if (typeof flashAlert === 'function') {
                        flashAlert('Succès', 'success', response.message.reponse || response.message);
                    }
                    $('#modal-retrait-acte').modal('hide');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else {
                    handleRetraitError(response);
                }
            },
            error: function (xhr) {
                var errorMessage = 'Erreur lors du retrait de l\'acte';
                var j = xhr.responseJSON;
                if (j && j.message) {
                    if (typeof j.message === 'object' && j.message.error) {
                        errorMessage = j.message.error;
                    } else if (typeof j.message === 'string') {
                        errorMessage = j.message;
                    }
                } else if (xhr.status === 403) {
                    errorMessage = 'Accès refusé : vous n\'avez pas l\'habilitation pour enregistrer ce retrait.';
                }
                if (typeof flashAlert === 'function') {
                    flashAlert('Erreur', 'error', errorMessage);
                }
            },
            complete: function () {
                if (typeof sifecBtnReset === 'function') {
                    sifecBtnReset(btn[0], 'Enregistrer le retrait');
                }
            }
        });
    }

    function handleRetraitError(response) {
        var errorMessage = '';
        if (typeof response.message === 'object') {
            if (response.message.error) {
                errorMessage = response.message.error;
            } else {
                var errors = [];
                Object.keys(response.message).forEach(function (key) {
                    var value = response.message[key];
                    if (Array.isArray(value)) {
                        value.forEach(function (v) { errors.push(v); });
                    } else {
                        errors.push(value);
                    }
                });
                errorMessage = errors.length > 0 ? errors.join('<br>') : 'Une erreur est survenue';
            }
        } else {
            errorMessage = response.message || 'Une erreur est survenue';
        }
        if (typeof flashAlert === 'function') {
            flashAlert('Erreur', 'error', errorMessage);
        }
    }

    function resetRetraitForm() {
        var f = document.getElementById('form-retrait-acte');
        if (f) {
            f.reset();
        }
        $('#form-retrait-acte .form-control').removeClass('is-valid is-invalid');
        $('#form-retrait-acte .invalid-feedback').text('');
        $('#date_retrait').val('');
    }

    $(function () {
        initModalRetrait();
    });
})(jQuery);
</script>
@endif
