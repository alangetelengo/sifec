@extends('layout.app')
@section('titre')
Actes Décès
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">

@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des déclarations tardives</h4>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date</th>
                                        <th>Décédé(e) A</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($declarationsTardives as $dd)
                                        @php
                                            $dernierMouvement = null;
                                            $peutEnvoyer = false;
                                            $peutModifier = false;
                                            $peutSupprimer = false;
                                            $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];

                                            if (isset($dd->mouvements) && $dd->mouvements->count()) {
                                                $dernierMouvement = $dd->mouvements->sortByDesc('created_at')->first();
                                                switch ($dernierMouvement->code_mouvement) {
                                                    case 'MOUV_0002':
                                                        $statutBadge = ['class' => 'badge-warning', 'label' => $dernierMouvement->lib_mouvement];
                                                        $peutEnvoyer = false;
                                                        $peutModifier = false;
                                                        $peutSupprimer = false;
                                                        break;
                                                    case 'MOUV_0004':
                                                        $statutBadge = ['class' => 'badge-info', 'label' => $dernierMouvement->lib_mouvement];
                                                        $peutEnvoyer = true;
                                                        $peutModifier = true;
                                                        $peutSupprimer = true;
                                                        break;
                                                    case 'MOUV_0015':
                                                        $statutBadge = ['class' => 'badge-info', 'label' => $dernierMouvement->lib_mouvement];
                                                        $peutEnvoyer = false;
                                                        $peutModifier = false;
                                                        $peutSupprimer = false;
                                                        break;
                                                    case 'MOUV_0019':
                                                        $statutBadge = ['class' => 'badge-success', 'label' => $dernierMouvement->lib_mouvement];
                                                        $peutEnvoyer = false;
                                                        $peutModifier = false;
                                                        $peutSupprimer = false;
                                                        break;
                                                    case 'MOUV_0032':
                                                        $statutBadge = ['class' => 'badge-primary', 'label' => $dernierMouvement->lib_mouvement];
                                                        $peutEnvoyer = true;
                                                        $peutModifier = true;
                                                        $peutSupprimer = true;
                                                        break;
                                                    case 'MOUV_0016':
                                                        $statutBadge = ['class' => 'badge-dark', 'label' => $dernierMouvement->lib_mouvement];
                                                        $peutEnvoyer = false;
                                                        $peutModifier = false;
                                                        $peutSupprimer = false;
                                                        break;
                                                    default:
                                                        $statutBadge = ['class' => 'badge-secondary', 'label' => $dernierMouvement->lib_mouvement ?? 'En cours'];
                                                        $peutEnvoyer = false;
                                                        $peutModifier = false;
                                                        $peutSupprimer = false;
                                                }
                                            } else {
                                                // Jamais envoyé
                                                $statutBadge = ['class' => 'badge-secondary', 'label' => 'Brouillon'];
                                                $peutEnvoyer = true;
                                                $peutModifier = true;
                                                $peutSupprimer = true;
                                            }
                                        @endphp
                                        <tr width="100%">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $dd->defunt->nom.' '.$dd->defunt->prenom }}</td>
                                            <td>{{ $dd->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                            <td>{{ date("d-m-Y", strtotime($dd->date_heure_deces)) }}</td>
                                            <td>{{$dd->lieu_deces}}</td>
                                            <td>{{ $dd->declarant->nom.' '.$dd->declarant->prenom }}</td>

                                            <td>
                                                <span class="badge light {{ $statutBadge['class'] }}" style="font-size: 13px;font-weight:600;">
                                                    {{ $statutBadge['label'] }}
                                                </span>
                                                @if($dernierMouvement && $dernierMouvement->observation)
                                                    <br><small>Observation : {{ $dernierMouvement->observation }}</small>
                                                @endif
                                                @if($dernierMouvement && $dernierMouvement->motif_renvoi)
                                                    <br><small>Motif : {{ $dernierMouvement->motif_renvoi }}</small>
                                                @endif
                                            </td>



                                            <td style="width: 18%">
                                                <div class="btn-group btn-group-xs">
                                                    {{-- Voir le détail --}}
                                                    <a href="{{ route('declarationDeces.show',$dd->code_declaration_deces) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir détail">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                    {{-- Modifier --}}
                                                    @if($peutModifier)
                                                        <a href="{{ route('declarationDeces.edit',$dd->code_declaration_deces) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                    @endif
                                                    {{-- Envoyer --}}
                                                    @if($peutEnvoyer)
                                                        <button class="btn btn-warning btn-envoyer-centre shadow btn-xs sharp me-1"
                                                            title="Envoyer la déclaration au centre d'état civil"
                                                            data-code="{{ $dd->code_declaration_deces }}"
                                                            data-piece-declarant="{{ $dd->piece_declarant }}"
                                                            data-piece-pere="{{ $dd->piece_pere }}"
                                                            data-piece-mere="{{ $dd->piece_mere }}"
                                                            data-identiteDeclarant="{{ $dd->declarant->nomcomplet() }}"
                                                            data-identitePere="{{ $dd->pere->nomcomplet() }}"
                                                            data-identiteMere="{{ $dd->mere->nomcomplet() }}">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    @endif
                                                    {{-- Supprimer --}}
                                                    @if($peutSupprimer)
                                                        <form action="{{ route('declarationDeces.destroy',$dd->code_declaration_deces) }}" method="POST" style="display: inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    @endif
                                                    {{-- Consulter le PDF pour impression --}}
                                                    <a href="{{ route('declarationDeces.etat',$dd->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document (PDF)">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date & Heure</th>
                                        <th>Décédé(e) A</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
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
{{-- DEBUT ENVOIS DECLARATION --}}
<div class="modal fade" id="modal-declaration-send" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> </span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        {{-- <input type="hidden" id="code_declaration_deces"> --}}
                        <label class="form-label">Transmission de la déclaration N°</label>
                        <input type="text" readonly class="form-control"  placeholder="" id="codedeclaration">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN ENVOIS DECLARATION --}}


