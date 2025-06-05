<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">




    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://casp-congo.com/sifec/bootstrap.min.css'">

    <!-- Style -->
    <link rel="stylesheet" href="https://casp-congo.com/sifec/style.css">

    <title>Confirmation de paiement</title>
  </head>
  <body>


  <div class="content">

    <div class="container" style="width: 90%; text-align: center; box-shadow: -2px -1px 28px 3px silver; padding:30px">
        <img src="https://casp-congo.com/sifec/logo-sifec.gif" style="width:30%; margin-bottom:20px">
      <h2 class=""> <img src="https://casp-congo.com/sifec/img.png" style="width:20px"/> Votre paiement a été effectué avec succès</h2>
    <hr/>
      <div class="table-responsive custom-table-responsive" style="width:100%; margin-bottom:15px">

        <table class="table custom-table" style="width:100%">
          <tbody>
            <tr>

              <td style="text-align: left"><strong>
                DOCUMENT
              </strong>
              </td>
              <td style="text-align: right">{{$demande->type_document." d'acte de ".$demande->type_acte}}</td>

            </tr>
            <tr class="spacer"><td colspan="100"></td></tr>


            <tr>

                <td style="text-align: left"><strong>MONTANT PAYE</strong></td>
                <td style="text-align: right">{{$demande->cout}} XAF</td>

              </tr>
              <tr class="spacer"><td colspan="100"></td></tr>

            <tr>


              <td style="text-align: left"> <strong>CENTRE D'ETAT CIVIL ASSOCIE</strong></td>
              <td style="text-align: right">{{$demande->cec_associe}}</td>

            </tr>


          </tbody>
        </table>

      </div>
      <a href="http://localhost/sifec-30-07-2024/sifec/portail-sifec.cg/" style="margin-top:15px" class="btn btn-success">Retour au portail de SIFEC - CONGO</a>
    </div>

  </div>





  </body>
</html>
