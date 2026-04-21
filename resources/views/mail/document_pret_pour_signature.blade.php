@component('mail::message')
@if($salut !== '')
M (Mme) {{ $salut }},
@else
Bonjour,
@endif

Un document a été généré et nécessite votre signature électronique.

**Type de document :** {{ $demande->getLibelleTypeDocument() }}

**Type d'acte :** {{ $demande->getLibelleTypeActe() }}

**Numéro d'acte :** {{ $demande->numero_acte }}

**Demandeur :** {{ $demande->getNomCompletDemandeur() }}

@component('mail::button', ['url' => $urlDemande])
Signer le document
@endcomponent

Merci de procéder à la signature dès que possible.

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
