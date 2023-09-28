<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Facture Client</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row" style="background-color: #9F9A0F;">
            <div class="col-md-12">
                <p class="text-light text-center mt-2"> <strong>FACTURE DE TRANSACTION SUR AGBANDE</strong></p>
            </div>
        </div>
        <br>
        <div class="row mt-3">
            <div class="col-md-12">
                <h1 class="text-center">Reference : <strong style="background-color: #000;padding:10px;color:#fff"> {{$reference}} </strong> </h1>
                <br>
                <table class="table table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Transporteur</th>
                            <th scope="col">Entreprise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="">
                                    <strong>Nom :</strong>{{$transporteur->firstname}} <br>
                                    <strong>Prénom :</strong>{{$transporteur->lastname}} <br>
                                    <strong>Téléphone :</strong>{{$transporteur->phone}}
                                </div>
                            </td>
                            <td>
                                <div class="">
                                    <strong>Company :</strong>{{env("APP_NAME")}}<br>
                                    <strong>N° IFU :</strong>{{env("AGBADE_IFU")}} <br>
                                    <strong>Téléphone:</strong>{{env("AGBADE_PHONE")}}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <br><br><br>
        <div class="row">
            <div class="col-12">
                <h1 class="" style="font-style: italic;text-align:center">Détail de la Facture</h1>
                <table class="table table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Fret</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Transport</th>
                            <th scope="col">Commission</th>
                            <th scope="col">Date de validation de livraison</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{$fret->id}}</td>
                            <td class="text-center">{{$fret->price}}</td>
                            <td class="text-center">{{$fret->transport->name}}</td>
                            <td class="text-danger text-center">{{TRANSACTION_COMMISSION($fret->price)}}</td>
                            <td class="text-center">{{$fret->updated_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <img src="https://res.cloudinary.com/duk6hzmju/image/upload/v1693321022/logo_vpxoml.png" alt="" srcset="">
            </div>
        </div>
        <br><br>
        <div class="row bottom-fixed" style="background-color: #9F9A0F;">
            <div class="col-md-12">
                <p class="text-light text-center mt-2">© Copyright - <?php echo date("Y"); ?></p>
            </div>
        </div>
    </div>

    <!-- Saut de page -->
    <!-- <div style="page-break-after: always;"></div> -->
</body>

</html>