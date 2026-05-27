@extends("layout.app")
@section("titre")
    Annuaire statistique des naissances
@endsection
@section("sous-titre")
    Tableaux statistiques des naissances vivantes enregistrées
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
                <h4 class="annuaire-title">Annuaire statistique — Naissances vivantes enregistrées</h4>
                <p class="annuaire-subtitle">Répartition par centre d'état civil, genre et mois (toutes déclarations de naissance).</p>
            </div>
            <div class="card-body">
                <form id="annuaire-form" class="annuaire-form" method="POST" action="{{ route('reporting.naissance.annuaire.statistique.display') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="annee">Année <span class="text-danger">*</span></label>
                                <select class="form-control" id="annee" name="annee" required>
                                    @foreach($annees as $a)
                                        <option value="{{ $a }}" {{ $a == $anneeActuelle ? 'selected' : '' }}>{{ $a }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
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
    $('#annuaire-form').on('submit', function () {
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(document.getElementById('btn-generer-annuaire'), 'Chargement...');
        }
    });
});
</script>
@endsection
