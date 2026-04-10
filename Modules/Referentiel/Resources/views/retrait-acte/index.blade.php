@extends('layout.app')
@section('titre')
  retraits
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

@endsection
@section('corps')

<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-block">
                    <h4>Consultation des retraits d'acte</h4>
                    {{-- <p class="mb-0 subtitle">Default button style</p> --}}
                </div>
                <div class="card-body">
                    <button class="btn btn-success btn choixActe" typeActe="naissance"> ACTE DE NAISSANCE</button>
                    <button class="btn btn-info choixActe" typeActe="mariage">ACTE DE MARIAGE</button>
                    <button class="btn btn-danger choixActe" typeActe="deces">ACTE DE DECES</button>
                    <hr>
                    <div id="naissance">
                        <form action="{{ route('retrait.search.acte') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom(s) de l'enfant<span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control  @error('nom_enfant') is-invalid @enderror" name="nom_enfant" value="{{ old('nom_enfant') }}" placeholder="Saisir le nom de l'enfant..." onkeyup="verif_lettre(this);this.value=this.value.toUpperCase()">
                                    @error("nom_enfant")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Prénom(s) de l'enfant</label>
                                    <input type="text" class="form-control" name="prenom_enfant" value="{{ old('prenom_enfant') }}" placeholder="Saisir le prénom de l'enfant..." onkeyup="verif_lettre(this);" style="text-transform: capitalize">
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select id="sexe_enfant" name="sexe_enfant" required class="form-control  @error('sexe_enfant') is-invalid @enderror">
                                        <option value="" disabled selected>Selectionner</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                    @error("sexe_enfant")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Année de naissance <span class="text-danger">*</span></label>
                                    <select id="annee_naissance_enfant" name="annee_naissance_enfant" required class="form-control  @error('annee_naissance_enfant') is-invalid @enderror">
                                        <option value="" disabled selected>Selectionner</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                                <a href="/"><button type="button" class="btn btn-sm btn-danger">Retour</button></a>
                                <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                         </form>
                    </div>
                    <hr>
                    @isset($acte)
                    @include("referentiel::retrait-acte.resultat");
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFonction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Libéllé de la fonction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{ route("fonction.store") }}"  method="POST">
                    @csrf

                    <div class="method"></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                <input type="text" name="lib_fonction" required class="form-control @error('lib_fonction') is-invalid @enderror" value="{{ old("lib_fonction") }}"  id="lib_fonction">
                                @error("lib_fonction")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

      <script>
        $(function(){
            $("#naissance").hide();
            // $("button.sweet-message").on("click", function(){
            $("button.choixActe").on("click", function(){



                var typeActe = $(this).attr("typeActe");
                if(typeActe == "naissance"){
                    $("#naissance").toggle();
                    // $("#naissance").show(300);
                }

                if(typeActe == "deces"){
                    $("#naissance").addClass("disabled",true);
                    $("#deces").show(300);
                }

                return false;

            });

        });
     /*    $(function(){
            $("a.show-edit-fonction").on("click", function() {
                var me = $(this);
                var lib_fonction = me.attr('data-fonction');
                var code_fonction = me.attr('data-code');
                var route = "{{ route('fonction.update', ':id') }}";
                route = route.replace(':id',code_fonction);

                $("#lib_fonction").val(lib_fonction);
                $(".modal-title").html("Modification "+ lib_fonction);
                $("form.action").attr(route);
                $("#method").html('@method("PUT")');
                var modal = $("#modalFonction").modal("show");

                return false;
            });
        }); */
      </script>
@endsection
