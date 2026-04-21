@component('mail::message')
@if($salut !== '')
M (Mme) {{ $salut }},
@else
Bonjour,
@endif

Une nouvelle demande de document a été enregistrée pour votre centre.

**Centre :** {{ $centre }}

**Origine :** {{ $origine }}

**Type de document :** {{ $demande->getLibelleTypeDocument() }}

**Type d'acte :** {{ $demande->getLibelleTypeActe() }}

**Numéro d'acte :** {{ $demande->numero_acte }}

**Demandeur :** {{ $demande->getNomCompletDemandeur() }}

@component('mail::button', ['url' => $urlDemande])
Ouvrir la demande
@endcomponent

Merci de traiter cette demande dans les délais prévus.

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
