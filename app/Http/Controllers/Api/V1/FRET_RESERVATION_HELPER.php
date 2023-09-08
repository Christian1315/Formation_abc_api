<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Frets;
use App\Models\Reservation;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;

class FRET_RESERVATION_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function reservation_rules(): array
    {
        return [
            'fret' => 'required|integer',
            'price' => 'required|integer',
            "charg_date" => "required|date",
            'info' => 'required',
        ];
    }

    static function reservation_messages(): array
    {
        return [
            'fret.required' => 'Veuillez préciser le fret en question',
            'price.required' => 'Veuillez préciser votre prix d\'estimation',
            'charg_date.required' => 'Veuillez préciser la date de chargement',
            'info.required' => 'Veuillez laisser un message au proprietaire du fret',

            'fret.integer' => 'Ce champ est un entier',
            'price.integer' => 'Ce champ est un entier',
            'charg_date.date' => 'Ce champ est une date',
        ];
    }

    static function Reservation_Validator($formDatas)
    {
        $rules = self::reservation_rules();
        $messages = self::reservation_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function retrieveReservation($id)
    {
        $reservation = Reservation::with(['owner', "fret"])->find($id);
        if (!$reservation) {
            return self::sendError('Cette reservation n\'existe pas!', 404);
        };
        return self::sendResponse($reservation, "Reservation récupérée avec succès");
    }

    static function createReservation($request)
    {
        $formData = $request->all();

        $fret = Frets::find($formData['fret']);
        if (!$fret) {
            return self::sendError('Ce fret n\' existe pas dans!', 404);
        }

        ##ENREGISTREMENT DE LA RESERVATION DANS LA DB
        $reservation = Reservation::create($formData); #ENREGISTREMENT DU USER DANS LA DB
        $reservation->owner = request()->user()->id;
        $reservation->save();

        ###___NOTONS QUE CE FRET EST RESERVEE
        $fret->reserved = 1;
        $fret->save();

        return self::sendResponse($reservation, 'Reservation ajoutée avec succès!!');
    }

    static function reservations()
    {
        $reservations = Reservation::with(['owner', "fret"])->orderBy('id', 'desc')->get();
        return self::sendResponse($reservations, 'Liste des reservations récupérée avec succès!!');
    }

    static function deleteReservation($request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return self::sendError("Cette reservation n'existe pas!", 505);
        }

        $reservation->delete();
        return self::sendResponse($reservation, 'Reservation supprimée avec succès!!');
    }
}
