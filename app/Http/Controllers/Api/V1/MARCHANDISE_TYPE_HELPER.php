<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\MarchandiseType;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

class MARCHANDISE_TYPE_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function MarchandiseType_rules(): array
    {
        return [
            'name' => 'required',
            'image' => 'required',
        ];
    }

    static function MarchandiseType_messages(): array
    {
        return [
            'name.required' => 'Veuillez precisez le nom !',
            'image.required' => 'Veuillez choisir une image qui illustre ce type de Marchandise',
        ];
    }

    static function MarchandiseType_Validator($formDatas)
    {
        $rules = self::MarchandiseType_rules();
        $messages = self::MarchandiseType_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    function _retrieveMarchandiseType($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return self::sendError('Ce type de Marchandise n\'existe pas!', 404);
        };

        return self::sendResponse($type, "Type de Marchandise récupére avec succès!");
    }

    static function marchandiseTypes()
    {
        #RECUPERATION DE TOUT LES TYPES DE Marchandise
        $types = MarchandiseType::with(['marchandises'])->orderBy('id', 'desc')->get();
        return self::sendResponse($types, 'Liste des types de Marchandise récupérés avec succès!!');
    }


    static function createMarchandiseType($request)
    {
        $formData = $request->all();

        ##GESTION DE L'IMAGE
        $img = $request->file('image');
        $img_name = $img->getClientOriginalName();
        $request->file('image')->move("MarchandiseTypeImages", $img_name);

        //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE **CANDIDATS**
        $formData["image"] = asset("MarchandiseTypeImages/" . $img_name);

        ##ENREGISTREMENT DU TYPE DE Marchandise DANS LA DB
        $type = MarchandiseType::create($formData); #ENREGISTREMENT DU TYPE DE Marchandise DANS LA DB
        $type->image = $formData["image"];
        $type->save;

        return self::sendResponse($type, 'Type de Marchandise ajouté avec succès!!');
    }

    static function deleteMarchandiseType($id)
    {
        $type = MarchandiseType::find($id);
        if (!$type) {
            return self::sendError('Ce Type Marchandise n\'existe pas!', 404);
        };

        $type->delete(); #SUPPRESSION DU TYPE DE MOYEN DE Marchandise;
        return self::sendResponse($type, "Ce Type de Marchandise a été supprimé avec succès!!");
    }

    static function updateMarchandiseype($request, $id)
    {
        $formData = $request->all();
        $type = MarchandiseType::find($id);

        if (!$type) { #QUAND **$type** RETOURNE **FALSE**
            return self::sendError('Ce Type de moyen de Marchandise n\'existe pas!', 404);
        };

        ###GESTION DE L'IMAGE SI ELLE EXISTAIT
        if ($request->file('image')) {
            ##GESTION DE L'IMAGE
            $img = $request->file('image');
            $img_name = $img->getClientOriginalName();
            $request->file('image')->move("MarchandiseTypeImages", $img_name);

            $formData["image"] = asset("MarchandiseTypeImages/" . $img_name);
        }

        $type->update($formData);
        return self::sendResponse($type, "Type de Marchandise modifié avec succès!!");
    }

    static function searchMarchandiseType($request)
    {
        $search = $request['search'];
        $result = collect(MarchandiseType::with('Marchandises')->get())->filter(function ($type) use ($search) {
            return Str::contains(strtolower($type['name']), strtolower($search));
        })->all();

        return self::sendResponse($result, 'Résultat de la recherche!');
    }
}
