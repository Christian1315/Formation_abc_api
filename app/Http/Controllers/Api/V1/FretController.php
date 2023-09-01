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
            "Delete",
            "AffectToTransport"
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

        return $this->createFret($request);
    }

    #MODIFICATION D'UN FRET
    public function Update(Request $request, $id)
    {

        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };


        return $this->updateFret($request, $id);
    }

    #SUPPRESION D'UN FRET
    public function Delete(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->deleteFret($id);
    }

     #AFFECTATION D'UN FRET A UN TRANSPORT
     public function AffectToTransport(Request $request)
     {
         #VERIFICATION DE LA METHOD
         if ($this->methodValidation($request->method(), "POST") == False) {
             #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
             return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
         };
         return $this->_affectToTransport($request);
     }
 }


