<style>
    table {
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 13px;
    }
    th, td {
        border: 1px solid #222;
        padding: 8px 10px;
    }
    th {
        background: #f1f1f1;
        text-align: center;
        font-weight: bold;
    }
    td.num {
        text-align: center;
    }
    .title{
        text-align: center;
        margin-bottom: 10px;
    }
    .period {
        text-align: center;
        font-weight: bold;
        margin-bottom: 12px;
        font-size: 12px;
    }
    .report-table {
        width: 190mm;
        margin: 0 auto;
    }
    .col-period { width: 40%; }
    .col-num { width: 15%; }
</style>

<page orientation="portrait" backcolor="#FEFEFE" footer="date;time;page">
    <div class="title">
        <h3>RAPPORT PERIODIQUE DES ACTES</h3>
    </div>
    <div class="period">
        Periode : du {{ date('d/m/Y', strtotime($dated)) }} au {{ date('d/m/Y', strtotime($datef)) }}
    </div>

    <table class="report-table">
        <colgroup>
            <col class="col-period">
            <col class="col-num">
            <col class="col-num">
            <col class="col-num">
            <col class="col-num">
        </colgroup>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Naissances</th>
                <th>Mariages</th>
                <th>Deces</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mois as $ligne)
                <tr>
                    <td>{{ ucfirst($ligne['label']) }}</td>
                    <td class="num">{{ $ligne['naissances'] }}</td>
                    <td class="num">{{ $ligne['mariages'] }}</td>
                    <td class="num">{{ $ligne['deces'] }}</td>
                    <td class="num">{{ $ligne['naissances'] + $ligne['mariages'] + $ligne['deces'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Aucune donnee disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</page>
