@extends('layout.app')

@section('titre')
    Rapport périodique
@endsection

@section('styles')
<style>
    .rp-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 8px 22px rgba(22, 27, 29, 0.08);
    }
    .rp-header {
        background: linear-gradient(135deg, #0d8a3f 0%, #21b931 100%);
        color: #fff;
        border-radius: 12px 12px 0 0;
    }
    .rp-kpi {
        border: 0;
        border-radius: 12px;
        color: #fff;
        min-height: 105px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .rp-kpi h3 {
        font-weight: 800;
        margin-bottom: 0;
        font-size: 1.8rem;
    }
    .rp-kpi-naissance { background: linear-gradient(135deg, #2096f3 0%, #1e88e5 100%); }
    .rp-kpi-mariage { background: linear-gradient(135deg, #f8b400 0%, #f39c12 100%); }
    .rp-kpi-deces { background: linear-gradient(135deg, #5b646b 0%, #495057 100%); }
    .rp-kpi-total { background: linear-gradient(135deg, #00b894 0%, #00a884 100%); }
    .rp-subtitle {
        font-size: 0.85rem;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
    <div class="an-shell">
        <div class="an-body">
            <div class="row">
                <div class="col-12">
                    <div class="card rp-card">
                        <div class="card-header rp-header">
                            <h4 class="mb-0 text-white">Rapport périodique des actes</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('reporting.periodique.search') }}" class="row g-3 align-items-end" id="form-rapport-periodique">
                                @csrf
                                <div class="col-md-3">
                                    <label class="form-label">Du</label>
                                    <input type="date" name="dated" value="{{ $dated }}" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Au</label>
                                    <input type="date" name="datef" value="{{ $datef }}" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100" id="btn-submit-rapport">
                                        Afficher le rapport
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-outline-secondary w-100" id="btn-export-pdf" formaction="{{ route('reporting.periodique.pdf') }}">
                                        Export PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card rp-kpi rp-kpi-naissance">
                        <div class="card-body">
                            <div class="rp-subtitle">Naissances</div>
                            <h3 class="mb-0">{{ $naissances }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card rp-kpi rp-kpi-mariage text-dark">
                        <div class="card-body">
                            <div class="rp-subtitle text-dark">Mariages</div>
                            <h3 class="mb-0 text-dark">{{ $mariages }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card rp-kpi rp-kpi-deces">
                        <div class="card-body">
                            <div class="rp-subtitle">Décès</div>
                            <h3 class="mb-0">{{ $deces }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card rp-kpi rp-kpi-total">
                        <div class="card-body">
                            <div class="rp-subtitle">Total actes</div>
                            <h3 class="mb-0">{{ $totalActes }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card rp-card">
                        <div class="card-header">
                            <h5 class="mb-0">Détail mensuel</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Période</th>
                                        <th>Naissances</th>
                                        <th>Mariages</th>
                                        <th>Décès</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($mois as $ligne)
                                        <tr>
                                            <td>{{ ucfirst($ligne['label']) }}</td>
                                            <td>{{ $ligne['naissances'] }}</td>
                                            <td>{{ $ligne['mariages'] }}</td>
                                            <td>{{ $ligne['deces'] }}</td>
                                            <td>{{ $ligne['naissances'] + $ligne['deces'] + $ligne['mariages'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Aucune donnée sur la période sélectionnée.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
        $('#form-rapport-periodique').on('submit', function () {
            var btn = document.activeElement && document.activeElement.form === this
                ? document.activeElement
                : document.getElementById('btn-submit-rapport');
            if (typeof sifecBtnLoading === 'function') {
                var label = btn && btn.id === 'btn-export-pdf' ? 'Export PDF...' : 'Chargement...';
                sifecBtnLoading(btn, label);
            }
        });
    });
</script>
@endsection
