<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">




    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('success_style/bootstrap.min.css') }}">

    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('success_style/style.css')}}">

    <script src="{{ asset('success_style/jquery.min.js')}}"></script>

    <title>Paiement en attente</title>
  </head>
  <body>


  <div class="content">

    <div class="container" style="width: 50%; text-align: center; box-shadow: -2px -1px 28px 3px silver; padding:30px">
        <img src="{{ asset('success_style/logo-sifec.gif')}}" style="width:27%; margin-bottom:20px">
        <h2 class="" id="message">
        <img src="{{ asset('success_style/loading.gif')}}" id="loader" style="width:50px"/>
        <img src="{{ asset('success_style/img.png')}}" id="ok" style="width:50px"/>
        Votre demande est en attente de paiement</h2>

        <input type="hidden" value="{{ $paiementDemande->code_demande}}" id="codeDemande">
        <input type="hidden" value="{{ $transid}}" id="transid">

        @php
            $statutPaiement = App\Sifec\SifecFacade::statutPaiement("ACSIMOMO66e1b005aefc21726066693");
            // $statutPaiement = App\Sifec\SifecFacade::statutPaiement("ACSIMOMO66e194aed9f4f1726059694");
            // dump($statutPaiement);
            // die();
            $status = "pending";
            // if($statutPaiement != null){
            //     $status = $statutPaiement->status;
            // }
        @endphp
        <input type="text" value="{{ $status}}" id="etat" class="form-control">
        @switch($status)
            @case("successful")
                <div class="form-group">
                    <div class="col-md-6">
                        <h4 class="text-center">Le paiement a été effectué avec succès</h4>
                    </div>
                </div>
            @break
            @case("pending")
            <div class="form-group">
                <div class="col-md-6">
                    <h4 class="text-center" style="margin: 10%;">Le paiement est en attente de validation, <br>
                        Veillez valider le paiement
                    </h4>
                </div>
            </div>
            <div id="message" style="width: 100%; padding:10px; font-size:14px; border:transparent; background-color:lightgreen; opacity:0.7"> Un courriel contenant vos pièces d'état civil vous a été transmis par mail à l'adresse {{$paiementDemande->email_demandeur}}  </div>
            <a id="btnRetour" href="http://localhost/sifec-30-07-2024/sifec/portail-sifec.cg/" style="margin-top:15px" class="btn btn-success">Retour au portail de SIFEC - CONGO</a>

            @break
            @case("failed")
            <div class="form-group">
                <div class="col-md-6">
                    <h4 class="text-center" style="margin: 10%;">Le paiement a échoué, <br>
                        Vous n'avez pas confirmé la transaction <br>
                    </h4>
                </div>
            </div>
            <div id="message" style="width: 100%; padding:10px; font-size:14px; border:transparent; background-color:lightgreen; opacity:0.7"> Un courriel contenant vos pièces d'état civil vous a été transmis par mail à l'adresse {{$paiementDemande->email_demandeur}}  </div>
            <a id="btnRetour" href="http://localhost/sifec-30-07-2024/sifec/portail-sifec.cg/" style="margin-top:15px" class="btn btn-success">Retour au portail de SIFEC - CONGO</a>

            @break
            @default
        @endswitch
    </div>
</div>



  </body>
  <script>

    $(document).ready(function(){


        $("#ok").hide();
        //timer de controle du statut de paiement

        setInterval(myTimer, 20000);
    });

    function myTimer() {

        //appel de la vérification
        // alert("Vérification statut_paiement");
        //Appel du service demandeActe

        var url = 'http://172.16.43.146/sifec-30-07-2024/sifec/public/api/v1/statutPaiementMomo';
        var transid = $("#transid").val();
        var data = {trans_id:transid};
        var codeDemande = $('#codeDemande').val();
        var statutDemande = "";
        var url2 = "http://172.16.43.146/sifec-30-07-2024/sifec/public/api/v1/confirmationPaiementMtn";

        //Traitement de la réponse
        // var httpRequest =  $.get(connexion, transid);
        var httpRequest =  $.post(url,data);

        //Retour en cas de succès0
        httpRequest.done(function(response){
            console.log(response);


            if(response == "successful"){
                statutDemande = "Demande payée";
                url2 += "?codedemande="+codeDemande+"&statutdemande="+statutDemande+"&transid="+transid;
                window.location = url2;
            }
            if(response == "failed"){
                statutDemande = "Demande non payée";
                url2 += "?codedemande="+codeDemande+"&statutdemande="+statutDemande+"&transid="+transid;
                window.location = url2;

            }
        });

    }


       </script>
</html>
