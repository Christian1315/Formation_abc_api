<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Type;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

class TRANSPORT_TYPE_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function transportType_rules(): array
    {
        return [
            'name' => 'required',
            'image' => 'required',
        ];
    }

    static function transportType_messages(): array
    {
        return [
            'name.required' => 'Veuillez precisez le type!',
            'image.required' => 'Veuillez choisir une image qui illustre ce type de moyen de transport',
        ];
    }

    static function TransportType_Validator($formDatas)
    {
        #
        $rules = self::transportType_rules();
        $messages = self::transportType_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    function _retrieveTransportType($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return self::sendError('Ce type de moyen de transport n\'existe pas!', 404);
        };

        return self::sendResponse($type, "Type de transport récupére avec succès!");
    }

    static function types()
    {
        #RECUPERATION DE TOUT LES TYPES DE MOYENS DE TRANSPORTSR
        $types = Type::with('transports')->orderBy('id', 'desc')->get();

        return self::sendResponse($types, 'Listes des types moyens de transport récupérés avec succès!!');
    }


    static function createTransportType($request)
    {
        $formData = $request->all();

        ##GESTION DE L'IMAGE
        $img = $request->file('image');
        $img_name = $img->getClientOriginalName();
        $request->file('image')->move("transportTypeImages", $img_name);

        //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE **CANDIDATS**
        $formData["image"] = asset("transportTypeImages/" . $img_name);

        ##ENREGISTREMENT DU TYPE DE TRANSPORT DANS LA DB
        $type = Type::create($formData); #ENREGISTREMENT DU TYPE DE TRANSPORT DANS LA DB
        $type->image = $formData["image"];
        $type->save;

        return self::sendResponse($type, 'Type de Moyen de transport ajouté avec succès!!');
    }

    static function deleteTransportType($type)
    {
        if (!$type) { #QUAND **$type** RETOURNE **FALSE**
            return self::sendError('Ce Type moyen de transport n\'existe pas!', 404);
        };

        $type->delete(); #SUPPRESSION DU TYPE DE MOYEN DE TRANSPORT;
        return self::sendResponse($type, "Ce Type de moyen de transport a été supprimé avec succès!!");
    }

    static function updateTransporType($request, $id)
    {
        $formData = $request->all();
        $type = Type::find($id);

        if (!$type) { #QUAND **$type** RETOURNE **FALSE**
            return self::sendError('Ce Type de moyen de transport n\'existe pas!', 404);
        };

        ###GESTION DE L'IMAGE SI ELLE EXISTAIT
        if ($request->file('image')) {
            ##GESTION DE L'IMAGE
            $img = $request->file('image');
            $img_name = $img->getClientOriginalName();
            $request->file('image')->move("transportTypeImages", $img_name);

            $formData["image"] = asset("transportTypeImages/" . $img_name);
        }

        $type->update($formData);
        return self::sendResponse($type, "Type de Moyen de transport modifié avec succès!!");
    }

    static function searchTransportType($request)
    {
        $search = $request['search'];
        $result = collect(Type::with('transports')->get())->filter(function ($type) use ($search) {
            return Str::contains(strtolower($type['name']), strtolower($search));
        })->all();

        return self::sendResponse($result, 'Résultat de la recherche!');
    }
}
