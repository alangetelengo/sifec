@php
    use Modules\Reporting\Http\Controllers\ReportingController as RC;
@endphp
<style>
    td, th {
        font-size: 7.5pt;
        padding: 3px 4px;
        vertical-align: middle;
        line-height: 1.15;
    }
    .table-header {
        background-color: #f0f0f0;
        font-weight: bold;
        text-align: center;
    }
    .dep-label {
        text-align: left;
        font-weight: normal;
    }
    .num { text-align: center; }
    .total-row { font-weight: bold; }
</style>

<page orientation="landscape" backcolor="#FEFEFE" footer="page" style="font-size: 9pt">
    <table cellspacing="0" style="width: 98%; margin: 0 auto 4px auto; font-size: 8pt;">
        <tr>
            <td style="width: 50%; text-align: left; vertical-align: top; padding: 0;">
                <div style="display: inline-block; width: 250px; text-align: center; font-size: 7pt; line-height: 1.05;">
                    <strong>MINISTERE DE L'INTERIEUR,<br>DE LA DECENTRALISATION<br>ET DU DEVELOPPEMENT LOCAL</strong><br>
                    *********************<br>
                    <strong>DIRECTION GENERALE<br>DE L'ADMINISTRATION DU TERRITOIRE</strong><br>
                    *********************<br>
                    <strong>DIRECTION DE L'ETAT CIVIL</strong><br>
                    *********************
                </div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top; padding: 0; font-size: 8pt;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unité - Travail - Progrès
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-size: 10pt; font-weight: bold; margin: 0 0 6px 0;">
        TABLEAU STATISTIQUE NATIONAL DES ACTES PAR DEPARTEMENT — ANNEE {{ $annee }}
    </div>

    <table class="rapport-table" cellspacing="0" style="width: 98%; margin: 0 auto; table-layout: fixed; border-collapse: collapse; border: 2px solid black;">
        <colgroup>
            <col style="width: 15%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 8%;">
        </colgroup>
        <thead>
            <tr class="table-header">
                <th rowspan="2" style="border: 1px solid black;">Départements</th>
                <th colspan="4" style="border: 1px solid black;">Naissances</th>
                <th colspan="4" style="border: 1px solid black;">Décès</th>
                <th colspan="4" style="border: 1px solid black;">Mariages</th>
            </tr>
            <tr class="table-header">
                <th style="border: 1px solid black;">H</th>
                <th style="border: 1px solid black;">F</th>
                <th style="border: 1px solid black;">T</th>
                <th style="border: 1px solid black;">Taux</th>
                <th style="border: 1px solid black;">H</th>
                <th style="border: 1px solid black;">F</th>
                <th style="border: 1px solid black;">T</th>
                <th style="border: 1px solid black;">Taux</th>
                <th style="border: 1px solid black;">Régime monogamie</th>
                <th style="border: 1px solid black;">Régime polygamie</th>
                <th style="border: 1px solid black;">T</th>
                <th style="border: 1px solid black;">Taux</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lignes as $ligne)
            <tr>
                <td class="dep-label" style="border: 1px solid black;">{{ $ligne['departement'] }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['naissance']['h']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['naissance']['f']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['naissance']['t']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtTauxTableauNational($ligne['naissance']['taux']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['deces']['h']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['deces']['f']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['deces']['t']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtTauxTableauNational($ligne['deces']['taux']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['mariage']['mono']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['mariage']['poly']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($ligne['mariage']['t']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtTauxTableauNational($ligne['mariage']['taux']) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="dep-label" style="border: 1px solid black;">Total</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['naissance']['h'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['naissance']['f'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['naissance']['t'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtTauxTableauNational($total['naissance']['taux']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['deces']['h'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['deces']['f'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['deces']['t'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtTauxTableauNational($total['deces']['taux']) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['mariage']['mono'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['mariage']['poly'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtCellTableauNational($total['mariage']['t'], true) }}</td>
                <td class="num" style="border: 1px solid black;">{{ RC::fmtTauxTableauNational($total['mariage']['taux']) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="font-size: 7pt; color: #444; margin-top: 6px;">
        Document généré le {{ date('d/m/Y') }}.
    </p>
</page>
