<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use PDF;

class PdfController extends Controller
{
    public function getPdf(Request $request)
    {
        // L'instance PDF avec la vue resources/views/posts/show.blade.php
        $data =[
            'id'=>1,
            'nom'=>'GOGO',
            'description'=>'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Facilis, molestiae. Sunt suscipit magnam in, iusto illum laboriosam provident, aliquam minus vitae ducimus dicta inventore doloribus earum omnis tempora beatae perspiciatis.',
        ];

        $users = User::all();
        // return $users;
        $pdf = PDF::loadView('pdf',compact('users'));
        // $pdf = PDF::loadView('pdf',$users)->stream();

        // return $pdf->download(Str::slug($data['nom']).'facture.pdf');
        return $pdf->stream();
    }
}
