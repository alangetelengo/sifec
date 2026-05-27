<style>
    td, th {
        font-size: 8pt;
        padding: 2px;
        vertical-align: middle;
    }
    b {
        font-size: 10pt;
    }
    .table-header {
        background-color: #f0f0f0;
        font-weight: bold;
        text-align: center;
    }
</style>

<page orientation="landscape" backcolor="#FEFEFE" footer="date;time;page" style="font-size: 10pt">
    <table cellspacing="0" style="width: 98%; margin: 0 0 20px 0; font-size: 10pt;">
        <tr>
            <td style="width:50%; text-align: left; vertical-align: top; padding-left: 0;">
                <div style="display: inline-block; width: 260px; text-align: center;">
                    <strong>MINISTERE DE L'INTERIEUR,<br>DE LA DECENTRALISATION<br>ET DU DEVELOPPEMENT LOCAL</strong><br>
                    *********************<br>
                    <strong>DIRECTION GENERALE<br>DE L'ADMINISTRATION DU TERRITOIRE</strong><br>
                    *********************<br>
                    <strong>DIRECTION DE L'ETAT CIVIL</strong><br>
                    *********************
                </div>
            </td>
            <td style="width:50%; text-align: right; vertical-align: top; padding-top: 0;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unité - Travail - Progrès
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px;">
        <h2 style="text-align: center; font-size: 14pt; margin-bottom: 20px;">
            FICHE TECHNIQUE D'EXPLOITATION DES ACTES DE MARIAGE
        </h2>

        <table cellspacing="0" style="width: 100%; font-size: 9pt; margin-bottom: 15px;">
            <tr>
                <td style="width: 25%;"><strong>{{ $departementType ?? 'Département' }} de :</strong> {{ $departement }}</td>
                @if(!empty($district))
                    <td style="width: 25%;"><strong>{{ $districtType }} de :</strong> {{ $district }}</td>
                @endif
                @if(!empty($arrondissement))
                    <td colspan="{{ empty($district) ? 3 : 2 }}"><strong>{{ $arrondissementType }} de :</strong> {{ $arrondissement }}</td>
                @endif
            </tr>
            <tr>
                <td colspan="4"><strong>Année :</strong> {{ $annee }}</td>
            </tr>
        </table>

        <table class="rapport-table" cellspacing="0" style="width: 98%; margin: 0 auto; table-layout: fixed; border-collapse: collapse; border: 2px solid black;">
            <colgroup>
                <col style="width: 16%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 9%;">
                <col style="width: 9%;">
                <col style="width: 24%;">
            </colgroup>
            <thead>
                <tr class="table-header">
                    <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Mois</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">Options</th>
                    <th colspan="3" style="border: 1px solid black; text-align: center;">régimes</th>
                    <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Reconstitués</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">Age des époux</th>
                    <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Total</th>
                </tr>
                <tr class="table-header">
                    <th style="border: 1px solid black; text-align: center;">Monogamie<br>(nombre)</th>
                    <th style="border: 1px solid black; text-align: center;">Polygamie<br>(nombre)</th>
                    <th style="border: 1px solid black; text-align: center;">Séparation des<br>biens (Nbre)</th>
                    <th style="border: 1px solid black; text-align: center;">Com. Réduite aux<br>Acquêts</th>
                    <th style="border: 1px solid black; text-align: center;">Com.<br>Conventionnelle<br>(Nbre)</th>
                    <th style="border: 1px solid black; text-align: center;">M</th>
                    <th style="border: 1px solid black; text-align: center;">F</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mois as $donnee)
                <tr>
                    <td style="border: 1px solid black; text-align: left; vertical-align: middle;">{{ $donnee['nom_mois'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['monogamie'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['polygamie'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['separation_biens'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['communaute_acquets'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['communaute_conventionnelle'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['reconstitues'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_epoux_moy'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_epouse_moy'] }}</td>
                    <td style="border: 1px solid black; text-align: center;"><strong>{{ $donnee['total'] }}</strong></td>
                </tr>
                @endforeach

                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td style="border: 1px solid black; text-align: center;">Total année</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['monogamie'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['polygamie'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['separation_biens'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['communaute_acquets'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['communaute_conventionnelle'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['reconstitues'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['age_epoux_moy'] ?? '-' }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['age_epouse_moy'] ?? '-' }}</td>
                    <td style="border: 1px solid black; text-align: center;"><strong>{{ $totaux['total'] }}</strong></td>
                </tr>
            </tbody>
        </table>

        <table cellspacing="0" style="width: 100%; margin-top: 15px;">
            <tr>
                <td style="width: 75%; vertical-align: top;">
                    <p style="font-size: 7pt; margin: 0;"><strong>Légende :</strong></p>
                    <ul style="font-size: 7pt; margin-left: 15px; margin-top: 5px;">
                        <li><strong>M</strong> : Âge moyen des époux (masculin)</li>
                        <li><strong>F</strong> : Âge moyen des épouses (féminin)</li>
                        <li><strong>Monogamie / Polygamie</strong> : Options de mariage enregistrées sur la déclaration</li>
                        <li><strong>Séparation des biens (RSB)</strong> : Chaque époux conserve la propriété de ses biens</li>
                        <li><strong>Communauté réduite aux acquêts (RCA)</strong> : Seuls les biens acquis pendant le mariage sont communs</li>
                        <li><strong>Communauté conventionnelle (RCC)</strong> : Régime matrimonial défini par contrat des époux</li>
                    </ul>
                </td>
                <td style="width: 25%; vertical-align: bottom; text-align: right; padding-right: 70px;">
                    <p style="font-size: 7pt; margin: 0; white-space: nowrap;">Fait à Brazzaville, le {{ date('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>
</page>
