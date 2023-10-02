<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Frets;
use App\Models\Marchandise;
use App\Models\MarchandiseType;
use Illuminate\Support\Facades\Validator;


class MARCHANDISE_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function Marchandise_rules(): array
    {
        return [
            'name' => 'required',
            'image' => 'required',
        ];
    }

    static function Marchandise_messages(): array
    {
        return [
            'name.required' => 'Veuillez precisez le nom !',
            'image.required' => 'Veuillez choisir une image qui illustre ce type de Marchandise',
        ];
    }

    static function Marchandise_Validator($formDatas)
    {
        $rules = self::Marchandise_rules();
        $messages = self::Marchandise_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    function _retrieveMarchandise($id)
    {
        $marchandise = Marchandise::with(['fret', "type"])->find($id);
        if (!$marchandise) {
            return self::sendError('Cette Marchandise n\'existe pas!', 404);
        };
        return self::sendResponse($marchandise, "Marchandise récupérée avec succès!");
    }

    static function marchandises()
    {
        #RECUPERATION DE TOUT LES TYPES DE Marchandise
        $types = Marchandise::with(['fret', "type"])->orderBy('id', 'desc')->get();
        return self::sendResponse($types, 'Liste des Marchandise récupérés avec succès!!');
    }

    static function deleteMarchandise($id)
    {
        $type = Marchandise::find($id);
        if (!$type) {
            return self::sendError('Cette Marchandise n\'existe pas!', 404);
        };

        $type->delete(); #SUPPRESSION DU TYPE DE MOYEN DE Marchandise;
        return self::sendResponse($type, "Cette Marchandise a été supprimée avec succès!!");
    }

    static function updateMarchandise($request, $id)
    {
        $formData = $request->all();
        $marchandise = Marchandise::find($id);

        if (!$marchandise) {
            return self::sendError('Cette Marchandise n\'existe pas!', 404);
        };

        ###GESTION DU FRET DE MARCHANDISE SI ELLE EXISTAIT
        if ($request->get('type')) {
            if (!is_numeric($request->get('type'))) {
                return self::sendError("Le type est un entier", 505);
            }
            $type = MarchandiseType::find($request->get('type'));
            if (!$type) {
                return self::sendError('Ce type de marchandise n\'existe pas!', 404);
            };
        }

        ###GESTION DU TYPE DE MARCHANDISE SI ELLE EXISTAIT
        if ($request->get('fret')) {
            if (!is_numeric($request->get('fret'))) {
                return self::sendError("Le fret est un entier", 505);
            }
            $fret = Frets::find($request->get('fret'));
            if (!$fret) {
                return self::sendError('Ce fret n\'existe pas!', 404);
            };
        }

        $marchandise->update($formData);
        return self::sendResponse($marchandise, "Marchandise modifiée avec succès!!");
    }
}
