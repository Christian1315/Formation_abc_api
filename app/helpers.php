<?php

use App\Mail\SendEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

function Send_Email($email, $subject, $message)
{
    $data = [
        "subject" => $subject,
        "message" => $message,
    ];
    Mail::to($email)->send(new SendEmail($data));
}

function IsUserAnAdmin()
{
    $user = request()->user();
    $roles = $user->roles;
    // return $roles;
    foreach ($roles as $role) {
        if ($role->label == "is_admin") { #S'IL EST UN ADMIN
            return true;
        }

        return false; #S'IL N'EST PAS UN ADMIN
    }
}

function IsUserAnAdminOrTransporter()
{
    $user = request()->user();
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
