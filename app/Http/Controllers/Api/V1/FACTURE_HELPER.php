<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Facture;
use App\Models\Frets;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PDF;

class FACTURE_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function facture_rules(): array
    {
        return [
            'client' => 'required|integer',
        ];
    }

    static function facture_messages(): array
    {
        return [
            'client.required' => 'Veuillez precisez l\'id du client à facturer!',
            'client.integer' => 'Le champ client doit être un entier!',
        ];
    }

    static function Facture_Validator($formDatas)
    {
        #
        $rules = self::facture_rules();
        $messages = self::facture_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createFacture($clientId)
    {
        $client = User::find($clientId);
        if (!$client) {
            return self::sendError("Ce client n'existe pas!", 404);
        }

        ###___GESTION DES FACTURES
        $reference = Custom_Timestamp();
        #~~recuperation des frets livré mais non facturés
        $frets = Frets::where(["owner" => $clientId, "status" => 5, "affected" => 1, "factured" => 0])->get();

        ##si aucun fret n'est livré
        if ($frets->count() == 0) {
            return self::sendError("Ce client ne dispose pas de fret disposé à être facturer", 505);
        }

        $formData = [];
        $formData["reference"] = $reference;
        if (request()->user()) {
            $formData["facturier"] = request()->user()->id;
        }

        #~~COMMISION TOTALE
        $commisons_array = [];
        foreach ($frets as $fret) {
            $transporteur = User::find($fret->transport->owner);

            ##############============GESTION DES TRANSPORTEURS AYANT LIVRE CES FRETS============######
            $commission = TRANSACTION_COMMISSION($fret->price);
            ##_____

            $pdf = PDF::loadView('facture-transport', compact(["transporteur", "reference", "fret", "commission"]));
            $pdf->save(public_path("factures/transporteurs/" . $reference . ".pdf"));
            ###____

            $facturepdf_path = asset("factures/transporteurs/" . $reference . ".pdf");

            ##mentionner que ce fret a été facturée déjà
            $fret->factured = 1;
            $fret->save();

            ###CRETAION DE LA FACTURE DE CE TRANSPORTEUR

            $formData["client"] = $transporteur->id;
            $formData["facture"] = $facturepdf_path;
            $facture = Facture::create($formData);

            #######GESTION DE LA COMMISSION DE L'EXPEDITEUR
            $fret_commission = TRANSACTION_COMMISSION($fret->price);
            array_push($commisons_array, $fret_commission);

            try {
                Send_Notification(
                    $transporteur,
                    "FACTURATION DES TRANSACTIONS SUR ABGANDE EN TANT QUE TRANSPORTEUR",
                    "Cher Transporteur, Vous venez juste d'etre facturé.e sur AGBANDE! \n Veuillez cliquer sur le lien ci-dessous pour la télécharger: " . $facturepdf_path
                );
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        #######___________EXPEDITEURS

        $commission_totale = array_sum($commisons_array);
        ##_____

        $pdf = PDF::loadView('facture-expeditor', compact(["client", "reference", "frets", "commission_totale"]));
        $pdf->save(public_path("factures/expeditors/" . $reference . ".pdf"));
        ###____

        $facturepdf_path = asset("factures/expeditors/" . $reference . ".pdf");

        $formData["client"] = $clientId;
        $formData["facture"] = $facturepdf_path;
        $facture = Facture::create($formData);

        ####___ENVOIE DE MAIL AU CLIENT POUR LUI NOTIFIER LA FACTURE
        try {
            Send_Notification(
                $client,
                "FACTURATION DES TRANSACTIONS SUR ABGANDE EN TANT QU'EXPEDITEUR",
                "Cher expéditeur, Vous venez juste d'etre facturé.e sur AGBANDE! \n Veuillez cliquer sur le lien ci-dessous pour la télécharger: " . $facturepdf_path
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        return self::sendResponse($facture, 'Facture générée avec succès!!');
    }

    static function retrieveFacture($id)
    {
        $facture = Facture::with(["client", "facturier"])->find($id);
        if (!$facture) {
            return self::sendError("Cette facture n'est pas disponible", 404);
        }
        return self::sendResponse($facture, "Facture récupérée avec succès");
    }

    static function factures()
    {
        $factures = Facture::with(["client", "facturier"])->orderBy("id", "desc")->get();
        if ($factures->count() == 0) {
            return self::sendError("Aucune facture n'est disponible", 404);
        }
        return self::sendResponse($factures, 'Liste des factures récupérés avec succès!!');
    }

    static function updateFacture($request, $id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return self::sendError('Cette facture n\'existe pas!', 404);
        };

        if ($request->get("paid")) {
            $facture->paid = $request->get("paid");
            $facture->save();
        }
        $facture->update($request->all());
        return self::sendResponse($facture, "Facture modifiée avec succès!!");
    }

    static function deleteFacture($id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return self::sendError('Cette facture n\'existe pas!', 404);
        };
        $facture->delete();
        return  self::sendResponse($facture, "Facture supprimée avec succès!");
    }
}