{{-- DEBUT DETAILS RENVOIE DECLARATION --}}
<div class="modal fade" id="modal-declaration-send-back" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> Détail du renvoie</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Document n°</label>
                        <input type="text" readonly class="form-control"  placeholder="" id="codedeclarationback">
                        <input type="hidden" class="form-control" id="codemouvementdeces">
                    </div>

                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif du renvoi <span class="text-danger">*</span></label>
                        <select id="motif_renvoi" name="motif_renvoi" class="form-control" readonly>
                            {{-- <option value="" disabled selected>Selectionner</option>
                            <option value="erreur materielle">Erreur matérielle</option>
                            <option value="Ajouter nom/prenom">Ajouter nom/prénom</option>
                            <option value="rectifier nom/prenom">Rectifier nom/prénom</option> --}}
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation" cols="105" rows="5" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN DETAILS RENVOIE DECLARATION --}}


@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){

                var codeDeclaration = $(this).attr('href');

                $("#code_declaration_deces").val(codeDeclaration);
                $("#codedeclaration").val(codeDeclaration);

                $("#modal-declaration-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cdd = $("#codedeclaration").val();
                var route = "{{ route('declarationDeces.mouvement') }}";
                var data = {
                    code_declaration_deces:cdd
                };

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-declaration-send").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });

            $("a.show-detail-renvoie").on("click", function(){
                var motif = $(this).attr("title");
                var cdd = $(this).attr("href");
                var cmvtn = $(this).attr("cmouvtdeces");
                var obs = $(this).attr("obs");

                $("#codedeclarationback").val(cdd);
                $("#observation").val(obs);
                $("#motif_renvoi").html("<option>"+motif+"</option>");

                $("#modal-declaration-send-back").modal("show");
                return false;
            });

        });
    </script>
@endsection
