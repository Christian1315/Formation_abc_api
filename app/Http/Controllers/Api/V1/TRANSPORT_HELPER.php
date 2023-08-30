<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Transport;
use App\Models\Type;
use Illuminate\Support\Facades\Validator;

class TRANSPORT_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function transport_rules(): array
    {
        return [
            'type_id' => 'required|integer',
            'fabric_year' => 'required',
            'circulation_year' => 'required',
            'tech_visit' => 'required',
            'tech_visit_expire' => 'required',
            'gris_card' => 'required',
            'assurance_card' => 'required',
        ];
    }

    static function transport_messages(): array
    {
        return [
            'type_id.required' => 'Veuillez précisez le type de moyen de transport que vous essayez d\'ajouter',
            'type_id.integer' => 'Ce champ requiert un entier',
            'fabric_year.required' => 'Veuillez precisez la date de fabrication!',
            'circulation_year.required' => 'Veuillez precisez la date de la mise en circulation!',
            'tech_visit.required' => 'envoyer une photo de la visite technique!',
            'tech_visit_expire.required' => 'Veuillez precisez la date d\'expiration de la visite technique!',
            'gris_card.required' => 'Veuillez envoyer une photo de la carte grise!',
            'assurance_card.required' => 'Veuillez envoyer une photo de la carte d\'assurance!',
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
        $user = request()->user();
        if (!IsUserAnAdmin()) { ##SI LE USER EST UN ADMIN
            $transport = Transport::with(['owner', 'type'])->find($id);
            if (!$transport) {
                return self::sendError('Ce moyen de transport n\'existe pas!', 404);
            };
            return self::sendResponse($transport, "Moyen de transport récupéré avec succès");
        }

        ### S'il est un simple user

        $transport = Transport::with(['owner', 'type'])->where(['owner' => $user->id, "id" => $id])->find($id);
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

        $tech_visit = $request->file('tech_visit');
        $img_name = $tech_visit->getClientOriginalName();
        $request->file('tech_visit')->move("tech_visit", $img_name);

        $formData["tech_visit"] = asset("tech_visit/" . $img_name);

        $assurance_card = $request->file('assurance_card');
        $img_name = $assurance_card->getClientOriginalName();
        $request->file('assurance_card')->move("assurance_card", $img_name);

        $formData["assurance_card"] = asset("assurance_card/" . $img_name);

        ##ENREGISTREMENT DU TRANSPORT DANS LA DB
        $transport = Transport::create($formData); #ENREGISTREMENT DU USER DANS LA DB
        $transport->owner = request()->user()->id;
        $transport->save();

        return self::sendResponse($transport, 'Moyen de transport ajouté avec succès!!');
    }

    static function transports()
    {
        $user = request()->user();
        if (!IsUserAnAdmin()) { ##SI LE USER EST UN ADMIN
            $transports = Transport::with(['owner', 'type'])->orderBy('id', 'desc')->get();
            return self::sendResponse($transports, "Moyen de transports récupérés avec succès");
        }

        ### S'il est un simple user
        ### il recupère seulement les transports qui lui appartiennent

        $transports = Transport::with(['owner', 'type'])->where(['owner' => $user->id])->orderBy('id', 'desc')->get();
        return self::sendResponse($transports, 'Listes des moyens de transport récupérés avec succès!!');
    }

    static function updateTransport($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $transport = Transport::find($id);

        if (!$transport) {
            return self::sendError("Ce moyen de transport n'existe pas", 404);
        }

        $transport_ = Transport::where(["owner" => $user->id, "id" => $id])->get();
        if ($transport_->count() == 0) {
            return self::sendError("Ce moyen de transport ne vous appartient pas! Vous ne pouvez pas le modifier!", 404);
        }

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

        $transport->delete(); #SUPPRESSION DU MOYEN DE TRANSPORT;
        return self::sendResponse($transport, "Ce moyen de transport a été supprimé avec succès!!");
    }
}
