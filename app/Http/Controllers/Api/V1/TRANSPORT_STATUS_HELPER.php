<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TransportStatus;

class TRANSPORT_STATUS_HELPER extends BASE_HELPER
{
    static function allTransportStatus()
    {
        $Transport_status =  TransportStatus::orderBy("id", "desc")->get();
        return self::sendResponse($Transport_status, 'Tout les status d\'Transport récupérés avec succès!!');
    }

    static function _retrieveTransportStatus($id)
    {
        $Transport_status = TransportStatus::where(['id' => $id])->get();
        if ($Transport_status->count() == 0) {
            return self::sendError("Ce status de Transport n'existe pas!", 404);
        }
        return self::sendResponse($Transport_status, "Status de Transport récupéré avec succès:!!");
    }
}
