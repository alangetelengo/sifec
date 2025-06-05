<html lang="fr">
@extends("layout.app")
@section("titre")
    Jugement
@endsection

@section("corps")
    <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4> {{ $jugmt->type_jugement }} POUR LE {{ $jugmt->declarationNaissance->type_declaration }}  N° <strong style="color: red">{{ $jugmt->declarationNaissance->numero_certificat}}</strong></h4>
                </div>
                <div class="card wizard-content">
                    <div class="card-body">
                        <div class="ligne">
                            <h4>INFORMATIONS DU JUGEMENT</h4>
                        </div>
                        <form action="{{ route('jugement.update',$jugmt->code_jugement) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="mb-2 col-md-5">
                                    <label class="form-label">Tribunal <span class="text-danger">*</span></label>
                                    <select name="cui" class="form-control" readOnly>
                                        <option value="{{ Auth::user()->affectationActive()->cui }}">{{Auth::user()->affectationActive()->institution->lib_institution }}</option>
                                    </select>
                                </div>

                                <div class="mb-2 col-md-2">
                                    <label class="form-label">N° du jugement</label>
                                    <input type="text" class="form-control" placeholder="Numéro du jugement" name="num_jugement" value="{{$jugmt->num_jugement }}">
                                </div>

                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Date du jugement <span class="text-danger">*</span></label>
                                    <input type="date" name="date_jugement" class="form-control" value="{{ $jugmt->date_jugement }}">
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Document <span class="text-danger">*</span></label>
                                    <input type="file" name="document_jugement"  class="form-control" id="document" value="{{ $jugmt->document_jugement }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-3">
                                    <br><br>
                                    <a href="{{ route("jugement.index") }}"><button type="button" class="btn btn-sm btn-danger">Liste des jugements</button></a>
                                    <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

