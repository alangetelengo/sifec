@extends('layout.app')
@section('titre')
    Accueil
@endsection
@section('styles')

@endsection
@section('corps')
    @php
        $user = Auth()->user();
         //r�cup�ration  du type cat�gorie et image d'accueil illustrative de l'institution de l'utilisateur connect�
        $typeCatIns = $user->AffectationActive()->institution->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution;
        $urlImg = $user->AffectationActive()->institution->typeInstitution->typeCategorieInstitution->image_illustrative;

    @endphp

    <center>
    <!-- affichage de l'image de la cat�gorie de l'insitution de l'utilisateur -->

    {{-- <img src="{{asset($urlImg)}}"  width="50%" style=""> --}}
    <!-- fin image -->



</center>


@endsection
