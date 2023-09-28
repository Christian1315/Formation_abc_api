<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class ReservationController extends FRET_RESERVATION_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
        $this->middleware("CheckIfUserIsAdminOrTransporter")->only([
            "Create",
            "Retrieve",
            "Delete",
            "AllReservations"
        ]);
    }

    #RECUPERATION DE TOUT LES MOYENS DE RESERVATION
    public function AllReservations(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR RESERVATION_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RETOURNE TOUT LES MOYENS DE RESERVATIONS
        return $this->reservations();
    }

    #RECUPERATION S DE RESERVATION
    public function Retrieve(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR RESERVATION_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->retrieveReservation($id);
    }

    #CREATION  DE RESERVATION
    public function Create(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR RESERVATION_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        #VALIDATION DES DATAs DEPUIS LA CLASS RESERVATION_HELPER
        $validator = $this->RESERVATION_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER HERITEE PAR RESERVATION_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **createRESERVATION** DE LA CLASS BASE_HELPER HERITEE PAR RESERVATION_HELPER
        return $this->createReservation($request);
    }

    #SUPPRESSION  DE RESERVATION
    public function Delete(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR RESERVATION_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->deleteReservation($request, $id);
    }
}
