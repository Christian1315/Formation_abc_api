<?php

namespace App\Http\Controllers\Api\V1;


class Authorization extends BASE_HELPER
{
    #ACCES BLOQUE QUAND LE USER N'EST PAS AUTHENTIFIE(E)
    function Authorization()
    {
        return $this->sendError("Accès réfusé \n! Veuillez-vous authentifiez!", 201);
    }
}
