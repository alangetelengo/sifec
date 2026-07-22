(function ($) {
  'use strict';

  function flashAlert(title, type, html) {
    if (window.Swal) {
      Swal.fire({
        title: title,
        icon: type === 'danger' ? 'error' : type,
        html: html,
        confirmButtonText: 'OK',
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
      });
    } else {
      window.alert(title + '\n' + (html || '').replace(/<[^>]+>/g, ''));
    }
  }

  function showModal(modalEl) {
    if (!modalEl) {
      return;
    }
    var m = bootstrap.Modal.getOrCreateInstance(modalEl, { backdrop: true, keyboard: true });
    m.show();
  }

  function hideModal(modalEl) {
    if (!modalEl) {
      return;
    }
    var inst = bootstrap.Modal.getInstance(modalEl);
    if (inst) {
      inst.hide();
    }
  }

  function adminCodeFromLabel(lib) {
    var s = String(lib || '');
    if (s.indexOf('DIRECTION DES EXAMENS ET CONCOURS') !== -1) {
      return 'ADM_0001';
    }
    if (s.indexOf('BANQUE CONGOLAISE DE L\'HABITAT') !== -1 || s.indexOf('BCH') !== -1) {
      return 'ADM_0002';
    }
    if (s.indexOf('IDENTIFICATION CIVILE') !== -1 || s.indexOf('DIC') !== -1) {
      return 'ADM_0005';
    }
    return 'ADM_0001';
  }

  function libFromConnexionAdmin(val) {
    var m = {
      dec: 'DIRECTION DES EXAMENS ET CONCOURS (DEC)',
      dic: "DIRECTION DE L'IDENTIFICATION CIVILE(DIC)",
      bch: "BANQUE CONGOLAISE DE L'HABITAT (BCH)",
      lcb: 'LA CONGOLAISE DES BANQUES(LCB)',
    };
    return m[val] || '';
  }

  function tryLegacyConnexion(email, password, adminVal) {
    var adminFull = libFromConnexionAdmin(adminVal);
    var ok = false;
    if (email === 'dec@gmail.com' && password === '123456') {
      ok = true;
    } else if (email === 'bch@gmail.com' && password === '123456') {
      ok = true;
    } else if (email === 'dic@gmail.com' && password === '123456') {
      ok = true;
    }
    if (ok && adminFull) {
      PortailApi.setToken('');
      $('#adminLibLabel').text(adminFull);
      hideModal(document.getElementById('modalConnexion'));
      showModal(document.getElementById('modalAdmin'));
      $('#alertConnexion').empty();
      return;
    }
    $('#alertConnexion').html('<span class="text-danger">Connexion échouée. Veuillez recommencer.</span>');
  }

  function libFromCecRow(row) {
    var lib = row.lib_institution;
    if (lib === undefined && row.LIB_INSTITUTION !== undefined) {
      lib = row.LIB_INSTITUTION;
    }
    if (lib === undefined) {
      var vals = Object.values(row);
      lib = vals.length ? vals[0] : '';
    }
    return lib ? String(lib) : '';
  }

  function renderCecSelectFiltered($select, filterText, libs) {
    var libsArr = libs || window.portailListeCecLibs || [];
    var f = (filterText || '').toLowerCase().trim();
    var previous = $select.val();
    $select.empty();
    $select.append($('<option>').val('').text('— Choisissez un centre —'));
    libsArr.forEach(function (lib) {
      if (!f || lib.toLowerCase().indexOf(f) !== -1) {
        $select.append($('<option>').val(lib).text(lib));
      }
    });
    if (previous) {
      var found = false;
      $select.find('option').each(function () {
        if ($(this).val() === previous) {
          found = true;
        }
      });
      if (found) {
        $select.val(previous);
      }
    }
  }

  function setupListeCecFromApi(liste) {
    var libs = [];
    (liste || []).forEach(function (row) {
      var lib = libFromCecRow(row);
      if (lib) {
        libs.push(lib);
      }
    });
    libs.sort(function (a, b) {
      return a.localeCompare(b, 'fr');
    });
    window.portailListeCecLibs = libs;
    renderCecSelectFiltered($('#demande_cec_traitement'), $('#demande_cec_traitement_filter').val(), libs);
    renderCecSelectFiltered($('#demande_cec_acte'), $('#demande_cec_acte_filter').val(), libs);
  }

  function resetActeVerificationUi() {
    window.portailActeVerifOk = false;
    window.portailActeVerifSeq = (window.portailActeVerifSeq || 0) + 1;
    $('#demande_numero_feedback').empty();
    $('#demande_numero_acte').removeClass('is-valid is-invalid');
  }

  function scheduleVerifActePortail() {
    clearTimeout(window._portailVerifActeTimer);
    window._portailVerifActeTimer = setTimeout(function () {
      runVerifActePortail();
    }, 450);
  }

  function runVerifActePortail() {
    if ($('#demande_TypeCritere').val() !== '1') {
      return;
    }
    var num = ($('#demande_numero_acte').val() || '').trim();
    var typeActe = $('#demande_type_acte').val();
    var $fb = $('#demande_numero_feedback');
    var $inp = $('#demande_numero_acte');

    if (num.length < 2) {
      resetActeVerificationUi();
      $fb.empty();
      $inp.removeClass('is-valid is-invalid');
      window.portailActeVerifOk = false;
      return;
    }

    window.portailActeVerifSeq = (window.portailActeVerifSeq || 0) + 1;
    var seq = window.portailActeVerifSeq;

    $fb.html('<span class="text-muted">Vérification en cours…</span>');
    $inp.removeClass('is-valid is-invalid');
    window.portailActeVerifOk = false;

    PortailApi.post('verifierActePortail', {
      type_acte: typeActe,
      numero_acte: num,
    })
      .done(function (r) {
        if (seq !== window.portailActeVerifSeq) {
          return;
        }
        if (String(r.code) === '200') {
          window.portailActeVerifOk = true;
          $inp.addClass('is-valid').removeClass('is-invalid');
          $fb.html('<span class="text-success">' + escapeHtml(r.message || 'Acte trouvé.') + '</span>');
        } else {
          window.portailActeVerifOk = false;
          $inp.addClass('is-invalid').removeClass('is-valid');
          $fb.html('<span class="text-danger">' + escapeHtml(r.message || 'Acte introuvable.') + '</span>');
        }
      })
      .fail(function () {
        if (seq !== window.portailActeVerifSeq) {
          return;
        }
        window.portailActeVerifOk = false;
        $inp.addClass('is-invalid').removeClass('is-valid');
        $fb.html('<span class="text-danger">Erreur de vérification. Réessayez.</span>');
      });
  }

  function toggleCritereDemande() {
    var v = $('#demande_TypeCritere').val();
    if (v === '1') {
      $('#wrap_numero_acte').show();
      $('#wrap_identite').hide();
      $('#demande_numero_acte').prop('required', true);
      $('#demande_cec_acte').prop('required', false);
      scheduleVerifActePortail();
    } else {
      $('#wrap_numero_acte').hide();
      $('#wrap_identite').show();
      $('#demande_numero_acte').prop('required', false);
      $('#demande_cec_acte').prop('required', true);
      resetActeVerificationUi();
    }
  }

  function renderBanTableRows(rows) {
    var $tb = $('#banTableBody');
    $tb.empty();
    if (!rows.length) {
      $tb.append('<tr><td colspan="6" class="text-muted">Aucune ligne sur cette page.</td></tr>');
      return;
    }
    rows.forEach(function (r) {
      var d = r.DateCelebration || r.dateCelebration || '';
      $tb.append(
        '<tr>' +
          '<td>' + escapeHtml(r.Departement || '') + '</td>' +
          '<td>' + escapeHtml(r.Institution || '') + '</td>' +
          '<td>' + escapeHtml(r.NomEpoux || '') + '</td>' +
          '<td>' + escapeHtml(r.NomEpouse || '') + '</td>' +
          '<td>' + escapeHtml(String(d)) + '</td>' +
          '<td>' + escapeHtml(r.LieuCelebration || '') + '</td>' +
          '</tr>'
      );
    });
  }

  function populateBanFacetSelects(facets) {
    if (!facets) {
      return;
    }
    var $dep = $('#ban_filter_dep');
    var $cec = $('#ban_filter_cec');
    var prevD = $dep.val() || '';
    var prevC = $cec.val() || '';
    $dep.find('option:not(:first)').remove();
    $cec.find('option:not(:first)').remove();
    (facets.departements || []).forEach(function (d) {
      $dep.append($('<option>').val(d).text(d));
    });
    (facets.institutions || []).forEach(function (i) {
      $cec.append($('<option>').val(i).text(i));
    });
    if (prevD) {
      $dep.find('option').each(function () {
        if ($(this).val() === prevD) {
          $dep.val(prevD);
        }
      });
    }
    if (prevC) {
      $cec.find('option').each(function () {
        if ($(this).val() === prevC) {
          $cec.val(prevC);
        }
      });
    }
  }

  function populateBanInstitutionSelectFromFacets(facets) {
    if (!facets) {
      return;
    }
    var $cec = $('#ban_filter_cec');
    var prevC = $cec.val() || '';
    $cec.find('option:not(:first)').remove();
    (facets.institutions || []).forEach(function (i) {
      $cec.append($('<option>').val(i).text(i));
    });
    if (prevC) {
      $cec.find('option').each(function () {
        if ($(this).val() === prevC) {
          $cec.val(prevC);
        }
      });
    }
  }

  function refreshBanInstitutionFacetsForDepartement(depLib) {
    var params = {
      per_page: 1,
      page: 1,
      include_facets: 1,
    };
    if (depLib) {
      params.facet_departement = depLib;
    }
    PortailApi.get('banMariagePublic', params).done(function (resp) {
      if (resp.facets) {
        populateBanInstitutionSelectFromFacets(resp.facets);
      }
    });
  }

  function loadBanPublic(options) {
    options = options || {};
    var page = options.page != null ? options.page : 1;
    var params = {
      per_page: 50,
      page: page,
    };
    // Toujours charger les listes Département / Institution sur la page 1 (fiabilise l’UI si le paramètre booléen était omis ou mal interprété).
    if (page === 1) {
      params.include_facets = 1;
      var depFacet = $('#ban_filter_dep').val() || '';
      if (depFacet) {
        params.facet_departement = depFacet;
      }
    }
    var dep = options.departement != null ? options.departement : $('#ban_filter_dep').val();
    var inst = options.institution != null ? options.institution : $('#ban_filter_cec').val();
    if (dep) {
      params.departement = dep;
    }
    if (inst) {
      params.institution = inst;
    }

    $('#banTableBody').html('<tr><td colspan="6">Chargement…</td></tr>');

    PortailApi.get('banMariagePublic', params)
      .done(function (resp) {
        var rows = (resp && resp.data) || [];
        renderBanTableRows(rows);
        if (resp.facets) {
          populateBanFacetSelects(resp.facets);
        }
        var cur = resp.current_page || 1;
        var last = resp.last_page || 1;
        var tot = resp.total != null ? resp.total : rows.length;
        $('#banPaginationInfo').text(
          'Page ' + cur + ' / ' + last + ' — ' + tot + ' publication(s) au total (max 50 par page).'
        );
        $('#banPagePrev').prop('disabled', cur <= 1);
        $('#banPageNext').prop('disabled', cur >= last);
        window.portailBanCurrentPage = cur;
        window.portailBanLastPage = last;
      })
      .fail(function (xhr) {
        var msg = "Impossible de charger le journal BAN.";
        if (xhr.status === 429) {
          msg = 'Trop de requêtes vers le serveur. Veuillez patienter environ une minute.';
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = String(xhr.responseJSON.message);
        }
        $('#banTableBody').html('<tr><td colspan="6" class="text-danger">' + escapeHtml(msg) + '</td></tr>');
        $('#banPaginationInfo').text('');
      });
  }

  function escapeHtml(t) {
    return String(t)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function setSubmitButtonLoading($btn, loading, loadingLabel) {
    if (!$btn || !$btn.length) {
      return;
    }
    if (loading) {
      if (!$btn.data('portailBtnBusy')) {
        $btn.data('portailBtnBusy', true);
        $btn.data('portailBtnHtml', $btn.html());
      }
      $btn.prop('disabled', true);
      $btn.html(
        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
        escapeHtml(loadingLabel || 'Veuillez patienter…')
      );
    } else {
      if ($btn.data('portailBtnBusy')) {
        $btn.prop('disabled', false);
        $btn.html($btn.data('portailBtnHtml'));
        $btn.removeData('portailBtnBusy');
        $btn.removeData('portailBtnHtml');
      }
    }
  }

  $(function () {
    $('#demande_TypeCritere').on('change', toggleCritereDemande);

    $('#demande_numero_acte').on('input blur', function () {
      if ($('#demande_TypeCritere').val() === '1') {
        scheduleVerifActePortail();
      }
    });

    $('#demande_cec_traitement_filter').on('input', function () {
      renderCecSelectFiltered($('#demande_cec_traitement'), $(this).val(), window.portailListeCecLibs);
    });
    $('#demande_cec_acte_filter').on('input', function () {
      renderCecSelectFiltered($('#demande_cec_acte'), $(this).val(), window.portailListeCecLibs);
    });

    $('#frmWindowParticulierBtnDisplay').on('click', function (e) {
      e.preventDefault();
      showModal(document.getElementById('modalParticulier'));
    });

    $('#frmConnexionBtnDisplay').on('click', function (e) {
      e.preventDefault();
      showModal(document.getElementById('modalConnexion'));
    });

    $(document).on('click', '.btn-service', function (e) {
      e.preventDefault();
      var $t = $(this);
      $('#demande_type_acte').val($t.data('type-acte'));
      $('#demande_type_document').val($t.data('type-doc'));
      $('#modalDemandeTitre').text('Demande : ' + $t.data('type-doc') + " — acte de " + $t.data('type-acte'));
      hideModal(document.getElementById('modalParticulier'));
      $('#demande_numero_acte').val('');
      resetActeVerificationUi();
      $('#demande_cec_traitement_filter').val('');
      $('#demande_cec_acte_filter').val('');
      $('#loaderDemandeActe').show();
      PortailApi.get('listeCec', {})
        .done(function (liste) {
          setupListeCecFromApi(Array.isArray(liste) ? liste : []);
          toggleCritereDemande();
          $('#loaderDemandeActe').hide();
          showModal(document.getElementById('modalDemande'));
        })
        .fail(function () {
          $('#loaderDemandeActe').hide();
          flashAlert('Erreur', 'error', 'Impossible de charger la liste des centres d’état civil.');
        });
    });

    $('#frmLivBtnDisplay').on('click', function (e) {
      e.preventDefault();
      flashAlert('Livret de famille', 'info', 'Formulaire livret : saisie locale (aucun appel API dans la version précédente). Contactez l’administrateur pour brancher le service.');
    });

    $('#frmJournalBtnDisplay').on('click', function (e) {
      e.preventDefault();
      hideModal(document.getElementById('modalParticulier'));
      showModal(document.getElementById('modalJournal'));
      loadBanPublic({ page: 1 });
    });

    $('#ban_filter_dep').on('change', function () {
      var dep = $(this).val() || '';
      $('#ban_filter_cec').val('');
      refreshBanInstitutionFacetsForDepartement(dep);
    });

    $('#btnRechercheJournal').on('click', function () {
      loadBanPublic({
        page: 1,
        departement: $('#ban_filter_dep').val() || '',
        institution: $('#ban_filter_cec').val() || '',
      });
    });

    $('#btnResetJournal').on('click', function () {
      $('#ban_filter_dep').val('');
      $('#ban_filter_cec').val('');
      loadBanPublic({ page: 1 });
    });

    $('#banPagePrev').on('click', function () {
      var p = window.portailBanCurrentPage || 1;
      if (p > 1) {
        loadBanPublic({
          page: p - 1,
          departement: $('#ban_filter_dep').val() || '',
          institution: $('#ban_filter_cec').val() || '',
        });
      }
    });

    $('#banPageNext').on('click', function () {
      var p = window.portailBanCurrentPage || 1;
      var last = window.portailBanLastPage || 1;
      if (p < last) {
        loadBanPublic({
          page: p + 1,
          departement: $('#ban_filter_dep').val() || '',
          institution: $('#ban_filter_cec').val() || '',
        });
      }
    });

    $('#formDemandeActe').on('submit', function (e) {
      e.preventDefault();
      var $btnDemande = $(this).find('[type="submit"]').first();
      var crit = $('#demande_TypeCritere').val();
      var numeroActe = crit === '1' ? ($('#demande_numero_acte').val() || '').trim() : '';
      if (crit === '1' && numeroActe.length >= 2 && !window.portailActeVerifOk) {
        flashAlert(
          'Numéro d\'acte',
          'warning',
          "Attendez la validation automatique du numéro ou corrigez-le si aucun acte n'est trouvé."
        );
        scheduleVerifActePortail();
        return;
      }
      var data = {
        type_acte: String($('#demande_type_acte').val() || ''),
        type_document: String($('#demande_type_document').val() || ''),
        numero_acte: numeroActe,
        nom_acte: String($('#demande_nom_acte').val() || ''),
        prenom_acte: String($('#demande_prenom_acte').val() || ''),
        sexe_acte: String($('#demande_sexe_acte').val() || ''),
        date_naissance_acte: $('#demande_date_naissance').val()
          ? String($('#demande_date_naissance').val())
          : '',
        lieu_naissance_acte: String($('#demande_lieu_naissance').val() || ''),
        cec_acte: String($('#demande_cec_acte').val() || ''),
        nom_demandeur: String($('#demande_nom_demandeur').val() || ''),
        telephone_demandeur: String($('#demande_tel_demandeur').val() || ''),
        email_demandeur: String($('#demande_email_demandeur').val() || ''),
        cec_traitement: String($('#demande_cec_traitement').val() || ''),
        moyen_paiement: String($('input[name="moyen_paiement"]:checked').val() || ''),
        numero_momo: String($('#demande_numero_momo').val() || ''),
        montant_a_payer: 2,
      };
      setSubmitButtonLoading($btnDemande, true, 'Envoi en cours…');
      PortailApi.post('demandeActe', data)
        .always(function () {
          setSubmitButtonLoading($btnDemande, false);
        })
        .done(function (reponse) {
          if (typeof reponse === 'string') {
            if (reponse === 'Acte non trouvé') {
              flashAlert('Demande', 'error', "Acte non trouvé. Vérifiez les informations saisies.");
              return;
            }
            $('#iframeEtat').attr('src', reponse);
            hideModal(document.getElementById('modalDemande'));
            showModal(document.getElementById('modalEtat'));
            return;
          }
          if (reponse.code === '200') {
            if (reponse.etat) {
              $('#iframeEtat').attr('src', reponse.etat);
              hideModal(document.getElementById('modalDemande'));
              showModal(document.getElementById('modalEtat'));
              return;
            }
            var html =
              '<p>Demande enregistrée.</p><p><strong>Code :</strong> ' +
              escapeHtml(reponse.code_demande || '') +
              '</p>';
            if (reponse.montant !== undefined) {
              html += '<p><strong>Montant :</strong> ' + escapeHtml(String(reponse.montant)) + '</p>';
            }
            html +=
              '<p class="mb-0 small text-muted">Le document PDF signé vous sera envoyé par e-mail après validation au centre d\'état civil.</p>';
            flashAlert('Succès', 'success', html);
            hideModal(document.getElementById('modalDemande'));
            return;
          }
          flashAlert('Demande', 'error', escapeHtml(reponse.message || 'Erreur lors de la création de la demande.'));
        })
        .fail(function (xhr) {
          var msg = 'Connexion échouée.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          }
          flashAlert('Erreur', 'error', escapeHtml(String(msg)));
        });
    });

    $('#formAuthActe').on('submit', function (e) {
      e.preventDefault();
      var $btnAuth = $(this).find('[type="submit"]').first();
      $('#auth-validation').empty();
      var data = {
        type_acte: $('#auth_type_acte').val(),
        numero_acte: $('#auth_numero_acte').val(),
        administration: $('#adminLibLabel').text(),
      };
      setSubmitButtonLoading($btnAuth, true, 'Vérification…');
      PortailApi.post('authentification', data)
        .always(function () {
          setSubmitButtonLoading($btnAuth, false);
        })
        .done(function (reponse) {
          if (String(reponse.code) === '180') {
            $('#certificatLoader1').show();
            $('#certificatLoader').hide();
            $('#displayActe').attr('src', '');
            $('#auth-validation').html('<div class="alert alert-danger mt-2">' + escapeHtml(reponse.message || '') + '</div>');
            if (reponse.etatRecu) {
              $('#iframeRecu').attr('src', reponse.etatRecu);
            }
          } else {
            $('#certificatLoader1').hide();
            $('#certificatLoader').show();
            $('#auth-validation').html('<div class="alert alert-success mt-2">Acte authentique</div>');
            $('#displayActe').attr('src', reponse.etatActe || '');
            $('#iframeRecu').attr('src', reponse.etatRecu || '');
          }
        })
        .fail(function () {
          $('#auth-validation').html('<div class="alert alert-danger mt-2">Connexion échouée.</div>');
        });
    });

    $('#formConnexion').on('submit', function (e) {
      e.preventDefault();
      var $btnConn = $(this).find('[type="submit"]').first();
      $('#alertConnexion').empty();
      var email = $('#connexion_email').val();
      var password = $('#connexion_password').val();
      var adminVal = $('#connexion_admin').val();
      var adminLabel = libFromConnexionAdmin(adminVal);

      setSubmitButtonLoading($btnConn, true, 'Connexion…');
      PortailApi.post('login', { email: email, password: password })
        .always(function () {
          setSubmitButtonLoading($btnConn, false);
        })
        .done(function (r) {
          if (String(r.code) === '200' && r.token) {
            PortailApi.setToken(r.token);
            $('#adminLibLabel').text(adminLabel);
            hideModal(document.getElementById('modalConnexion'));
            showModal(document.getElementById('modalAdmin'));
            return;
          }
          tryLegacyConnexion(email, password, adminVal);
        })
        .fail(function () {
          tryLegacyConnexion(email, password, adminVal);
        });
    });

    $('#btnHistorique').on('click', function (e) {
      e.preventDefault();
      var idAdmin = adminCodeFromLabel($('#adminLibLabel').text());
      PortailApi.post('historiqueAuthentification', { code_administration: idAdmin })
        .done(function (reponse) {
          if (reponse.etat) {
            $('#iframeHistorique').attr('src', reponse.etat);
          }
          showModal(document.getElementById('modalHistorique'));
        })
        .fail(function () {
          flashAlert('Erreur', 'error', 'Impossible de récupérer l’historique.');
        });
    });

    $('#btnDeconnexion').on('click', function (e) {
      e.preventDefault();
      PortailApi.setToken('');
      hideModal(document.getElementById('modalAdmin'));
    });
  });
})(jQuery);
