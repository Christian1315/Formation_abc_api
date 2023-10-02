<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Frets;
use App\Models\FretStatus;
use App\Models\FretType;
use App\Models\Marchandise;
use App\Models\MarchandiseType;
use App\Models\Transport;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;

class FRET_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function fret_rules(): array
    {
        return [
            'depart_date' => 'required|date',
            'arrived_date' => 'required|date',
            'marchandises' => 'required',
            // 'weight' => 'required|integer',
            // 'length' => 'required|integer',
            'transport_type' => 'required|integer',
            'transport_num' => 'required|integer',
            'price' => 'required|integer',
            'comment' => 'required'
        ];
    }

    static function fret_messages(): array
    {
        return [
            // 'user_id.required' => 'Veuillez precisez l\'id du transporteur!',
            // 'user_id.integer' => 'Ce Champ doit etre un entier!',
            // 'name.required' => 'Veuillez precisez le nom du fret',
            // 'nature.required' => 'Veuillez precisez la nature du fret!',
            // 'vol_or_quant.required' => 'Veuillez précisez le volume ou la quantité du fret!',
            // 'charg_date.required' => 'Veuillez précisez la date du chargement!',
            // 'charg_date.date' => 'Ce Champ doit etre une date!',
            // 'charg_location.required' => 'Veuillez précisez le lieu du chargement!',
            // 'charg_destination.required' => 'Veuillez précisez la destination du fret!',
            // 'axles_num.required' => 'Veuillez précisez le nombre d’essieux du fret!',
            // 'axles_num.integer' => 'Ce Champ doit etre un entier',
            // 'fret_img.required' => 'Veuillez choisir une image du fret!',
        ];
    }

    static function Fret_Validator($formDatas)
    {
        #
        $rules = self::fret_rules();
        $messages = self::fret_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createFret($request)
    {
        $formData = $request->all();
        $fretData = [
            "depart_date" => $formData["depart_date"],
            "arrived_date" => $formData["arrived_date"],
            "depart_map" => $formData["depart_map"],
            "arrived_map" => $formData["arrived_map"],
            "transport_type" => $formData["transport_type"],
            "transport_num" => $formData["transport_num"],
            "price" => $formData["price"],
            "comment" => $formData["comment"],
        ];

        $marchandisesData = $formData["marchandises"];
        // $marchandisesData = [
        //     [
        //         "type" => 1,
        //         "weight" => 3000,
        //         "length" => 100,
        //     ],
        //     [
        //         "type" => 2,
        //         "weight" => 5000,
        //         "length" => 200,
        //     ]
        // ];

        ###TRAITEMENT DU TYPE DE MARCHANDISE
        foreach ($marchandisesData as $marchandise) {
            $type = MarchandiseType::find($marchandise["type"]);
            if (!$type) {
                return self::sendError("Ce type de marchandise d'ID: " . $marchandise["type"] . " n'existe pas!", 505);
            }
        }

        #####CREATION DU FRET
        $fret = Frets::create($fretData);
        $fret->owner = request()->user()->id;
        $fret->status = 1;
        $fret->save();

        #####CREATION DE LA MARCHANDISE
        foreach ($marchandisesData as $marchandise) {
            $mar = Marchandise::create($marchandise);
            $mar->fret = $fret->id;
            $mar->save();
        }

        return self::sendResponse($fret, 'Fret ajouté avec succès!!');
    }

    static function retrieve($id)
    {
        // $user = request()->user();
        // if (IsUserAnAdmin($user->id)) { ##SI LE USER EST UN ADMIN
        //     $frets = Frets::with(['owner', "status", "transport_type", "marchandises", "transport"])->find($id);
        //     if (!$frets) {
        //         return self::sendError('Ce Fret n\'existe pas!', 404);
        //     };
        //     return self::sendResponse($frets, "Fret récupéré avec succès");
        // }

        ### S'il est un simple user
        $fret = Frets::with(['owner', "status", "transport_type", "marchandises", "transport"])->find($id);
        #QUAND L'ID NE CORRESPOND A AUCUN FRET
        if (!$fret) {
            return self::sendError('Ce Fret n\'existe pas!', 404);
        }
        return self::sendResponse($fret, "Fret récupéré avec succès");
    }

    static function frets()
    {
        // $user = request()->user();

        // if (IsUserAnAdmin($user->id)) { ##SI LE USER EST UN ADMIN
        //     $frets = Frets::with(['owner', "status", "transport_type", "marchandises", "transport"])->orderBy("id", "desc")->get();
        //     return self::sendResponse($frets, "Liste des Frets récupéré avec succès");
        // }

        #QUAND C'EST UN SIMPLE USER
        $frets = Frets::with(['owner', "status", "transport_type", "transport", "marchandises"])->orderBy('id', 'desc')->get();

        return self::sendResponse($frets, 'Liste des frets récupérés avec succès!!');
    }

    static function updateFret($request, $id)
    {
        $formData = $request->all();
        // $user = request()->user();

        $fret = Frets::where(["id" => $id])->get();
        if ($fret->count() == 0) {
            return self::sendError('Ce Fret n\'existe pas!', 404);
        };

        $fret = $fret[0];

        if ($request->get("transport_type")) {
            ###TRAITEMENT DU MOYEN DE TRANSPORT
            $transport_type = Type::find($formData["transport_type"]);
            if (!$transport_type) {
                return self::sendError("Ce type de transport n'existe pas", 404);
            }
        }

        if ($request->get("type")) {
            ###TRAITEMENT DU TYPE DE MARCHANDISE
            $type = MarchandiseType::find($formData["type"]);
            if (!$type) {
                return self::sendError("Ce type de Fret n'existe pas", 404);
            }
            $fret->type = $formData["type"];
        }

        if ($request->get("status")) {
            ###TRAITEMENT DU STATUS
            $status = FretStatus::find($formData["status"]);
            if (!$status) {
                return self::sendError("Ce status de Fret n'existe pas", 404);
            }

            ##s'il vaut mettre le status du fret sur **Livré**
            if ($formData["status"] == 5) {
                if (!$fret->affected) { ##si le fret n'est pas encore affecté, on ne peut pas le mettre sur le status *Livré*
                    return self::sendError("Désolé! Ce fret n'a été affecté à aucun transporteur. Vous ne pouvez donc pas le noter comme un fret livré!", 505);
                }
            }
            $fret->status = $formData["status"];
        }
        $fret->save();

        $fret->update($formData);
        return self::sendResponse($fret, "Fret modifié avec succès!!");
    }

    static function deleteFret($id)
    {
        $user = request()->user();
        $fret = Frets::where(["owner" => $user->id, "id" => $id])->get();
        if ($fret->count() == 0) {
            return self::sendError('Ce Fret n\'existe pas!', 404);
        };

        $fret = $fret[0];
        $fret->delete();
        return  self::sendResponse($fret, "Fret supprimé avec succès!");
    }

    static function _affectToTransport($request)
    {
        $user = request()->user();
        $formData = $request->all();
        if (!$request->get("fret_id")) {
            return self::sendError("Le champ **fret_id** est réquis!", 404);
        }
        if (!$request->get("transport_id")) {
            return self::sendError("Le champ **transport_id** est réquis!", 404);
        }

        $fret = Frets::find($formData["fret_id"]);
        $transport = Transport::find($formData["transport_id"]);

        ###___VERIFIONS SI CE FRET LUI APPARTIENT
        if ($fret->owner != $user->id) {
            return self::sendError("Ce fret ne vous appartient pas!", 505);
        }

        ##________ TRAITEMENT DU MOYEN DE TRANSPORT _______

        #*** VERIFIONS SI CE TRANSPORT EXISTE
        if (!$transport) {
            return self::sendError("Ce Transport n'existe pas", 505);
        }

        #*** VERIFIONS SI CE TRANSPORT EST VALIDE
        if ($transport->status != 2) {
            return self::sendError("Ce transport n'est pas encore validé! Veuillez le faire valider!", 505);
        }

        ###VERIFIONS SI CE FRET EXISTE
        if (!$fret) {
            return self::sendError("Ce Fret n'existe pas", 505);
        }

        ###VERIFIONS SI CE FRET A DEJA ETE AFFECTE A UN QUELCONQUE TRANSPORT
        if ($fret->affected == true) {
            return self::sendError("Ce Fret a déjà été affecté a un transporteur", 505);
        }

        ###______
        $fret->transport_id = $formData["transport_id"];
        $fret->affected = 1;
        $fret->save();
        return self::sendResponse($formData, "Affectation effectuée avecx succès");
    }
}
