<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class CONTACT_HELPER extends BASE_HELPER
{
    static function contact_rules(): array
    {
        return [
            'name' => ['required'],
            'phone' => ['required', 'numeric'],
            'object' => ['required'],
            'message' => ['required'],
        ];
    }

    static function contact_messages(): array
    {
        return [
            'phone.required' => 'Le champ phone est réquis!',
            'phone.numeric' => 'Le phone doit être un nombre entier',
            'object.required' => 'L\'object est réquis!',
            'name.required' => 'Le champ name est réquis!',
            'message.required' => 'Le message est réquis!',
        ];
    }

    static function Contact_Validator($formDatas)
    {
        $rules = self::contact_rules();
        $messages = self::contact_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createContact($formData)
    {
        $contact = Contact::create($formData);
        return self::sendResponse($contact, 'Contact enregistré avec succès!!');
    }

    static function retrieveContact($id)
    {
        $contact = Contact::find($id);
        return self::sendResponse($contact, 'Contact récupré avec succès!!');
    }

    static function allContacts()
    {
        $contacts = Contact::orderBy("id", "desc")->get();
        return self::sendResponse($contacts, 'Contacts récupérés avec succès!!');
    }

    static function _deleteContact($id)
    {
        $contact = Contact::fin($id);

        if (!$contact) {
            return self::sendError('Ce contact n\'existe pas!', 404);
        };
        return self::sendResponse($contact, "Ce contact a été supprimé avec succès!!");
    }
}
