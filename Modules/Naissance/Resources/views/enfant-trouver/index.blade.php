

@extends('layout.app')
@section('titre')
Déclaration
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link href='https://css.gg/airplane.css' rel='stylesheet'>
@endsection
@section('sous-titre')
    Liste des déclarations de naissance
@endsection
@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des déclarations de naissance</h4>
                <a href="{{ route("declarationNaissance.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Enregistrer un certificat  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($declarations as $dn)
                                    <tr width="100%">
                                        <td>{{ $dn->code_declaration_naissance }}</td>
                                        <td>{{ $dn->declarant->nom.' '.$dn->Declarant->prenom }}</td>
                                        <td>{{ $dn->enfant->nom }}</td>
                                        <td>{{ $dn->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($dn->enfant->date_naissance)) }}</td>
                                        <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>

                                         @if($dn->mouvements()->get("statut")->last()->statut == "En cours")
                                        <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $dn->mouvements()->get("statut")->last()->statut }} de saisie</span></td>
                                        @endif
                                        @if($dn->mouvements()->get("statut")->last()->statut == "Envoyée")
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Transférée à l'institution supérieure </span></td>
                                        @endif
                                        <td>
                                            @if($dn->mouvements()->get("statut")->last()->statut == "En cours" || $dn->mouvements()->get("statut")->last()->statut == "Renvoyée")
                                            <div class="btn-group btn-group-xs">
                                                <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{$dn->code_declaration_naissance}}-modal-sm" title="Envoyer"><i class="fas fa-plane"></i></button>
                                                <div class="modal fade bd-{{$dn->code_declaration_naissance}}-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Transmission de la déclaration N° <strong>{{ $dn->code_declaration_naissance }}</strong> </h5>
                                                                <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                                </button>
                                                            </div>
                                                            <form action="{{ route("declarationNaissance.mouvement",$dn->code_declaration_naissance) }}" method="POST">
                                                                @csrf
                                                                @method("PUT")
                                                                <div class="modal-body">
                                                                    <div class="mb-3 col-md-12">
                                                                        <label class="form-label">Statut</label>
                                                                        <select name="statut" required class="form-control form-control wide select2">
                                                                           <option value="Envoyée" selected>Envoyer</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                                                                    <button type="submit" class="btn btn-sm btn-warning ">Valider</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-print"></i></a>
                                                @if($dn->acte !== null)
                                                <a href="{{ route('acteNaissance.display',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                @endif
                                                <a href="{{ route('declarationNaissance.edit',$dn->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                               <form  action="{{ route('declarationNaissance.destroy',$dn->code_declaration_naissance) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                       {{--  <th>Père</th>
                                        <th>Mère</th> --}}
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

@endsection
