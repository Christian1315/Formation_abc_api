<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FretStatus;

class FRET_STATUS_HELPER extends BASE_HELPER
{
    static function allFretStatus()
    {
        $Fret_status =  FretStatus::orderBy("id", "desc")->get();
        return self::sendResponse($Fret_status, 'Tout les status d\'Fret récupérés avec succès!!');
    }

    static function _retrieveFretStatus($id)
    {
        $Fret_status = FretStatus::where(['id' => $id])->get();
        if ($Fret_status->count() == 0) {
            return self::sendError("Ce status de Fret n'existe pas!", 404);
        }
        return self::sendResponse($Fret_status, "Status de Fret récupéré avec succès:!!");
    }
}
