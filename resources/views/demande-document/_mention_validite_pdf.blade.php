{{-- Mention période de validité (copies / extraits demande) --}}
@if(!empty($demande) && $demande->document_valide_de && $demande->document_valide_jusquau)
    <p style="font-size: 11px; text-align: center; margin: 2mm 0; color: #333;">
        <strong>Validité du présent document :</strong>
        du {{ $demande->document_valide_de->format('d/m/Y') }} au {{ $demande->document_valide_jusquau->format('d/m/Y') }}.
    </p>
@endif
