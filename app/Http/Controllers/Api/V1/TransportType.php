<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class TransportType extends TRANSPORT_TYPE_HELPER
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

    #RECUPERATION DE TOUT LES TYPES DE MOYENS DE TRANSPORT
    public function transportTypes(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->types(); #RETOURNE TOUT LES TYPES MOYENS DE TRANSPORTS
    }

    #RECUPERATION D'UN TYPE DE MOYENS DE TRANSPORT
    public function Retrieve(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_retrieveTransportType($id);
    }

    #CREATION D'UN MOYENS DE TRANSPORT
    public function Create(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR TRANSPORT_TYPE_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        #VALIDATION DES DATAs DEPUIS LA CLASS TRANSPORT_TYPE_HELPER
        $validator = $this->TransportType_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER HERITEE PAR TRANSPORT_TYPE_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **createTransport** DE LA CLASS BASE_HELPER HERITEE PAR TRANSPORT_TYPE_HELPER
        return $this->createTransportType($request);
    }

    #MODIFICATION D'UN TYPE DE MOYENS DE TRANSPORT
    public function Update(Request $request, $id)
    {

        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->updateTransporType($request, $id);
    }

    #SUPPRESSION D'UN TYPE DE MOYENS DE TRANSPORT
    public function Delete(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        $type = $this->findType($id); #RETOURNE **FALSE** QUAND LE TYPE DE MOYEN DE TRANSPORT N'EXISTE PAS & **$type** QUAND CE DERNIER EXISTE;
        return $this->deleteTransportType($type);
    }

    #RECHERCHE D'UN TYPE MOYENS DE TRANSPORT
    public function Search(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->searchTransportType($request);
    }
}
