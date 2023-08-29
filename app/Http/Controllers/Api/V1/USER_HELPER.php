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
            'expeditor' => ['required', "boolean"],
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => ['required', "integer"],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', Rule::unique('users')],
        ];
    }

    static function register_messages(): array
    {
        return [
            'expeditor.required' => 'Le champ **expeditor** est réquis!',
            'expeditor.boolean' => 'Le champ **expeditor** doit être un boolean!',
            'firstname.required' => 'Le champ Firstname est réquis!',
            'lastname.required' => 'Le champ Lastname est réquis!',
            'phone.required' => 'Le champ Phone est réquis!',
            'phone.integer' => 'Le champ Phone doit être un entier!',
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
            'email' => 'required',
            'password' => 'required',
        ];
    }

    static function login_messages(): array
    {
        return [
            'email.required' => 'Le champ Email est réquis!',
            'email.email' => 'Ce champ est un mail!',
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


    static function createUser($formData)
    {
        $expeditor = $formData["expeditor"];
        $role = null;
        if ($expeditor) { ##IL S'AGIT D'UN EXPEDITEUR(is_sender)
            $role = Role::find(2); ###ROLE D'UN EXPEDITEUR(is_sender)
        } else {
            $role = Role::find(1); ###ROLE D'UN TRANSPORTEUR(is_transporter)
        };

        $user = User::create($formData); #ENREGISTREMENT DU USER DANS LA DB

        #AFFECTATION DU ROLE **$role** AU USER **$user** 
        $user->roles()->attach($role);

        #=====ENVOIE D'EMAIL =======~####
        $message = "Votre Compte a été crée avec succès sur AGBANDE";
        Send_Email(
            $user->email,
            "Création de compte sur AGBANDE",
            $message,
        );
        return self::sendResponse($user, 'Compte crée avec succès!!');
    }

    static function userAuthentification($request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::attempt($credentials)) { #SI LE USER EST AUTHENTIFIE
            $user = Auth::user();
            $token = $user->createToken('MyToken', ['api-access'])->accessToken;
            $user['roles'] = $user->roles;
            $user['notifications'] = $user->notifications;
            $user['token'] = $token;

            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
            return self::sendResponse($user, 'Vous etes connecté(e) avec succès!!');
        }

        #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
        return self::sendError('Connexion échouée! Vérifiez vos données puis réessayez à nouveau!', 500);
    }

    static function getUsers()
    {

        $users =  User::with(['transports', 'roles', 'frets', 'notifications'])->get();
        return self::sendResponse($users, 'Touts les utilisatreurs récupérés avec succès!!');
    }

    static function retrieveUsers($id)
    {
        $user = User::with(['transports', 'roles', 'frets', 'notifications'])->where('id', $id)->get();
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
