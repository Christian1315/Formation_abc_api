<?php

namespace App\Http\Controllers\Api\V1;

// require 'vendor/autoload.php';
use \Mailjet\Resources;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Validator;

class NOTIFICATIONS_HELPER extends BASE_HELPER
{
    ##======== NOTIFICATION VALIDATION =======##
    static function notification_rules(): array
    {
        return [
            'receiver_id' => 'required|integer',
            'message' => 'required',
        ];
    }

    static function notification_messages(): array
    {
        return [
            'receiver_id.required' => 'Veuillez precisez l\'id du destinataire!',
            'receiver_id.integer' => 'Ce champ requiert un entier',
        ];
    }

    static function Notification_Validator($formDatas)
    {
        $rules = self::notification_rules();
        $messages = self::notification_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    #RECUPERATION DE TOUTES LES NOTIFICATIONS
    static function allNotifications()
    {
        $notifications  = Notification::with(['sender', 'receiver'])->orderBy('id', 'desc')->get();
        return self::sendResponse($notifications, 'Listes de toutes les notifications');
    }

    #RECHERCHER UNE NOTIFICATION
    static function findNotification($id)
    {
        $NOTIFICATION = Notification::with(['receiver', 'sender'])->find($id);
        #QUAND L'ID NE CORRESPOND A AUCUNE NOTIFICATION
        if (!$NOTIFICATION) {
            return false;
        }
        #AUTREMENT
        return $NOTIFICATION;
    }


    static function retrieveNotification($notification)
    {
        if (!$notification) { #QUAND **$notification** RETOURNE **FALSE**
            return self::sendError('Cette notification n\'existe pas!', 404);
        };

        return self::sendResponse($notification, 'Notification récupéré avec succès!!');
    }

    #CREATION D'UNE NOTIFIFCATION
    static function createNotification($formData)
    {
        $user = request()->user();
        $formData['sender_id'] = $user->id;

        #QUAND L'ID NE CORRESPOND A AUCUN EXPEDITEUR
        if (!$user) {
            return self::sendError('Ce sender_id ne corresponds à aucun EXPEDITEUR', 404);
        }

        $receiver = User::find($formData['receiver_id']);

        if (!$receiver) {
            return self::sendError('Ce receiver_id ne corresponds à aucun utilisateur', 404);
        }

        $notification = Notification::create($formData); #ENREGISTREMENT DE LA NOTIFICATION DANS LA DB

        ###ENVOIE DE LA NOTIFICATION AU DESTINATAIRE VIA MAIL
        Send_Notification(
            $receiver,
            "NOTIFICATION SUR AGBANDE",
            $formData['message'],
        );

        return self::sendResponse($notification, 'Notification envoyée avec succès!!');
    }

    #TOUTES LES NOTIFICATIONS RECU PAR UN UTILISATEUR
    static function myNotificationsReceived($receiver_id)
    {
        $receiver = User::find($receiver_id);
        #QUAND L'ID NE CORRESPOND A AUCUN DESTINATAIRE
        if (!$receiver) {
            return self::sendError("Cet ID ne corresponds à aucun destinataire!", 404);
        }

        #RECUPERATION DE TOUTES LES NOTIFICATIONS D'UN USER
        $notifications = Notification::with(['sender'])->where('receiver_id', '=', $receiver_id)->orderBy('id', 'desc')->get();

        return self::sendResponse($notifications, 'Listes des notifications récupérés avec succès!!');
    }

    static function updateNotification($notification, $formData)
    {
        $notification->update($formData);
        $resul = Notification::find($notification->id);
        return self::sendResponse($resul, "Notification modifiée avec succès!!");
    }

    #SUPPRESSION D'UNE NOTIFICATION
    static function deleteNotification($notification)
    {
        if (!$notification) { #QUAND **$notification** RETOURNE **FALSE**
            return self::sendError('Cette notification n\'existe pas!', 404);
        };

        $notification->delete(); #SUPPRESSION DE LA NOTIFICATION;
        return self::sendResponse($notification, "Cette notification a été supprimée avec succès!!");
    }
}
