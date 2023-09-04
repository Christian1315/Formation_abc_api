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
            'transporter' => ['required', "boolean"],
            // 'firstname' => 'required',
            // 'lastname' => 'required',
            'phone' => ['required', "integer", Rule::unique("users")],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', Rule::unique('users')],
        ];
    }

    static function register_messages(): array
    {
        return [
            'expeditor.required' => 'Le champ **expeditor** est réquis!',
            'expeditor.boolean' => 'Le champ **expeditor** doit être un boolean!',
            'expeditor.transporter' => 'Le champ **transporter** est réquis!',
            'expeditor.transporter' => 'Le champ **transporter** doit être un boolean!',

            // 'firstname.required' => 'Le champ Firstname est réquis!',
            // 'lastname.required' => 'Le champ Lastname est réquis!',
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
            'account' => 'required',
            'password' => 'required',
        ];
    }

    static function login_messages(): array
    {
        return [
            'account.required' => 'Le champ Account est réquis!',
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

    static function createUser($formData)
    {
        $expeditor = $formData["expeditor"];
        $transporter = $formData["transporter"];

        if ($expeditor == $transporter) {
            return self::sendError("Désolé! Soit vous êtes un Expéditeur ou soit un Transporteur!", 505);
        };

        $role = null;
        if ($expeditor) { ##IL S'AGIT D'UN EXPEDITEUR(is_sender)
            $role = Role::find(2); ###ROLE D'UN EXPEDITEUR(is_sender)
        }

        if ($transporter) {
            $role = Role::find(1); ###ROLE D'UN TRANSPORTEUR(is_transporter)
        };

        $user = User::create($formData); #ENREGISTREMENT DU USER DANS LA DB

        #AFFECTATION DU ROLE **$role** AU USER **$user** 
        $user->roles()->attach($role);


        $active_compte_code = Get_compte_active_Code($user, "ACT");
        $user->active_compte_code = $active_compte_code;
        $user->compte_actif = 0;
        $user->save();

        #=====ENVOIE D'EMAIL =======~####
        $message = "Votre Compte a été crée avec succès sur AGBANDE";
        $compte_activation_msg = "Votre compte n'est pas encore actif. Veuillez l'activer en utilisant le code ci-dessous : " . $active_compte_code;


        Send_Email(
            $user->email,
            "Création de compte sur AGBANDE",
            $message,
        );

        Send_Email(
            $user->email,
            "Activation de compte sur AGBANDE",
            $compte_activation_msg,
        );

        return self::sendResponse($user, 'Compte crée avec succès!!');
    }

    static function userAuthentification($request)
    {

        if (is_numeric($request->get('account'))) {
            $credentials  =  ['phone' => $request->get('account'), 'password' => $request->get('password')];
        } elseif (filter_var($request->get('account'), FILTER_VALIDATE_EMAIL)) {
            $credentials  =  ['email' => $request->get('account'), 'password' => $request->get('password')];
        }

        if (Auth::attempt($credentials)) { #SI LE USER EST AUTHENTIFIE
            $user = Auth::user();

            ###VERIFIONS SI LE COMPTE EST ACTIF
            if (!$user->compte_actif) {
                return self::sendError("Ce compte n'est pas actif! Veuillez l'activer", 404);
            }

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

    static function activateAccount($request)
    {
        if (!$request->get("active_compte_code")) {
            return self::sendError("Le Champ **active_compte_code** est réquis", 505);
        }
        $user =  User::where(["active_compte_code" => $request->active_compte_code])->get();
        if ($user->count() == 0) {
            return self::sendError("Ce Code ne corresponds à aucun compte! Veuillez saisir le vrai code", 505);
        }

        $user = $user[0];
        ###VERIFIONS SI LE COMPTE EST ACTIF DEJA

        if ($user->compte_actif) {
            return self::sendError("Ce compte est déjà actif!", 505);
        }

        $user->compte_actif = 1;
        $user->save();
        return self::sendResponse($user, 'Votre compte à été activé avec succès!!');
    }

    static function getUsers()
    {

        $users =  User::with(['transports', 'roles', 'frets', 'notifications'])->get();
        return self::sendResponse($users, 'Touts les utilisatreurs récupérés avec succès!!');
    }

    static function _updatePassword($formData)
    {
        $user = User::where(['id' => request()->user()->id])->get();
        if (count($user) == 0) {
            return self::sendError("Ce compte ne vous appartient pas!", 404);
        };

        #### VERIFIONS SI LE NOUVEAU PASSWORD CORRESPONDS ENCORE AU ANCIEN PASSWORD
        if ($formData["old_password"] == $formData["new_password"]) {
            return self::sendError('Le nouveau mot de passe ne doit pas etre identique à votre ancien mot de passe', 404);
        }

        if (Hash::check($formData["old_password"], $user[0]->password)) { #SI LE old_password correspond au password du user dans la DB
            $user[0]->update(["password" => $formData["new_password"]]);
            return self::sendResponse($user, 'Mot de passe modifié avec succès!');
        }
        return self::sendError("Votre mot de passe est incorrect", 505);
    }

    static function _demandReinitializePassword($request)
    {

        if (!$request->get("account")) {
            return self::sendError("Le Champ account est réquis!", 404);
        }
        $account = $request->get("account");

        $user = null;
        if (is_numeric($account)) {
            $user = User::where(['phone' => $account])->get();
        } elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $user = User::where(['email' => $account])->get();
        }
        if (!$user) {
            return self::sendError("Ce compte n'existe pas!", 404);
        };

        $user = $user[0];
        $pass_code = Get_passCode($user, "PASS");
        $user->pass_code = $pass_code;
        $user->pass_code_active = 1;
        $user->save();

        $message = "Demande de réinitialisation éffectuée avec succès sur AGBANDE! Voici vos informations de réinitialisation de password ::" . $pass_code;

        #=====ENVOIE D'EMAIL =======~####
        Send_Email(
            $user->email,
            "Demande de réinitialisation de compte sur AGBANDE",
            $message,
        );

        return self::sendResponse($user, "Demande de réinitialisation éffectuée avec succès! Un message vous a été envoyé par mail");
    }

    static function _reinitializePassword($request)
    {

        $pass_code = $request->get("pass_code");

        if (!$pass_code) {
            return self::sendError("Ce Champ pass_code est réquis!", 404);
        }

        $new_password = $request->get("new_password");

        if (!$new_password) {
            return self::sendError("Ce Champ new_password est réquis!", 404);
        }

        $user = User::where(['pass_code' => $pass_code])->get();

        if (count($user) == 0) {
            return self::sendError("Ce code n'est pas correct!", 404);
        };

        $user = $user[0];
        #Voyons si le passs_code envoyé par le user est actif

        if ($user->pass_code_active == 0) {
            return self::sendError("Ce Code a déjà été utilisé une fois! Veuillez faire une autre demande de réinitialisation", 404);
        }

        #UPDATE DU PASSWORD
        $user->update(['password' => $new_password]);

        #SIGNALONS QUE CE pass_code EST D2J0 UTILISE
        $user->pass_code_active = 0;
        $user->save();


        $message = "Réinitialisation de password éffectuée avec succès sur AGBANDE!";

        #=====ENVOIE D'EMAIL =======~####
        Send_Email(
            $user->email,
            "Réinitialisation de compte sur AGBANDE",
            $message,
        );

        return self::sendResponse($user, "Réinitialisation éffectuée avec succès!");
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
