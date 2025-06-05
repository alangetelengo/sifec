@extends('layout.app')
@section('titre')
Les duplicatas
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
                <h4>Duplicatas</h4>
                <div class="row">
                    <div class="col-md-12" id="dupcreer">
                        <button class="btn btn-primary mb-2 btn-block chercheacte">Rechercher la personne</button>
                    </div>
                </div>
           </div>
            <div class="col-12">
                {{-- <div class="col-12"> --}}
                    
                {{-- </div> --}}
                <div class="card">
                    <div class="card-body">
                        <div id="actetrouver">                            
                            <h4>Acte trouvé</h4>
                            <form action="{{route('duplicata.store')}}" method="POST">
                                @csrf
                                <input type="text" id="numacte" name="numacte" class="d-none">
                                <div class="row">
                                    <div class="mb-2 col-md-6">
                                        <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"lass="form-control" id="nom_trouve" placeholder="" disabled>
                                    </div>

                                    <div class="mb-2 col-md-6">
                                        <label class="form-label">Prénom(s)</label>
                                        <input type="text" class="form-control" id="prenom_trouve" placeholder=""  disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-6">
                                        <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"lass="form-control" id="lieu_trouve" placeholder=""  disabled>
                                    </div>

                                    <div class="mb-2 col-md-6">
                                        <label class="form-label">Centre d'Etat Civil</label>
                                        <input type="text" class="form-control @error('institut_recherche') is-invalid @enderror" value="{{ old("institut_recherche") }}" placeholder="" id="institut_recherche" disabled>
                                        @error("institut_recherche")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <input type="submit" class="btn btn-primary" value="Générer duplicata">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="listeduplicata">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($duplicatas as $duplicata)
                                    <tr width="100%">
                                        <td>{{ $duplicata->code_duplicata }}</td>
                                        <td>{{ $duplicata->niupp }}</td>
                                        <td>{{ $duplicata->actenaissance->declaration->enfant->nom }}</td>
                                        <td>{{ $duplicata->actenaissance->declaration->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($duplicata->actenaissance->declaration->enfant->date_naissance)) }}</td>
                                        <td>{{ $duplicata->actenaissance->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>

                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('duplicata.generate',$duplicata->code_duplicata) }}" target="_blank">Afficher duplicata</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>                                   
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>N°</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
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


    <div class="modal fade" id="modal-acte" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechercher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"lass="form-control"  placeholder="" id="nom_recherche" required>
                            
                        </div>

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Prénom(s)</label>
                            <input type="text" class="form-control"  placeholder="" id="prenom_recherche">
                            
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Lieu de naissance </label>
                            <input type="tel" class="form-control"  id="lieu_recherche">
                            
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info text-white" id="rechercher">Rechercher</button>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Résultat de la recherche</h4>
                                </div>
                                <div class="card-body">
                                    <div id="resultatrech"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
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

    <script>
        $(function(){
        cacher();
        $("button.chercheacte").on("click",function(){           

            var me = $(this);
            var action = me.attr("href");
            var modal = $("#modal-acte");

            modal.modal("show");

            return false;

        });

            // Rechercher la personne
        $('button#rechercher').on("click", function (event) {
            // event.preventDefault();
            // data = [];
            var nom = $("#nom_recherche");
            var prenom = $("#prenom_recherche");
            var lieu = $("#lieu_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                //sexe: sexe.val(),
                lieu: lieu.val()
            };

            var int = 0;

            var table = '<div class="table-responsive">'+
                            '<table id="example" class="table table-responsive-md table-hover">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th><strong>Nom et prénom</strong></th>'+
                                        '<th><strong>Lieu naissance</strong></th>'+
                                        '<th><strong>Centre d\'Etat Civil</strong></th>'+
                                        // '<th><strong>Action</strong></th>'+
                                ' </tr>'+
                                '</thead>'+
                                '<tbody>';

            //traitement ajax
            $.post("{{ route('declarationNaissance.rechercheActe') }}",
                    data,
                    function(response){
                        if(response.personnes.length > 0){
                            console.log(response);

                            for( var i=0; i < response.personnes.length ; i++){                            
                                int ++;
                                table +='<tr class="tr" data-choix="'+response.personnes[i].niupp+'" data-nom="'+response.personnes[i].nom+'" data-prenom="'+response.personnes[i].prenom+'" data-lieu="'+response.personnes[i].lieu_naissance+'" data-institut="'+response.personnes[i].lib_institution+'">'+
                                            '<td><strong>'+int+'</strong></td>'+
                                            '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                            '<td>'+response.personnes[i].lieu_naissance+'</td>'+
                                            '<td>'+response.personnes[i].lib_institution+'</td>'+                                                
                                            '</tr>';
                            }
                        }

                        $("#resultatrech").html(table);

                        $("tr.tr").on("click", function (){ 
                            $("#listeduplicata").hide();
                            $("#dupcreer").hide();
                            $("#actetrouver").show();

                            var choix = $(this).data('choix');
                            var nom = $(this).data('nom');
                            var prenom = $(this).data('prenom');
                            var lieu = $(this).data('lieu');
                            var institut = $(this).data('institut');

                            $("#listeduplicata").hide();
                            $("#dupcreer").hide();
                            $("#actetrouver").show();

                            $("#numacte").val(choix);


                            $("#nom_trouve").val(nom);
                            $("#prenom_trouve").val(prenom);
                            $("#lieu_trouve").val(lieu);
                            $("#institut_recherche").val(institut);

                            $("#modal-acte").modal('hide');

                        });

                    });
            });

            function cacher() {
                $("#actetrouver").hide();
            }
   
        });
    </script>

@endsection
