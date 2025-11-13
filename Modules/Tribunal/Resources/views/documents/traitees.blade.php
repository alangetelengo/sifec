@extends('layout.app')
@section('titre')
Document traités
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Document traités (prêtes à être envoyés au centre d'état civil)</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display table table-bordered" style="min-width: 845px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Module</th>
                                <th>Identifiant</th>
                                <th>Type de déclaration</th>
                                <th>Nom(s)</th>
                                <th>Prénom(s)</th>
                                <th>Date clé</th>
                                <th>Document importé</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($declarations as $item)
                            @php
                                $module = $item->module;
                                if ($module === 'naissance') {
                                    $id = $item->code_declaration_naissance;
                                    $typeDeclaration = $item->type_declaration ?? '-';
                                    $nom = $item->enfant->nom ?? '-';
                                    $prenom = $item->enfant->prenom ?? '-';
                                    $date = $item->enfant->date_naissance ?? '-';
                                    $libTypeDoc = $item->requisition ? $item->requisition->typeRequisition->lib_type_requisition : ($item->jugement ? $item->jugement->typeJugement->lib_type_jugement : '-');
                                } elseif ($module === 'deces') {
                                    $id = $item->code_declaration_deces;
                                    $typeDeclaration = $item->type_declaration ?? '-';
                                    $nom = $item->defunt->nom ?? '-';
                                    $prenom = $item->defunt->prenom ?? '-';
                                    $date = $item->defunt->date_deces ?? '-';
                                    $libTypeDoc = $item->requisition ? $item->requisition->typeRequisition->lib_type_requisition : ($item->jugement ? $item->jugement->typeJugement->lib_type_jugement : '-');
                                } else { // mariage
                                    $id = $item->code_declaration_mariage;
                                    $typeDeclaration = $item->type_declaration ?? '-';
                                    $nom = ($item->epoux->nom ?? '-') . ' & ' . ($item->epouse->nom ?? '-');
                                    $prenom = ($item->epoux->prenom ?? '-') . ' & ' . ($item->epouse->prenom ?? '-');
                                    $date = $item->date_mariage ?? '-';
                                    $libTypeDoc = $item->requisition ? $item->requisition->typeRequisition->lib_type_requisition : ($item->jugement ? $item->jugement->typeJugement->lib_type_jugement : '-');
                                }
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                    <span class="badge {{ $module == 'naissance' ? 'bg-primary' : ($module == 'deces' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($module) }}
                                    </span>
                                </td>
                                <td>{{ $id }}</td>
                                <td>{{ $typeDeclaration }}</td>
                                <td>{{ $nom }}</td>
                                <td>{{ $prenom }}</td>
                                <td>{{ $date && $date != '-' ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($libTypeDoc && $libTypeDoc != '-')
                                        <span class="badge bg-info">{{ $libTypeDoc }}</span>
                                    @else
                                        <span class="badge bg-secondary">Aucun</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-row gap-1 w-100">
                                        {{-- Action : Envoyer au centre d'état civil --}}
                                        <form action="{{ route('tribunal.declarations.envoyer_officiel') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="hidden" name="module" value="{{ $module }}">
                                            <button type="submit" class="btn btn-success btn-xs" title="Envoyer au centre d'état civil">
                                                <i class="fas fa-paper-plane"></i> Envoyer
                                            </button>
                                        </form>
                                        {{-- Action : Modifier le dossier --}}
                                        <a href="{{ route('tribunal.declarations.edit', ['type' => $module, 'id' => $id]) }}"
                                           class="btn btn-info btn-xs" title="Modifier le dossier">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Module</th>
                                <th>Identifiant</th>
                                <th>Type de déclaration</th>
                                <th>Nom(s)</th>
                                <th>Prénom(s)</th>
                                <th>Date clé</th>
                                <th>Document importé</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    // Initialisation des DataTables avec la langue française
    $(function() {
        $('table.display').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            }
        });
    });
</script>
@endsection
