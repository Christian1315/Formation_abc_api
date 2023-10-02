<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Transport;
use App\Models\TransportStatus;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;

class TRANSPORT_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function transport_rules(): array
    {
        return [
            "name" => "required",
            'type_id' => 'required|integer',

            'fabric_year' => 'required',
            'circulation_year' => 'required',
            'assurance_expire' => 'required',
            'tech_visit_expire' => 'required',

            'gris_card' => 'required',
            'assurance_card' => 'required',

            'img1' => 'required',
            'img2' => 'required',
            'img3' => 'required',
        ];
    }

    static function transport_messages(): array
    {
        return [
            'name.required' => 'Veuillez précisez le nom du moyen de transport que vous essayez d\'ajouter',
            'type_id.required' => 'Veuillez précisez le type de moyen de transport que vous essayez d\'ajouter',
            'type_id.integer' => 'Ce champ requiert un entier',

            'fabric_year.required' => 'Veuillez precisez la date de fabrication!',
            'circulation_year.required' => 'Veuillez precisez la date de la mise en circulation!',

            'assurance_expire.required' => 'Veuillez precisez la date d\'expiration de la carte d\'assurance!',
            'tech_visit_expire.required' => 'Veuillez precisez la date d\'expiration de la visite technique!',

            'gris_card.required' => 'Veuillez envoyer une photo de la carte grise!',
            'assurance_card.required' => 'Veuillez envoyer une photo de la carte d\'assurance!',

            'img1.required' => 'Veuillez choisir la première photo du moyen de transport!',
            'img2.required' => 'Veuillez choisir la deuxième photo du moyen de transport!',
            'img3.required' => 'Veuillez choisir la troixième photo du moyen de transport!',
        ];
    }

    static function Transport_Validator($formDatas)
    {
        #
        $rules = self::transport_rules();
        $messages = self::transport_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function retrieveTransport($id)
    {
        // $user = request()->user();
        // if (IsUserAnAdmin($user->id)) { ##SI LE USER EST UN ADMIN
        //     $transport = Transport::with(['owner', 'type', "status", "frets"])->find($id);
        //     if (!$transport) {
        //         return self::sendError('Ce moyen de transport n\'existe pas!', 404);
        //     };
        //     return self::sendResponse($transport, "Moyen de transport récupéré avec succès");
        // }
        ### S'il est un simple user
        $transport = Transport::with(['owner', 'type', "status", "frets"])->where(["id" => $id])->find($id);
        if (!$transport) {
            return self::sendError('Ce moyen de transport n\'existe pas!', 404);
        };
        return self::sendResponse($transport, "Transport récupéré avec succès");
    }

    static function createTransport($request)
    {
        $formData = $request->all();

        $type = Type::find($formData['type_id']);
        if (!$type) {
            return self::sendError('Ce type de moyen de transport n\' existe pas dans la DB!', 404);
        }
        ##GESTION DES IMAGES
        $gris_card = $request->file('gris_card');
        $img_name = $gris_card->getClientOriginalName();
        $request->file('gris_card')->move("gris_card", $img_name);
        $formData["gris_card"] = asset("gris_card/" . $img_name);

        $assurance_card = $request->file('assurance_card');
        $img_name = $assurance_card->getClientOriginalName();
        $request->file('assurance_card')->move("assurance_card", $img_name);
        $formData["assurance_card"] = asset("assurance_card/" . $img_name);

        $img1 = $request->file('img1');
        $img_name = $img1->getClientOriginalName();
        $request->file('img1')->move("vehicule_images", $img_name);
        $formData["img1"] = asset("vehicule_images/" . $img_name);

        $img2 = $request->file('img2');
        $img_name = $img2->getClientOriginalName();
        $request->file('img2')->move("vehicule_images", $img_name);
        $formData["img2"] = asset("vehicule_images/" . $img_name);

        $img3 = $request->file('img3');
        $img_name = $img3->getClientOriginalName();
        $request->file('img3')->move("vehicule_images", $img_name);
        $formData["img3"] = asset("vehicule_images/" . $img_name);


        ##ENREGISTREMENT DU TRANSPORT DANS LA DB
        $transport = Transport::create($formData); #ENREGISTREMENT DU USER DANS LA DB
        $transport->owner = request()->user()->id;
        $transport->status = 1;
        $transport->save();

        return self::sendResponse($transport, 'Moyen de transport ajouté avec succès!!');
    }

    static function transports()
    {
        // $user = request()->user();
        // if (IsUserAnAdmin($user->id)) { ##SI LE USER EST UN ADMIN
        //     $transports = Transport::with(['owner', 'type', "status", "frets"])->orderBy('id', 'desc')->get();
        //     return self::sendResponse($transports, "Moyen de transports récupérés avec succès");
        // }
        // ### S'il est un simple user
        // ### il recupère seulement les transports qui lui appartiennent

        $transports = Transport::with(['owner', 'type', "status", "frets"])->orderBy('id', 'desc')->get();
        return self::sendResponse($transports, 'Liste des moyens de transport récupérés avec succès!!');
    }

    static function updateTransport($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $transport = Transport::find($id);

        if (!$transport) {
            return self::sendError("Ce moyen de transport n'existe pas", 404);
        }

        // $transport_ = Transport::where(["owner" => $user->id, "id" => $id])->get();
        // if ($transport_->count() == 0) {
        //     return self::sendError("Ce moyen de transport ne vous appartient pas! Vous ne pouvez pas le modifier!", 404);
        // }

        ##GESTION DES IMAGES
        if ($request->file('gris_card')) {
            $img = $request->file('gris_card');
            $img_name = $img->getClientOriginalName();
            $request->file('gris_card')->move("gris_card", $img_name);

            $formData["gris_card"] = asset("gris_card/" . $img_name);
        }

        if ($request->file('tech_visit')) {
            $img = $request->file('tech_visit');
            $img_name = $img->getClientOriginalName();
            $request->file('tech_visit')->move("tech_visit", $img_name);

            $formData["tech_visit"] = asset("tech_visit/" . $img_name);
        }

        if ($request->file('assurance_card')) {
            $img = $request->file('assurance_card');
            $img_name = $img->getClientOriginalName();
            $request->file('assurance_card')->move("assurance_card", $img_name);

            $formData["assurance_card"] = asset("assurance_card/" . $img_name);
        }

        if ($request->file("img1")) {
            $img1 = $request->file('img1');
            $img_name = $img1->getClientOriginalName();
            $request->file('img1')->move("vehicule_images", $img_name);
            $formData["img1"] = asset("vehicule_images/" . $img_name);
        }

        if ($request->file("img2")) {
            $img1 = $request->file('img2');
            $img_name = $img1->getClientOriginalName();
            $request->file('img2')->move("vehicule_images", $img_name);
            $formData["img2"] = asset("vehicule_images/" . $img_name);
        }

        if ($request->file("img3")) {
            $img1 = $request->file('img3');
            $img_name = $img1->getClientOriginalName();
            $request->file('img3')->move("vehicule_images", $img_name);
            $formData["img3"] = asset("vehicule_images/" . $img_name);
        }

        ###TRAITEMENT DU STATUS DU TRANSPORT
        if ($request->get("status")) {
            if (!IsUserAnAdmin($user->id)) {
                return self::sendError("Désolé! Seuls les admins peuvent valider un transport", 201);
            }
            $status_ = $request->get('status');
            $status = TransportStatus::find($status_);

            if (!$status) {
                return self::sendError("Ce status de transport n'existe pas!", 404);
            }

            $transport->status = $status_;
            $transport->save(); ### Changement de status
        }

        $transport->update($formData);
        return self::sendResponse($transport, "Moyen de transport modifié avec succès!!");
    }

    static function deleteTransport($id)
    {
        $user = request()->user();
        $transport = Transport::find($id);

        if (!$transport) {
            return self::sendError("Ce moyen de transport n'existe pas", 404);
        }

        $transport_ = Transport::where(["owner" => $user->id, "id" => $id])->get();
        if ($transport_->count() == 0) {
            return self::sendError("Ce moyen de transport ne vous appartient pas! Vous ne pouvez pas le supprimer!", 404);
        }

        $transport->delete(); ### SUPPRESSION DU MOYEN DE TRANSPORT;
        return self::sendResponse($transport, "Ce moyen de transport a été supprimé avec succès!!");
    }
}
