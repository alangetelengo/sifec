@extends("layout.app")
@section("titre")
    Annuaire statistique des faits d'état civil
@endsection
@section("sous-titre")
    Tableaux statistiques par centre d'état civil
@endsection
@section("styles")
<style>
    .annuaire-card { border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; }
    .annuaire-card .card-header {
        background: linear-gradient(90deg, #f8fafc 0%, #eef5ff 100%);
        border-bottom: 1px solid #e9ecef;
    }
    .annuaire-title { margin: 0; font-weight: 700; color: #1f2937; }
    .annuaire-subtitle { margin: 4px 0 0; color: #6b7280; font-size: 0.92rem; }
    .annuaire-form .form-group label { font-weight: 600; color: #374151; }
    .annuaire-form .form-control { border-radius: 8px; }
    .annuaire-form .btn-generate {
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
        <div class="card annuaire-card">
            <div class="card-header">
                <h4 class="annuaire-title">Annuaire statistique des faits d'état civil</h4>
                <p class="annuaire-subtitle" id="annuaire-subtitle">Répartition par centre d'état civil et mois.</p>
            </div>
            <div class="card-body">
                <form id="annuaire-form" class="annuaire-form" method="POST" action="{{ route('reporting.faits.annuaire.statistique.display') }}">
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
                                <label for="annee">Année <span class="text-danger">*</span></label>
                                <select class="form-control" id="annee" name="annee" required>
                                    @foreach($annees as $a)
                                        <option value="{{ $a }}" {{ $a == $anneeActuelle ? 'selected' : '' }}>{{ $a }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="departement">Département <span class="text-danger">*</span></label>
                                <select class="form-control" id="departement" name="departement" required>
                                    <option value="">-- Sélectionnez un département --</option>
                                    @foreach($departements as $dept)
                                        <option value="{{ $dept->code_localite }}">{{ $dept->lib_localite }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-generate" id="btn-generer-annuaire">
                            <i class="fas fa-chart-bar me-1"></i> Générer l'annuaire
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
        naissance: 'Naissances vivantes — répartition par centre, genre et mois.',
        mariage: 'Mariages enregistrés — répartition par centre et mois.',
        deces: 'Décès enregistrés — répartition par centre, genre et mois.'
    };
    function refreshSubtitle() {
        var t = $('#type_fait').val();
        $('#annuaire-subtitle').text(subtitles[t] || subtitles.naissance);
    }
    $('#type_fait').on('change', refreshSubtitle);
    refreshSubtitle();

    $('#annuaire-form').on('submit', function () {
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(document.getElementById('btn-generer-annuaire'), 'Chargement...');
        }
    });
});
</script>
@endsection
