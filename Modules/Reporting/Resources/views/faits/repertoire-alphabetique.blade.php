@extends("layout.app")
@section("titre")
    Répertoire alphabétique des faits d'état civil
@endsection
@section("sous-titre")
    Liste alphabétique des actes enregistrés
@endsection
@section("styles")
<style>
    .repertoire-card { border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; }
    .repertoire-card .card-header {
        background: linear-gradient(90deg, #f8fafc 0%, #eef5ff 100%);
        border-bottom: 1px solid #e9ecef;
    }
    .repertoire-title { margin: 0; font-weight: 700; color: #1f2937; }
    .repertoire-subtitle { margin: 4px 0 0; color: #6b7280; font-size: 0.92rem; }
    .repertoire-form .form-group label { font-weight: 600; color: #374151; }
    .repertoire-form .form-control { border-radius: 8px; }
    .repertoire-form .btn-generate {
        min-width: 240px;
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1rem;
    }
</style>
@endsection
@section("corps")
<div class="row">
    <div class="col-12">
        <div class="card repertoire-card">
            <div class="card-header">
                <h4 class="repertoire-title">Répertoire alphabétique des faits d'état civil</h4>
                <p class="repertoire-subtitle" id="repertoire-subtitle">Liste des actes classés par ordre alphabétique.</p>
            </div>
            <div class="card-body">
                <form id="repertoire-form" class="repertoire-form" method="POST" action="{{ route('reporting.faits.repertoire.alphabetique.display') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="type_fait">Type de fait <span class="text-danger">*</span></label>
                                <select class="form-control" id="type_fait" name="type_fait" required>
                                    <option value="naissance" {{ ($typeFait ?? 'naissance') === 'naissance' ? 'selected' : '' }}>Naissance</option>
                                    <option value="mariage" {{ ($typeFait ?? '') === 'mariage' ? 'selected' : '' }}>Mariage</option>
                                    <option value="deces" {{ ($typeFait ?? '') === 'deces' ? 'selected' : '' }}>Décès</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="dated">Du</label>
                                <input type="date" class="form-control" id="dated" name="dated" value="{{ $dated ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="datef">Au</label>
                                <input type="date" class="form-control" id="datef" name="datef" value="{{ $datef ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-generate" id="btn-generer-repertoire">
                            <i class="fas fa-list-ol me-1"></i> Générer le répertoire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script>
$(function () {
    var subtitles = {
        naissance: 'Actes de naissance — ordre alphabétique par nom de l\'enfant.',
        mariage: 'Actes de mariage — ordre alphabétique par époux puis épouse.',
        deces: 'Actes de décès — ordre alphabétique par nom du défunt.'
    };
    function refreshSubtitle() {
        var t = $('#type_fait').val();
        $('#repertoire-subtitle').text(subtitles[t] || subtitles.naissance);
    }
    $('#type_fait').on('change', refreshSubtitle);
    refreshSubtitle();

    $('#repertoire-form').on('submit', function () {
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(document.getElementById('btn-generer-repertoire'), 'Chargement...');
        }
    });
});
</script>
@endsection
