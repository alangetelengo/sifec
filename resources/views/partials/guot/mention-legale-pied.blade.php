{{--
  Mention légale en pied de page des PDF signés (Html2Pdf).
  Alignée sur l'en-tête du document :
    - certificats (formation sanitaire / hygiène) → Ministère de la Santé et de la Population
    - déclarations, actes, copies, extraits → état civil

  Variable : $typeDocument — clé parmi :
    acte_naissance, certificat_naissance, declaration_naissance,
    copie_naissance, extrait_naissance,
    acte_mariage, declaration_mariage, copie_mariage, extrait_mariage,
    acte_deces, certificat_deces, certificat_constatation_deces, declaration_deces,
    copie_deces, extrait_deces,
    document (repli)

  Optionnel : $autorite — 'sante' | 'etat_civil' (force le domaine si fourni)
--}}
@php
    $labels = [
        'acte_naissance' => 'Cet acte de naissance',
        'certificat_naissance' => 'Ce certificat de naissance',
        'declaration_naissance' => 'Cette déclaration de naissance',
        'copie_naissance' => "Cette copie d'acte de naissance",
        'extrait_naissance' => "Cet extrait d'acte de naissance",
        'acte_mariage' => 'Cet acte de mariage',
        'declaration_mariage' => 'Cette déclaration de mariage',
        'copie_mariage' => "Cette copie d'acte de mariage",
        'extrait_mariage' => "Cet extrait d'acte de mariage",
        'acte_deces' => 'Cet acte de décès',
        'certificat_deces' => 'Ce certificat de décès',
        'certificat_constatation_deces' => 'Ce certificat de constatation de décès',
        'declaration_deces' => 'Cette déclaration de décès',
        'copie_deces' => "Cette copie d'acte de décès",
        'extrait_deces' => "Cet extrait d'acte de décès",
        'registre_naissance' => "Ce registre d'actes de naissance",
        'registre_mariage' => "Ce registre d'actes de mariage",
        'registre_deces' => "Ce registre d'actes de décès",
        'document' => 'Ce document',
    ];

    $typesSante = [
        'certificat_naissance',
        'certificat_deces',
        'certificat_constatation_deces',
    ];

    $cle = $typeDocument ?? 'document';
    $sujet = $labels[$cle] ?? $labels['document'];

    $domaine = $autorite
        ?? (in_array($cle, $typesSante, true) ? 'sante' : 'etat_civil');

    $complement = $domaine === 'sante'
        ? 'du Ministère de la Santé et de la Population de la République du Congo'
        : "de l'état civil de la République du Congo";
@endphp
<div style="width: 100%; text-align: center; font-size: 8px; color: #5a3d1e; line-height: 1.25; padding: 0.5mm 4mm 1.5mm 4mm;">
    {{ $sujet }} est un document officiel {{ $complement }}. Toute falsification ou usage frauduleux est puni par la loi.
</div>
