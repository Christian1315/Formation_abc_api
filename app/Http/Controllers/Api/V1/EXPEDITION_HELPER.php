<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Expedition;
use App\Models\Frets;
use Illuminate\Support\Facades\Validator;

class EXPEDITION_HELPER extends BASE_HELPER
{
    ##======== EXPEDITION VALIDATION =======##
    static function expedition_rules(): array
    {
        return [
            // 'fret' => 'required|integer',
            // 'transport' => 'required|integer',
            // "duration" => "required|date",
        ];
    }

    static function expedition_messages(): array
    {
        return [
        ];
    }

    static function Expedition_Validator($formDatas)
    {
        $rules = self::expedition_rules();
        $messages = self::expedition_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function retrieveExpedition($id)
    {
        $reservation = Expedition::with(['owner', "fret"])->find($id);
        if (!$reservation) {
            return self::sendError('Cette reservation n\'existe pas!', 404);
        };
        return self::sendResponse($reservation, "Reservation récupérée avec succès");
    }

    static function createExpedition($request)
    {
        $formData = $request->all();

        $fret = Frets::find($formData['fret']);
        if (!$fret) {
            return self::sendError('Ce fret n\' existe pas dans!', 404);
        }

        ##ENREGISTREMENT DE LA Expedition DANS LA DB
        $reservation = Expedition::create($formData); #ENREGISTREMENT DU USER DANS LA DB
        $reservation->owner = request()->user()->id;
        $reservation->save();

        ###___NOTONS QUE CE FRET EST RESERVEE
        $fret->reserved = 1;
        $fret->save();

        return self::sendResponse($reservation, 'Reservation ajoutée avec succès!!');
    }

    static function expeditions()
    {
        $reservations = Expedition::with(['owner', "fret"])->orderBy('id', 'desc')->get();
        return self::sendResponse($reservations, 'Liste des reservations récupérée avec succès!!');
    }

    static function deleteExpedition($request, $id)
    {
        $reservation = Expedition::find($id);

        if (!$reservation) {
            return self::sendError("Cette Expedition n'existe pas!", 505);
        }

        $reservation->delete();
        return self::sendResponse($reservation, 'Reservation supprimée avec succès!!');
    }
}
