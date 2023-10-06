<?php

use App\Mail\SendEmail;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Mail;

####_____________

function userCount()
{
    return count(User::all()) + 1;
}

function Custom_Timestamp()
{
    $date = new DateTimeImmutable();
    $micro = (int)$date->format('Uu'); // Timestamp in microseconds
    return $micro;
}

##Ce Helper permet de creér le passCode de réinitialisation de mot de passe
function Get_passCode($user, $type)
{
    $created_date = $user->created_at;

    $year = explode("-", $created_date)[0]; ##RECUPERATION DES TROIS PREMIERS LETTRES DU USERNAME
    $an = substr($year, -2);
    $timestamp = substr(Custom_Timestamp(), -3);

    $passcode =  $timestamp . $type . $an . userCount();
    return $passcode;
}

##Ce Helper permet de creér le passCode de réinitialisation de mot de passe
function Get_compte_active_Code($user, $type)
{
    $created_date = $user->created_at;

    $year = explode("-", $created_date)[0]; ##RECUPERATION DES TROIS PREMIERS LETTRES DU USERNAME
    $an = substr($year, -2);
    $timestamp = substr(Custom_Timestamp(), -3);

    $passcode =  $timestamp . $type . $an . userCount();
    return $passcode;
}

function Send_Email($email, $subject, $message)
{
    $data = [
        "subject" => $subject,
        "message" => $message,
    ];
    Mail::to($email)->send(new SendEmail($data));
}

function Send_Notification($receiver, $subject, $message)
{
    $data = [
        "subject" => $subject,
        "message" => $message,
    ];
    Notification::send($receiver, new SendNotification($data));
}

function IsUserAnAdmin($userId)
{
    $user = User::find($userId);
    $roles = $user->roles;
    // return $roles;
    foreach ($roles as $role) {
        if ($role->label == "is_admin") { #S'IL EST UN ADMIN
            return true;
        }
        return false; #S'IL N'EST PAS UN ADMIN
    }
}

####___CE HELPER PERMET D'OBTENIR LA COMMISION SUR CHAQUE TRANSACTION POUR AGBANDE
function TRANSACTION_COMMISSION($fret_price)
{
    return ($fret_price * 5) / 100;
}

function IsUserAnAdminOrTransporter($userId)
{
    $user = User::find($userId);
    $roles = $user->roles;
    foreach ($roles as $role) {
        if ($role->label == "is_transporter") { #S'IL EST UN TRANSPORTEUR
            return true;
        }

        if ($role->label == "is_admin") { #S'IL EST UN ADMIN
            return true;
        }
        return false; #S'IL N'EST NI TRANSPORTEUR NI ADMIN
    }
}

function IsUserAnAdminOrExpeditor($userId)
{
    $user = User::find($userId);
    $roles = $user->roles;
    foreach ($roles as $role) {
        if ($role->label == "is_sender") { #S'IL EST UN EXPEDITEUR
            return true;
        }

        if ($role->label == "is_admin") { #S'IL EST UN ADMIN
            return true;
        }
        return false; #S'IL N'EST NI EXPEDITEUR NI ADMIN
    }
}


function IsUserAnAdminOrBiller($userId)
{
    $user = User::find($userId);
    $roles = $user->roles;
    foreach ($roles as $role) {
        if ($role->label == "is_biller") { #S'IL EST UN FACTURIER
            return true;
        }

        if ($role->label == "is_admin") { #S'IL EST UN ADMIN
            return true;
        }
        return false; #S'IL N'EST NI FACTURIER NI ADMIN
    }
}
