<div class="d-flex flex-row gap-1 w-100">
    {{-- Voir le certificat reçu (PDF) --}}
    <a href="{{ route('tribunal.voir_certificat', ['type' => $module, 'id' => $id]) }}" class="btn btn-warning btn-xs text-start me-1" title="{{ $module == "mariage" ? "Voir le formulaire type PDF reçu" : "Voir le certificat PDF reçu" }}" target="_blank">
        <i class="fas fa-file-pdf"></i>
    </a>
    {{-- Voir le détail du dossier --}}
    <a href="{{ route('tribunal.detail_certificat.show', ['type' => $module, 'id' => $id]) }}" class="btn btn-primary btn-xs text-start me-1" title="Voir le détail du dossier">
        <i class="fas fa-eye"></i>
    </a>

    @php
        // Flux du tribunal : 1-Réception → 2-Validation (MOUV_1019) → 3-Import → 4-Envoi

        // Mouvements de réception du dossier au tribunal
        $mouvementsReception = [
            'MOUV_0006', 'MOUV_0008', 'MOUV_0209', 'MOUV_0109',
            'MOUV_0026', 'MOUV_0027', 'MOUV_0031', 'MOUV_0032','MOUV_2008'
        ];

        // Mouvement de validation par le tribunal
        $mouvementValidation = 'MOUV_1019';

        // Mouvements d'import de document (réquisition/jugement)
        $mouvementsImport = ['MOUV_1001', 'MOUV_1002'];

        // Mouvements d'envoi au centre d'état civil
        $mouvementsEnvoiCentre = ['MOUV_0009', 'MOUV_0010', 'MOUV_0011'];

        // Déterminer l'état actuel du dossier
        $documentRecu = $dernierMouvement && in_array($dernierMouvement->code_mouvement, $mouvementsReception);
        $documentValide = $dernierMouvement && $dernierMouvement->code_mouvement === $mouvementValidation;
        $documentImporteEtPret = $documentImporte && $dernierMouvement && in_array($dernierMouvement->code_mouvement, $mouvementsImport);
        $documentDejaEnvoye = $dernierMouvement && in_array($dernierMouvement->code_mouvement, $mouvementsEnvoiCentre);

        // ÉTAPE 1 & 2 : Peut valider/renvoyer (après réception, avant validation)
        $peutValiderRenvoyer = $documentRecu &&
                              !$documentValide &&
                              !$documentImporte &&
                              !$documentDejaEnvoye &&
                              ($tribunal_approuver ?? 'NON') !== 'OUI';

        // ÉTAPE 3 : Peut importer (après validation MOUV_1019)
        $peutImporter = $documentValide &&
                       !$documentImporte &&
                       !$documentDejaEnvoye &&
                       ($tribunal_approuver ?? 'NON') === 'OUI';

        // ÉTAPE 4 : Peut envoyer (après import)
        $peutEnvoyer = $documentImporteEtPret &&
                      !$documentDejaEnvoye;
    @endphp

    {{-- ÉTAPE 1 & 2 : Renvoyer le dossier au centre d'état civil (si problème avant validation) --}}
    @if($peutValiderRenvoyer)
        <a href="#" class="btn btn-danger btn-xs text-start me-1 show-to-send" title="Renvoyer au centre d'état civil (ex : informations manquantes, erreur, etc.)" data-id="{{ $id }}" data-module="{{ $module }}" data-action="renvoi">
            <i class="fas fa-undo"></i>
        </a>
    @endif

    {{-- ÉTAPE 2 : Valider le dossier reçu (génère MOUV_1019) --}}
    @if($peutValiderRenvoyer)
        <a href="#" class="btn btn-success btn-xs text-start me-1 modal-confirmation-document" title="Valider le dossier reçu (MOUV_1019)" data-id="{{ $id }}" data-module="{{ $module }}" data-action="modal-confirmation-document">
            <i class="fas fa-check"></i>
        </a>
    @endif

    {{-- ÉTAPE 3 : Importer une réquisition ou un jugement (après validation MOUV_1019) --}}
    @if($peutImporter)
        <a href="{{ route('tribunal.document.importer', ['type' => $module, 'code' => $id]) }}"
            class="btn btn-info btn-xs text-start me-1" title="Importer réquisition/jugement (après validation)">
            <i class="fas fa-upload"></i>
        </a>
    @endif


    {{-- Télécharger le document importé (si déjà importé) --}}
    @if($documentImporte)
        <a href="{{ route('tribunal.voir_document', ['type' => $module, 'id' => $id]) }}"
            class="btn btn-info btn-xs text-start me-1" title="Télécharger le document importé">
            <i class="fas fa-download"></i>
        </a>
    @endif

    {{-- ÉTAPE 4 : Envoyer la réponse au centre d'état civil (après import de réquisition/jugement) --}}
    @if($peutEnvoyer)
        <a href="#" class="btn btn-primary btn-xs text-start me-1 show-to-send" style="font-size: 13px;font-weight:600;" title="Envoyer la réponse (réquisition/jugement) au centre d'état civil" data-id="{{ $id }}" data-module="{{ $module }}" data-action="envoi">
            <i class="fas fa-paper-plane"></i>
        </a>
    @endif

    {{-- Indicateur si le document a déjà été envoyé --}}
    @if($documentDejaEnvoye)
        <span class="badge bg-success" title="Document déjà envoyé au centre d'état civil">
            <i class="fas fa-check-circle me-1"></i>Envoyé
        </span>
    @endif
</div>





