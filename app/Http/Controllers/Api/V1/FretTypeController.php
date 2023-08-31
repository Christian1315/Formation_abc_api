<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class FretTypeController extends FRET_TYPE_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
        $this->middleware("CheckIfUserIsAdmin")->only([
            "Create",
            "Update",
            "Delete"
        ]);
    }

    #RECUPERATION DE TOUT LES TYPES DE MOYENS DE Fret
    public function FretTypes(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->types(); #RETOURNE TOUT LES TYPES MOYENS DE FretS
    }

    #RECUPERATION D'UN TYPE DE MOYENS DE Fret
    public function Retrieve(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_retrieveFretType($id);
    }

    #CREATION D'UN MOYENS DE Fret
    public function Create(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Fret_TYPE_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        #VALIDATION DES DATAs DEPUIS LA CLASS Fret_TYPE_HELPER
        $validator = $this->FretType_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER HERITEE PAR Fret_TYPE_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **createFret** DE LA CLASS BASE_HELPER HERITEE PAR Fret_TYPE_HELPER
        return $this->createFretType($request);
    }

    #MODIFICATION D'UN TYPE DE MOYENS DE Fret
    public function Update(Request $request, $id)
    {

        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->updateFretype($request, $id);
    }

    #SUPPRESSION D'UN TYPE DE MOYENS DE Fret
    public function Delete(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->deleteFretType($id);
    }

    #RECHERCHE D'UN TYPE MOYENS DE Fret
    public function Search(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->searchFretType($request);
    }
}
