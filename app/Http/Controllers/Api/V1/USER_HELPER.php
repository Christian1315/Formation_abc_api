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

    ##======== ADDING A USER =======##
    static function add_user_rules(): array
    {
        return [
            'role' => ['required', "integer"],
            'phone' => ['required', "integer", Rule::unique("users")],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', Rule::unique('users')],
        ];
    }

    static function add_user_messages(): array
    {
        return [
            'role.required' => 'Le champ role est réquis!',
            'role.integer' => 'Le champ role doit être un entier!',

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

    static function Add_user_Validator($formDatas)
    {
        $rules = self::add_user_rules();
        $messages = self::add_user_messages();

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

        ##GESTION DES IMAGES
        if ($request->file("ifu")) {
            $ifu = $request->file('ifu');
            $img_name = $ifu->getClientOriginalName();
            $request->file('ifu')->move("ifu", $img_name);
            $formData["ifu"] = asset("ifu/" . $img_name);
        }

        if ($request->file("rccm")) {
            $rccm = $request->file('rccm');
            $img_name = $rccm->getClientOriginalName();
            $request->file('rccm')->move("rccm", $img_name);
            $formData["rccm"] = asset("rccm/" . $img_name);
        }

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

    static function addUser($request)
    {
        $formData = $request->all();
        $role = Role::find($formData["role"]);
        if (!$role) {
            return self::sendError("Ce role n'existe pas", 404);
        }

        ##GESTION DES IMAGES
        if ($request->file("ifu")) {
            $ifu = $request->file('ifu');
            $img_name = $ifu->getClientOriginalName();
            $request->file('ifu')->move("ifu", $img_name);
            $formData["ifu"] = asset("ifu/" . $img_name);
        }

        if ($request->file("rccm")) {
            $rccm = $request->file('rccm');
            $img_name = $rccm->getClientOriginalName();
            $request->file('rccm')->move("rccm", $img_name);
            $formData["rccm"] = asset("rccm/" . $img_name);
        }

        $user = User::create($formData); #ENREGISTREMENT DU USER DANS LA DB

        #AFFECTATION DU ROLE **$role** AU USER **$user** 
        $user->roles()->attach($role);

        $active_compte_code = Get_compte_active_Code($user, "ACT");
        $user->active_compte_code = $active_compte_code;
        $user->compte_actif = 0;
        $user->save();

        ###____
        $current_user = request()->user();

        $adder = $current_user->company_name ? $current_user->company_name : $current_user->firstname . " " . $current_user->lastname;
        #=====ENVOIE D'EMAIL =======~####
        $message = $adder . " viens de vous créer un compte sur AGBANDE";
        $compte_activation_msg = "Le compte n'est pas encore actif. Veuillez l'activer en utilisant le code ci-dessous : " . $active_compte_code;


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

        return self::sendResponse($user, 'Utilisateur ajouté avec succès!!');
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
        $users =  User::orderBy("id","desc")->get();
        return self::sendResponse($users, 'Tout les utilisatreurs récupérés avec succès!!');
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
