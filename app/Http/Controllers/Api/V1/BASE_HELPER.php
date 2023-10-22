<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class BASE_HELPER extends Controller
{

    ##========== RESPONSE MANAGEMENT =======##
    static function sendResponse($data, $message, $cookie = null)
    {
        $response = [
            'status' => True,
            'data' => $data,
            'message' => $message,
        ];
        if ($cookie) {
            return response()->json($response, 200)->withCookie($cookie);
        }
        return response()->json($response, 200);
    }

    static function sendError($message, $code)
    {
        $response = [
            'status' => false,
            'erros' => $message,
        ];

        return response()->json($response, $code);
    }

    #======= METHOD VALIDATION =====#
    function methodValidation($method, $supportedMethod)
    {
        # $method: C'est la methode envoyée par la requete
        # $supportedMethod: C'est la methode requise par la requete

        $method_validated = False;
        if ($method == $supportedMethod) {
            $method_validated = true;
        }
        return $method_validated;
    }
}
