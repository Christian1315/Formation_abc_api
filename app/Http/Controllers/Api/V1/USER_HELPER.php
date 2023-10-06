<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class USER_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function register_rules(): array
    {
        return [
            'firstname' => ['required'],
            'lastname' => ['required'],
            'phone' => ['required', "integer", Rule::unique("users")],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', Rule::unique('users')],
        ];
    }

    static function register_messages(): array
    {
        return [
            'expeditor.required' => 'Le champ expeditor est réquis!',
            'expeditor.boolean' => 'Le champ expeditor doit être un boolean!',
            'transporter.required' => 'Le champ transporter est réquis!',
            'transporter.boolean' => 'Le champ transporter doit être un boolean!',

            'phone.required' => 'Le champ Phone est réquis!',
            'phone.integer' => 'Le champ Phone doit être un entier!',
            'phone.unique' => 'Ce Phone existe déjà!',
            'email.required' => 'Le champ Email est réquis!',
            'email.email' => 'Ce champ est un mail!',
            'email.unique' => 'Ce mail existe déjà!',
            'password.required' => 'Le champ Password est réquis!',
            'password.unique' => 'Ce mot de passe existe déjà!!',
        ];
    }

    static function Register_Validator($formDatas)
    {
        #
        $rules = self::register_rules();
        $messages = self::register_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== LOGIN VALIDATION =======##
    static function login_rules(): array
    {
        return [
            'phone' => 'required|numeric',
            'password' => 'required',
        ];
    }

    static function login_messages(): array
    {
        return [
            'phone.required' => 'Le phone est réquis!',
            'phone.numeric' => 'Le phone doit être numérique!',
            'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function Login_Validator($formDatas)
    {
        #
        $rules = self::login_rules();
        $messages = self::login_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== NEW PASSWORD VALIDATION =======##
    static function NEW_PASSWORD_rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required',
        ];
    }

    static function NEW_PASSWORD_messages(): array
    {
        return [
            // 'new_password.required' => 'Veuillez renseigner soit votre username,votre phone ou soit votre email',
            // 'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function NEW_PASSWORD_Validator($formDatas)
    {
        #
        $rules = self::NEW_PASSWORD_rules();
        $messages = self::NEW_PASSWORD_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createUser($request)
    {
        $formData = $request->all();

        $user = User::create($formData); #ENREGISTREMENT DU USER DANS LA DB

        #=====ENVOIE DE NOTIFICATION =======~####
        $message = "Votre Compte a été crée avec succès sur AGBANDE";

        try {
            Send_Notification(
                $user,
                "CREATION DE COMPTE SUR FORMATION ABC",
                $message
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        return self::sendResponse($user, 'Compte crée avec succès!!');
    }

    static function userAuthentification($request)
    {

        if (Auth::attempt(['phone' => $request->get('phone'), 'password' => $request->get('password')])) { #SI LE USER EST AUTHENTIFIE
            $user = Auth::user();

            $token = $user->createToken('MyToken', ['api-access'])->accessToken;
            $user["token"] = $token;
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
            return self::sendResponse($user, 'Vous etes connecté(e) avec succès!!');
        }

        #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
        return self::sendError('Connexion échouée! Vérifiez vos données puis réessayez à nouveau!', 500);
    }

    static function getUsers()
    {
        $users =  User::orderBy("id", "desc")->get();
        return self::sendResponse($users, 'Tout les utilisatreurs récupérés avec succès!!');
    }

    static function _updatePassword($formData)
    {
        $user = request()->user();
        if (!$user) {
            return self::sendError("Ce compte ne vous appartient pas!", 404);
        };

        #### VERIFIONS SI LE NOUVEAU PASSWORD CORRESPONDS ENCORE AU ANCIEN PASSWORD
        if ($formData["old_password"] == $formData["new_password"]) {
            return self::sendError('Le nouveau mot de passe ne doit pas etre identique à votre ancien mot de passe', 404);
        }

        if (Hash::check($formData["old_password"], $user->password)) { #SI LE old_password correspond au password du user dans la DB
            $user->update(["password" => $formData["new_password"]]);
            return self::sendResponse($user, 'Mot de passe modifié avec succès!');
        }
        return self::sendError("Votre mot de passe est incorrect", 505);
    }

    static function retrieveUsers($id)
    {
        $user = User::find($id);
        if ($user->count() == 0) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        }
        return self::sendResponse($user, "Utilisateur récupé avec succès:!!");
    }

    static function userLogout($request)
    {
        $request->user()->token()->revoke();
        return self::sendResponse([], 'Vous etes déconnecté(e) avec succès!');
    }
}
