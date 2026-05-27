@extends("layout.app")
@section("titre")
    Tableau national par département
@endsection
@section("sous-titre")
    Statistiques des actes par département
@endsection
@section("styles")
<style>
    .tn-card { border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; }
    .tn-card .card-header {
        background: linear-gradient(90deg, #f8fafc 0%, #eef5ff 100%);
        border-bottom: 1px solid #e9ecef;
    }
    .tn-title { margin: 0; font-weight: 700; color: #1f2937; }
    .tn-subtitle { margin: 4px 0 0; color: #6b7280; font-size: 0.92rem; }
    .tn-form .form-group label { font-weight: 600; color: #374151; }
    .tn-form .form-control { border-radius: 8px; }
    .tn-form .btn-generate {
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
        <div class="card tn-card">
            <div class="card-header">
                <h4 class="tn-title">Tableau statistique national des actes par département</h4>
                <p class="tn-subtitle">Naissances, décès et mariages (actes émis) — répartition par département et taux.</p>
            </div>
            <div class="card-body">
                <form id="form-tableau-national" class="tn-form" method="POST" action="{{ route('reporting.statistique.tableau.national.departements.display') }}">
                    @csrf
                    <div class="row justify-content-center">
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
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-generate" id="btn-generer-tableau">
                            <i class="fas fa-table me-1"></i> Générer le tableau
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
    $('#form-tableau-national').on('submit', function () {
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(document.getElementById('btn-generer-tableau'), 'Chargement...');
        }
    });
});
</script>
@endsection
