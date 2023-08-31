<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class FretController extends FRET_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
        $this->middleware("CheckIfUserIsAdminOrExpeditor")->only([
            "Create",
            "Update",
            "Delete"
        ]);
    }

    #RECUPERATION DE TOUT LES FRETs
    public function _Frets(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->frets(); #RETOURNE TOUT LES FRET
    }

    #RECUPERATION D'UN FRET
    public function _Retrieve(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->retrieve($id);
    }

    #CREATION D'UN FRET
    public function Create(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        #VALIDATION DES DATAs DEPUIS LA CLASS TRANSPORT_HELPER
        $validator = $this->Fret_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **createTransport** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
        return $this->createFret($request->all());
    }

    #MODIFICATION D'UN FRET
    public function Update(Request $request, $id)
    {

        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "PATCH") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        $fret = $this->findFret($id); #RETOURNE **FALSE** QUAND LE FRET N'EXISTE PAS & **$fret** QUAND CE DERNIER EXISTE;

        if (!$fret) { #QUAND **$fret** RETOURNE **FALSE**
            return self::sendError('Ce Fret n\'existe pas!', 404);
        };

        return $this->updateFret($fret, $request->all());
    }

    #SUPPRESION D'UN FRET
    public function Delete(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        $fret = $this->findFret($id); #RETOURNE **FALSE** QUAND LE FRET N'EXISTE PAS & **$fret** QUAND CE DERNIER EXISTE;

        if (!$fret) { #QUAND **$fret** RETOURNE **FALSE**
            return self::sendError('Ce fret n\'existe pas!', 404);
        };

        $fret->delete(); #SUPPRESSION DU FRET;
        return $this->sendResponse($fret, "Ce fret a été supprimé avec succès!!");
    }
}
