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

    <div style="margin-top: 10px;">
        <h2 style="text-align: center; font-size: 14pt; margin-bottom: 20px;">
            FICHE TECHNIQUE D'EXPLOITATION DES ACTES DE NAISSANCE
        </h2>

        <table cellspacing="0" style="width: 100%; font-size: 9pt; margin-bottom: 15px;">
            <tr>
                <td style="width: 25%;"><strong>Année :</strong> {{ $annee }}</td>
                <td style="width: 25%;"><strong>{{ $departementType ?? 'Département' }} :</strong> {{ $departement }}</td>
                @if(!empty($district))
                    <td style="width: 25%;"><strong>{{ $districtType }} :</strong> {{ $district }}</td>
                @endif
                @if(!empty($arrondissement))
                    <td style="width: 25%;"><strong>{{ $arrondissementType }} :</strong> {{ $arrondissement }}</td>
                @endif
            </tr>
            @if(!empty($village))
                <tr>
                    <td colspan="4"><strong>{{ $villageType }} :</strong> {{ $village }}</td>
                </tr>
            @endif
        </table>

        <table class="rapport-table" cellspacing="0" style="width: 98%; margin: 0 auto; table-layout: fixed; border-collapse: collapse; border: 2px solid black;">
            <colgroup>
                <col style="width: 18%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 7%;">
                <col style="width: 10%;">
                <col style="width: 10%;">
                <col style="width: 10%;">
                <col style="width: 10%;">
            </colgroup>
            <thead>
                <tr class="table-header">
                    <th rowspan="2" style="border: 1px solid black; text-align: center;">MOIS</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">DECLARATIONS DANS LES DELAIS (<= 30j)</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">DECLARATIONS HORS DELAIS (> 30j)</th>
                    <th colspan="2" style="border: 1px solid black; text-align: center;">ACTES RECONSTITUES</th>
                    <th colspan="3" style="border: 1px solid black; text-align: center;">AGE DE LA MERE</th>
                    <th rowspan="2" style="border: 1px solid black; text-align: center;">TOTAL</th>
                </tr>
                <tr class="table-header">
                    <!-- Dans les délais -->
                    <th style="border: 1px solid black; text-align: center;">M</th>
                    <th style="border: 1px solid black; text-align: center;">F</th>
                    <!-- Hors délais -->
                    <th style="border: 1px solid black; text-align: center;">M</th>
                    <th style="border: 1px solid black; text-align: center;">F</th>
                    <!-- Reconstitués -->
                    <th style="border: 1px solid black; text-align: center;">M</th>
                    <th style="border: 1px solid black; text-align: center;">F</th>
                    <!-- Age mère -->
                    <th style="border: 1px solid black; text-align: center;">Min</th>
                    <th style="border: 1px solid black; text-align: center;">Moy</th>
                    <th style="border: 1px solid black; text-align: center;">Max</th>
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
                    <!-- Reconstitués -->
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['reconstitue_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['reconstitue_f'] }}</td>
                    <!-- Age mère -->
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_mere_min'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_mere_moy'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $donnee['age_mere_max'] }}</td>
                    <!-- Total -->
                    <td style="border: 1px solid black; text-align: center;"><strong>{{ $donnee['total'] }}</strong></td>
                </tr>
                @endforeach

                <!-- Ligne de totaux -->
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td style="border: 1px solid black; text-align: center;">TOTAUX</td>
                    <!-- Dans les délais -->
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['delai_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['delai_f'] }}</td>
                    <!-- Hors délais -->
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['hors_delai_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['hors_delai_f'] }}</td>
                    <!-- Reconstitués -->
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['reconstitue_m'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $totaux['reconstitue_f'] }}</td>
                    <!-- Age mère - vide pour totaux -->
                    <td colspan="3" style="border: 1px solid black; text-align: center;">-</td>
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
                        <li><strong>Dans les délais</strong> : Déclarations faites dans les 30 jours suivant la naissance</li>
                        <li><strong>Hors délais</strong> : Déclarations faites après 30 jours suivant la naissance</li>
                        <li><strong>Actes reconstitués</strong> : Actes issus de jugements ou de réquisitions du tribunal</li>
                    </ul>
                </td>
                <td style="width: 25%; vertical-align: bottom; text-align: right; padding-right: 70px;">
                    <p style="font-size: 7pt; margin: 0; white-space: nowrap;">Fait à Brazzaville, le {{ date('d/m/Y') }}</p>
                    {{-- <p style="font-size: 7pt; margin: 0; white-space: nowrap;">le {{ date('d/m/Y') }}</p> --}}
                </td>
            </tr>
        </table>
    </div>
</page>
