<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ROLE_HELPER extends BASE_HELPER
{
    ##======== ROLE ATTACHEMENT =======##
    static function role_rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'role_id' => 'required|integer',
        ];
    }

    static function role_messages(): array
    {
        return [
            'user_id.required' => 'Veuillez précisez l\'ID de l\'utilisateur',
            'role_id.required' => 'Veuillez précisez l\'ID du role',
        ];
    }

    static function Role_Validator($formDatas)
    {
        #
        $rules = self::role_rules();
        $messages = self::role_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function allRoles()
    {
        $roles =  Role::orderBy("id", "desc")->get();
        return self::sendResponse($roles, 'Tout les status d\'Fret récupérés avec succès!!');
    }

    static function _retrieveRole($id)
    {
        $role = Role::find($id);
        if ($role->count() == 0) {
            return self::sendError("Ce role n'existe pas!", 404);
        }
        return self::sendResponse($role, "Role récupéré avec succès:!!");
    }

    static function roleAttach($formData)
    {
        $user = User::find($formData['user_id']);
        if (!$user) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        };

        $role = Role::find($formData['role_id']);
        if (!$role) {
            return self::sendError("Ce role n'existe pas!", 404);
        };

        $role_user = RoleUser::where(["user_id" => $formData['user_id'], "role_id" => $formData['role_id']])->get();
        if ($role_user->count() != 0) {
            return self::sendError("Ce role était déjà attaché à cet utilisateur", 505);
        };

        $role_user = RoleUser::create($formData);
        return self::sendResponse($role_user, "User attaché au role avec succès!!");
    }

    static function roleDesAttach($formData)
    {
        $user = User::find($formData['user_id']);
        if (!$user) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        };

        $role = Role::find($formData['role_id']);
        if (!$role) {
            return self::sendError("Ce role n'existe pas!", 404);
        };
        $role_user = RoleUser::where(["user_id" => $formData['user_id'], "role_id" => $formData['role_id']])->get();

        if ($role_user->count() == 0) {
            return self::sendError("Ce role n'était pas attaché à cet utilisateur", 505);
        };
        $role_user = $role_user[0];
        $role_user->delete();
        return self::sendResponse($role_user, "Utilisateur dettaché du role avec succès!!");
    }
}
