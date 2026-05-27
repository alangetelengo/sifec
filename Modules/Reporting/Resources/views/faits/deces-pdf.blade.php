<style>
    td {
        font-size: 8pt;
        padding: 2px;
    }
    th {
        font-size: 8pt;
        padding: 2px;
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

    <div style="margin-top: 5px;">
        <h2 style="text-align: center; font-size: 13pt; margin-bottom: 15px;">
            FICHE TECHNIQUE D'EXPLOITATION DES ACTES DE DECES
        </h2>

        <table cellspacing="0" style="width: 100%; font-size: 8pt; margin-bottom: 10px;">
            <tr>
                <td style="width: 25%;"><strong>{{ $departementType ?? 'Département' }} de :</strong> {{ $departement }}</td>
                @if(!empty($district))
                    <td style="width: 25%;"><strong>{{ $districtType }} de :</strong> {{ $district }}</td>
                @endif
            </tr>
            @if(!empty($arrondissement) || !empty($village))
                <tr>
                    @if(!empty($arrondissement))
                        <td style="width: 25%;"><strong>{{ $arrondissementType }} de :</strong> {{ $arrondissement }}</td>
                    @endif
                    @if(!empty($village))
                        <td style="width: 25%;"><strong>{{ $villageType }} de :</strong> {{ $village }}</td>
                    @endif
                </tr>
            @endif
            <tr>
                <td colspan="4"><strong>Année :</strong> {{ $annee }}</td>
            </tr>
        </table>

        <table class="rapport-table" cellspacing="0" style="width: 98%; margin: 0 auto; table-layout: fixed; border-collapse: collapse; border: 2px solid black;">
            <colgroup>
                <col style="width: 16%;">
                <col style="width: 6%;">
                <col style="width: 6%;">
                <col style="width: 6%;">
                <col style="width: 6%;">
                <col style="width: 18%;">
                <col style="width: 6%;">
                <col style="width: 10%;">
                <col style="width: 10%;">
                <col style="width: 16%;">
            </colgroup>
            <thead>
                <tr class="table-header">
                    <th rowspan="2" style="border: 1px solid black; text-align: center;">Mois</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">Déclaration dans les délai<br>(≤24h)</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">Déclaration hors délai<br>(>24h)</th>
                    <th rowspan="2" style="border: 1px solid black; text-align: center;">Cause décès<br>(principales)</th>
                    <th rowspan="2" style="border: 1px solid black; text-align: center;">Reconstitué</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">Age du décédé<br>(Moy.)</th>
                    <th rowspan="2" style="border: 1px solid black; text-align: center;">Total</th>
                </tr>
                <tr class="table-header">
                    <!-- Dans les délais -->
                    <th style="border: 1px solid black; text-align: center;">Sexe<br>M</th>
                    <th style="border: 1px solid black; text-align: center;">Sexe<br>F</th>
                    <!-- Hors délais -->
                    <th style="border: 1px solid black; text-align: center;">Sexe<br>M</th>
                    <th style="border: 1px solid black; text-align: center;">Sexe<br>F</th>
                    <!-- Age décédé -->
                    <th style="border: 1px solid black; text-align: center;">M</th>
                    <th style="border: 1px solid black; text-align: center;">F</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mois as $donnee)
                <tr>
                    <td style="border: 1px solid black; text-align: left;">{{ $donnee['nom_mois'] }}</td>
                    <!-- Dans les délais -->
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['delai_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['delai_f'] }}</td>
                    <!-- Hors délais -->
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['hors_delai_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['hors_delai_f'] }}</td>
                    <!-- Causes de décès (tronquées si trop longues) -->
                    <td style="border: 1px solid black; text-align: left; font-size: 7pt;">
                        {{ strlen($donnee['causes']) > 50 ? substr($donnee['causes'], 0, 47).'...' : $donnee['causes'] }}
                    </td>
                    <!-- Reconstitués -->
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['reconstitues'] }}</td>
                    <!-- Age décédé (moyenne) -->
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_decede_moy_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_decede_moy_f'] }}</td>
                    <!-- Total -->
                    <td style="border: 1px solid black; text-align: center;"><strong>{{ $donnee['total'] }}</strong></td>
                </tr>
                @endforeach
                
                <!-- Ligne de totaux -->
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td style="border: 1px solid black; text-align: center;">Total</td>
                    <!-- Dans les délais -->
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['delai_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['delai_f'] }}</td>
                    <!-- Hors délais -->
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['hors_delai_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['hors_delai_f'] }}</td>
                    <!-- Causes - vide pour totaux -->
                    <td style="border: 1px solid black; text-align: center;">-</td>
                    <!-- Reconstitués -->
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['reconstitues'] }}</td>
                    <!-- Age décédé - vide pour totaux -->
                    <td colspan="2" style="border: 1px solid black; text-align: center;">-</td>
                    <!-- Total général -->
                    <td style="border: 1px solid black; text-align: center;"><strong>{{ $totaux['total'] }}</strong></td>
                </tr>
            </tbody>
        </table>

        <table cellspacing="0" style="width: 100%; margin-top: 15px;">
            <tr>
                <td style="width: 75%; vertical-align: top;">
                    <p style="font-size: 7pt; margin: 0;"><strong>Légende :</strong></p>
                    <ul style="font-size: 7pt; margin-left: 15px; margin-top: 5px;">
                        <li><strong>M</strong> : Masculin</li>
                        <li><strong>F</strong> : Féminin</li>
                        <li><strong>Dans les délai</strong> : Déclarations faites dans les 24 heures suivant le décès</li>
                        <li><strong>Hors délai</strong> : Déclarations faites après 24 heures suivant le décès</li>
                        <li><strong>Age du décédé</strong> : Âge moyen au moment du décès (en années)</li>
                        <li><strong>Reconstitué</strong> : Actes issus de jugements ou reconstitutions administratives</li>
                    </ul>
                </td>
                <td style="width: 25%; vertical-align: bottom; text-align: right; padding-right: 70px;">
                    <p style="font-size: 7pt; margin: 0; white-space: nowrap;">Fait à Brazzaville, le {{ date('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>
</page>
