<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FretType;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

class FRET_TYPE_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function FretType_rules(): array
    {
        return [
            'name' => 'required',
            'image' => 'required',
        ];
    }

    static function FretType_messages(): array
    {
        return [
            'name.required' => 'Veuillez precisez le nom !',
            'image.required' => 'Veuillez choisir une image qui illustre ce type de Fret',
        ];
    }

    static function FretType_Validator($formDatas)
    {
        $rules = self::FretType_rules();
        $messages = self::FretType_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    function _retrieveFretType($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return self::sendError('Ce type de Fret n\'existe pas!', 404);
        };

        return self::sendResponse($type, "Type de Fret récupére avec succès!");
    }

    static function types()
    {
        #RECUPERATION DE TOUT LES TYPES DE FRETS
        $types = FretType::with(['frets'])->orderBy('id', 'desc')->get();

        return self::sendResponse($types, 'Liste des types de Fret récupérés avec succès!!');
    }


    static function createFretType($request)
    {
        $formData = $request->all();

        ##GESTION DE L'IMAGE
        $img = $request->file('image');
        $img_name = $img->getClientOriginalName();
        $request->file('image')->move("fretTypeImages", $img_name);

        //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE **CANDIDATS**
        $formData["image"] = asset("fretTypeImages/" . $img_name);

        ##ENREGISTREMENT DU TYPE DE Fret DANS LA DB
        $type = FretType::create($formData); #ENREGISTREMENT DU TYPE DE Fret DANS LA DB
        $type->image = $formData["image"];
        $type->save;

        return self::sendResponse($type, 'Type de Fret ajouté avec succès!!');
    }

    static function deleteFretType($id)
    {
        $type = FretType::find($id);
        if (!$type) {
            return self::sendError('Ce Type Fret n\'existe pas!', 404);
        };

        $type->delete(); #SUPPRESSION DU TYPE DE MOYEN DE Fret;
        return self::sendResponse($type, "Ce Type de Fret a été supprimé avec succès!!");
    }

    static function updateFretype($request, $id)
    {
        $formData = $request->all();
        $type = FretType::find($id);

        if (!$type) { #QUAND **$type** RETOURNE **FALSE**
            return self::sendError('Ce Type de moyen de Fret n\'existe pas!', 404);
        };

        ###GESTION DE L'IMAGE SI ELLE EXISTAIT
        if ($request->file('image')) {
            ##GESTION DE L'IMAGE
            $img = $request->file('image');
            $img_name = $img->getClientOriginalName();
            $request->file('image')->move("fretTypeImages", $img_name);

            $formData["image"] = asset("fretTypeImages/" . $img_name);
        }

        $type->update($formData);
        return self::sendResponse($type, "Type de Fret modifié avec succès!!");
    }

    static function searchFretType($request)
    {
        $search = $request['search'];
        $result = collect(FretType::with('frets')->get())->filter(function ($type) use ($search) {
            return Str::contains(strtolower($type['name']), strtolower($search));
        })->all();

        return self::sendResponse($result, 'Résultat de la recherche!');
    }
}
