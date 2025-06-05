@extends('layout.app')
{{-- @section('titre')
Liste des centres d'état civil principale
@endsection --}}
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des localités administratives</h4>
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalCEC">
                        Ajouter
                    </button>
                </div>
                <div class="col-12" style="opacity:1">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Localité</th>
                                            <th>Type localité</th>
                                            <th>Localité rattachée</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $i=1; ?>
                                        @foreach ($localités as $institution)
                                            @if($institution->code_type_institution != "TPINS_0004" && $institution->code_type_institution != "TPINS_0001" )
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $institution->lib_institution }}</td>
                                                <td>{{ $institution->institutionParent != "" ? $institution->institutionParent->lib_institution : "" }}</td>
                                                <td>{{ $institution->typeInstitution->lib_type_institution }}</td>
                                                <td>{{ $institution->lieu != "" ? $institution->lieu->lib_localite : "ETRANGER" }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-xs">
                                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target=".bd-{{$institution->code_institution}}-modal-sm"><i class="fas fa-pencil-alt"></i></button>
                                                        <div class="modal fade bd-{{$institution->code_institution}}-modal-sm" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Modification de {{ $institution->lib_institution }}</h5>
                                                                        <button type="button" class="btn btn-sm-close" data-bs-dismiss="modal">
                                                                        </button>
                                                                    </div>

                                                                    <form action="{{ route('institution.update',$institution->code_institution) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="modal-body">

                                                                            <div class="row">
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="form-label">Libéllé institution <span class="text-danger">*</span></label>
                                                                                    <input type="text" class="form-control" class="form-control" value="{{ $institution->lib_institution }}" name="lib_institution">
                                                                                </div>

                                                                                <div class="mb-2 col-md-12 typeInstitution">
                                                                                    <label class="form-label">Type institution <span class="text-danger">*</span></label>
                                                                                    <select name="code_type_institution" required class="form-control form-control wide">
                                                                                        {{-- <option value="">Choisissez</option> --}}
                                                                                        @foreach ($typeInstitutions as $item)
                                                                                            <option value="{{ $item->code_type_institution }}" {{$item->code_type_institution == $institution->code_type_institution ? "selected":""}}>{{ $item->lib_type_institution }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="radio">
                                                                                      <input type="radio" name="newrattacher" {{ $institution->code_institution_parent != null ? "checked" : "" }} value="OUI"  />
                                                                                      OUI
                                                                                    </label>
                                                                                    <label class="radio">
                                                                                      <input type="radio" name="newrattacher" {{ $institution->code_institution_parent == null ? "checked" : "" }} value="NON" />
                                                                                      NON
                                                                                    </label>
                                                                                </div>
                                                                                @if($institution->code_institution_parent != null)
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="form-label">Institution rattachée <span class="text-danger"></span></label>
                                                                                    <select name="code_institution_parent" class="form-control form-control wide oldparent">
                                                                                        @foreach ($institutions as $item)
                                                                                            <option value="{{ $item->code_institution }}" {{$item->code_institution == $institution->code_institution_parent ? "selected":""}}>{{ $item->lib_institution }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                @else
                                                                                <div class="mb-2 col-md-12 institutionRattache d-none">
                                                                                    <label class="form-label">Institution rattachée <span class="text-danger"></span></label>
                                                                                    <select name="code_institution_parent" class="form-control form-control wide newparent">
                                                                                        @foreach ($institutions as $item)
                                                                                            <option value="{{ $item->code_institution }}">{{ $item->lib_institution }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                @endif
                                                                                <div class="mb-2 col-md-12">
                                                                                    <label class="form-label">Localité <span class="text-danger">*</span></label>
                                                                                    <select name="code_localite" required class="form-control form-control wide">
                                                                                        @foreach ($localites as $item)
                                                                                            <option value="{{ $item->code_localite }}" {{ $institution->lieu != null ? $item->code_localite == $institution->lieu->code_localite ? "selected" : "" : "" }}>{{ $item->lib_localite }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2 col-md-12 lesceau">
                                                                                    <label class="form-label">Sceau <span class="text-danger"></span></label>
                                                                                    <input type="file" class="form-control" name="sceau" id="sceau">
                                                                                    @if($institution->sceau != null)
                                                                                    <img src='{{ asset("app/".$institution->sceau) }}' alt="" width="100px" height="100px">
                                                                                    @endif
                                                                                </div>
                                                                                <div class="mb-2 col-md-12 lesceau">
                                                                                    <label class="form-label">Etat <span class="text-danger">*</span></label>
                                                                                    <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                                                                        <option value="1" {{"1"==$institution->statut ? "selected":""}}>Actif</option>
                                                                                        <option value="0" {{"0"==$institution->statut ? "selected":""}}>Inactif</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-sm btn-primary ">Modifier</button>
                                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group btn-group-xs">
                                                        <form action="{{ route("institution.destroy",$institution->code_institution) }}" method="post">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Localité</th>
                                            <th>Type localité</th>
                                            <th>Localité rattachée</th>
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


      <!-- Large modal -->
        <div class="modal fade" id="modalCEC" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Information du centre principal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  action="{{ route("institution.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-2 col-md-12">
                                    <label class="form-label">Libéllé institution <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('lib_institution') is-invalid @enderror" value="{{ old("lib_institution") }}" required  name="lib_institution">
                                </div>

                                <div class="mb-2 col-md-12 typeInstitution">
                                    <label class="form-label">Type institution <span class="text-danger">*</span></label>
                                    <select id="codetypeinstitution" name="code_type_institution" required class="form-control form-control wide">
                                        <option value="">Choisissez</option>
                                        @foreach ($typeInstitutions as $item)
                                            <option value="{{ $item->code_type_institution }}" >{{ $item->lib_type_institution }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label class="radio">
                                      <input type="radio" name="rattacher" value="OUI" />
                                      OUI
                                    </label>
                                    <label class="radio">
                                      <input type="radio" name="rattacher" value="NON" />
                                      NON
                                    </label>
                                </div>

                                <div class="mb-2 col-md-12 institutionRattache d-none">
                                    <label class="form-label">Institution rattachée <span class="text-danger"></span></label>
                                    <select id="codeinstitutionparent" name="code_institution_parent" required class="form-control form-control wide">

                                    </select>
                                </div>

                                <div class="mb-2 col-md-12 typeLocalite d-none">
                                    <label class="form-label">Type localité <span class="text-danger">*</span></label>
                                    <select id="codeTypeLocalite" name="code_type_localite" required class="form-control form-control wide">
                                        <option value="">Choisissez</option>
                                        @foreach ($typeLocalites as $item)
                                            <option value="{{ $item->code_type_localite }}" >{{ $item->lib_type_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 col-md-12 localite d-none">
                                    <label class="form-label">Localité <span class="text-danger">*</span></label>
                                    <select id="codelocalites" name="code_localite" required class="form-control form-control wide">

                                    </select>
                                </div>
                                <div class="mb-2 col-md-12 lesceau">
                                    <label class="form-label">Sceau<span class="text-danger"></span></label>
                                    <input type="file" class="form-control" name="sceau">
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
@endsection
@section('scripts')
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
      <script>

        $(function() {

            $('input[type="radio"]').change(function(){
                var selectedValue = $('input[name="rattacher"]:checked').val();
                if(selectedValue == "OUI"){
                    $(".institutionRattache").removeClass("d-none");
                    $("#codeinstitutionparent").attr('disabled', false);
                    console.log("Selected option value:", selectedValue);
                }
                if(selectedValue == "NON"){
                    $(".institutionRattache").addClass("d-none");
                    $("#codeinstitutionparent").attr('disabled', true);

                    $(".typeLocalite").removeClass("d-none");
                    console.log("Selected option value:", selectedValue);
                }
            });



            //institution rattachée
            $("#codeinstitutionparent").on("change", function(){
                $(".typeLocalite").removeClass("d-none");
            });

            //Type de l'institution
            $("#codetypeinstitution").on("change", function(){

                var typeInstitution = $(this).val();
                if(typeInstitution !="" || typeInstitution != null){
                    getInstitution(typeInstitution);
                }
            });
            //Lieu de l'institution
            $("#codeTypeLocalite").on("change", function(){
                $(".localite").removeClass("d-none");
                var codetypeLoc = $(this).val();
               if(codetypeLoc !="" || codetypeLoc != null){
                    getLocalite(codetypeLoc);
                }
            });
        });

          //recuperer les institutions rattachées
          function getInstitution(id){
                var out = "<option selected disabled>Choisissez</option>";
                $.get("{{ route('institution.get.institution') }}", { id:id }, function(data){
                    if(data.length > 0){
                        for(var i=0; i < data.length; i++){
                            out += "<option value="+data[i].code_institution+" >"+data[i].lib_institution+"</option>";
                        }
                    }
                    $("#codeinstitutionparent").html(out);
                });
            }

             //recuperer les localites
             function getLocalite(id){
                var out = "<option selected disabled>Choisissez</option>";
                $.get("{{ route('institution.get.localite') }}", { id:id }, function(data){
                    if(data.length > 0){
                        for(var i=0; i < data.length; i++){
                            out += "<option value="+data[i].code_localite+" >"+data[i].lib_localite+"</option>";
                        }
                    }
                   // console.log(data)
                    $("#codelocalites").html(out);
                });
            }

      </script>
@endsection
