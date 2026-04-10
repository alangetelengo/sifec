<html lang="fr">
@extends("layout.app")
@section("titre")
    Réquisition
@endsection

@section("corps")
<div class="page-sifec-form">
    <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4> REQUISITION POUR {{ $req->declarationNaissance ? $req->declarationNaissance->libelleAffichageType() : "LA RECTIFICATION DE L'ACTE" }}  N° <strong style="color: red">{{ $req->declarationNaissance->numero_certificat ?? $req->rectification->numero_rectification}}</strong></h4>
                </div>
                <div class="card wizard-content">
                    <div class="card-body">
                        <div class="ligne">
                            <h4>INFORMATIONS DE LA REQUISITION</h4>
                        </div>
                        <form action="{{ route('requisition.update',$req->code_requisition) }}" method="post" enctype="multipart/form-data">
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
                                    <label class="form-label">N° de la réquisition</label>
                                    <input type="text" class="form-control" placeholder="Numéro de la requisition" name="num_requisition" value="{{ old('num_requisition') }}">
                                </div>

                                <div class="mb-2 col-md-2">
                                    <label class="form-label">Date de la requisition <span class="text-danger">*</span></label>
                                    <input type="date" name="date_requisition" class="form-control" value="{{ old('date_requisition') }}">
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Document <span class="text-danger">*</span></label>
                                    <input type="file" name="document_requisition"  class="form-control" id="document" value="{{ old('document_requisition') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-3">
                                    <br><br>
                                    <a href="{{ route("requisition.index") }}"><button type="button" class="btn btn-sm btn-danger">Liste des réquisitions</button></a>
                                    <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

