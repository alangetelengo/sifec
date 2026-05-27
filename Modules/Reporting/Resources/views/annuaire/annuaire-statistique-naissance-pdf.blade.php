<style>
    td, th {
        font-size: {{ $modeCompact ? '6.5pt' : '7pt' }};
        padding: {{ $modeCompact ? '1px 2px' : '2px 3px' }};
        vertical-align: middle;
        line-height: 1.05;
    }
    .table-header {
        background-color: #f0f0f0;
        font-weight: bold;
        text-align: center;
    }
    .centre-nom {
        font-weight: bold;
        text-align: left;
        font-size: {{ $modeCompact ? '6.5pt' : '7pt' }};
    }
    .genre-label {
        text-align: left;
        font-size: {{ $modeCompact ? '6.5pt' : '7pt' }};
    }
    .num {
        text-align: center;
    }
    .ligne-donnee {
        height: {{ $hauteurLigneMm }}mm;
    }
</style>

<page orientation="landscape" backcolor="#FEFEFE" footer="page" style="font-size: 9pt">
    <table cellspacing="0" style="width: 100%; margin: 0 0 4px 0; font-size: 8pt;">
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

    <div style="text-align: center; font-size: 11pt; font-weight: bold; margin: 0 0 2px 0;">ANNUAIRE {{ $annee }}</div>
    <div style="text-align: center; font-size: 9pt; font-weight: bold; text-decoration: underline; margin: 0 0 4px 0;">
        Tableaux statistique des naissances vivantes enregistrées
    </div>

    <table cellspacing="0" style="width: 100%; font-size: 8pt; margin: 0 0 4px 0;">
        <tr>
            <td style="width: 55%; padding: 0;"><strong>{{ $departementType ?? 'Département' }} de :</strong> {{ $departement }}</td>
            <td style="width: 45%; padding: 0; text-align: right; font-size: 7pt; white-space: nowrap;">
                Fait à Brazzaville, le {{ date('d/m/Y') }}
            </td>
        </tr>
    </table>

    <table class="rapport-table" cellspacing="0" style="width: 100%; margin: 0; table-layout: fixed; border-collapse: collapse; border: 2px solid black;">
        <colgroup>
            <col style="width: 15%;">
            <col style="width: 6%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
            <col style="width: 6.5%;">
            <col style="width: 6.5%;">
        </colgroup>
        <thead>
            <tr class="table-header ligne-donnee">
                <th style="border: 1px solid black;">Centres d'état civil</th>
                <th style="border: 1px solid black;">Genre / totaux</th>
                @foreach($moisCourts as $libMois)
                    <th style="border: 1px solid black;">{{ $libMois }}</th>
                @endforeach
                <th style="border: 1px solid black;">Total/centre</th>
                <th style="border: 1px solid black;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centres as $centre)
            <tr class="ligne-donnee">
                <td rowspan="3" class="centre-nom" style="border: 1px solid black;">{{ $centre['nom'] }}</td>
                <td class="genre-label" style="border: 1px solid black;">Masculin</td>
                @for($m = 1; $m <= 12; $m++)
                    <td class="num" style="border: 1px solid black;">{{ $centre['masculin'][$m] ?: '' }}</td>
                @endfor
                <td class="num" style="border: 1px solid black;"><strong>{{ $centre['masculin']['total'] ?: '' }}</strong></td>
                <td class="num" style="border: 1px solid black;"></td>
            </tr>
            <tr class="ligne-donnee">
                <td class="genre-label" style="border: 1px solid black;">féminin</td>
                @for($m = 1; $m <= 12; $m++)
                    <td class="num" style="border: 1px solid black;">{{ $centre['feminin'][$m] ?: '' }}</td>
                @endfor
                <td class="num" style="border: 1px solid black;"><strong>{{ $centre['feminin']['total'] ?: '' }}</strong></td>
                <td class="num" style="border: 1px solid black;"></td>
            </tr>
            <tr class="ligne-donnee">
                <td class="genre-label" style="border: 1px solid black;"><strong>total</strong></td>
                @for($m = 1; $m <= 12; $m++)
                    <td class="num" style="border: 1px solid black;"><strong>{{ $centre['total'][$m] ?: '' }}</strong></td>
                @endfor
                <td class="num" style="border: 1px solid black;"><strong>{{ $centre['total']['total'] ?: '' }}</strong></td>
                <td class="num" style="border: 1px solid black;"><strong>{{ $centre['pourcentage'] > 0 ? number_format($centre['pourcentage'], 1, ',', ' ') : '' }}</strong></td>
            </tr>
            @endforeach

            <tr class="ligne-donnee" style="background-color: #e0e0e0;">
                <td rowspan="3" class="centre-nom" style="border: 1px solid black;"><strong>Total/mois</strong></td>
                <td class="genre-label" style="border: 1px solid black;"><strong>Masculin</strong></td>
                @for($m = 1; $m <= 12; $m++)
                    <td class="num" style="border: 1px solid black;"><strong>{{ $totauxMois['masculin'][$m] ?: '' }}</strong></td>
                @endfor
                <td class="num" style="border: 1px solid black;"><strong>{{ $totauxMois['masculin']['total'] ?: '' }}</strong></td>
                <td class="num" style="border: 1px solid black;"></td>
            </tr>
            <tr class="ligne-donnee" style="background-color: #e0e0e0;">
                <td class="genre-label" style="border: 1px solid black;"><strong>féminin</strong></td>
                @for($m = 1; $m <= 12; $m++)
                    <td class="num" style="border: 1px solid black;"><strong>{{ $totauxMois['feminin'][$m] ?: '' }}</strong></td>
                @endfor
                <td class="num" style="border: 1px solid black;"><strong>{{ $totauxMois['feminin']['total'] ?: '' }}</strong></td>
                <td class="num" style="border: 1px solid black;"><strong>100</strong></td>
            </tr>
            <tr class="ligne-donnee" style="background-color: #e0e0e0;">
                <td class="genre-label" style="border: 1px solid black;"><strong>total</strong></td>
                @for($m = 1; $m <= 12; $m++)
                    <td class="num" style="border: 1px solid black;"><strong>{{ $totauxMois['total'][$m] ?: '' }}</strong></td>
                @endfor
                <td class="num" style="border: 1px solid black;"><strong>{{ $totauxMois['total']['total'] ?: '' }}</strong></td>
                <td class="num" style="border: 1px solid black;"><strong>100</strong></td>
            </tr>
        </tbody>
    </table>
</page>
