<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class Notifications extends NOTIFICATIONS_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    #CREATION D'UNE NOTIFICATION
    public function Create(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR NOTIFICATION_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS NOTIFICATION_HELPER
        $validator = $this->Notification_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER HERITEE PAR NOTIFICATION_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **createNotification** DE LA CLASS BASE_HELPER HERITEE PAR NOTIFICATION_HELPER
        return $this->createNotification($request->all());
    }

    #RECUPERATION DE TOUTES LES NOTIFICATIONS
    public function _AllNotifications(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->allNotifications();
    }

    #RECUPERATION DE TOUTES LES NOTIFICATIONS RECU PAR UN USER
    public function NotificationsReceived(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR FRET_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->MyNotificationsReceived($id); #RETOURNE TOUTES LES NOTIFICATIONS D'UN USER
    }

    #RECUPERATION D'UNE NOTIFICATION VIA SON ID
    public function Retrieve(Request $request,$id) {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(),"GET")==False){ 
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode ".$request->method()." n'est pas supportée pour cette requete!!",404);
        };

        $NOTIFICATION = $this->findNotification($id);#RETOURNE **FALSE** QUAND LE TRANSPORT N'EXISTE PAS & **$notification** QUAND CETTE DERNIERE N\'EXISTE;
        
        return $this->retrieveNotification($NOTIFICATION);
    }

    #MODIFICATION D'UNE NOTIFICATION
    public function Update(Request $request,$id) {
        
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(),"PATCH")==False){ 
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode ".$request->method()." n'est pas supportée pour cette requete!!",404);
        };

        $NOTIFICATION = $this->findNotification($id);#RETOURNE **FALSE** QUAND LA NOTIFICATION N'EXISTE PAS & **$NOTIFICATION** QUAND CETTE DERNIERE EXISTE;

        if(!$NOTIFICATION){#QUAND **$transport** RETOURNE **FALSE**
            return self::sendError('Cette notification n\'existe pas!',404);
        };

        return $this->updateNotification($NOTIFICATION,$request->all());
    }

    #SUPPRESSION D'UNE NOTIFICATION
    public function Delete(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        $NOTIFICATION = $this->findNotification($id); #RETOURNE **FALSE** QUAND LA NOTIFICATION N'EXISTE PAS & **$NOTIFICATION** QUAND CETTE DERNIERE NOTIFICATION EXISTE;

        return $this->deleteNotification($NOTIFICATION);
    }
}
