<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Frets;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use PDF;

class PdfController extends Controller
{
    public function getPdf(Request $request)
    {
        // L'instance PDF avec la vue resources/views/posts/show.blade.php
        $data = [
            'id' => 1,
            'nom' => 'GOGO',
            'description' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Facilis, molestiae. Sunt suscipit magnam in, iusto illum laboriosam provident, aliquam minus vitae ducimus dicta inventore doloribus earum omnis tempora beatae perspiciatis.',
        ];

        $users = User::all();
        $client = User::find(1);
        $reference = Custom_Timestamp();
        $frets = Frets::where(["owner" => 1, "status" => 5, "affected" => 1])->get();
        $formData["frets"] = $frets;
        // return $formData;
        $pdf = PDF::loadView('facture', compact(["client", "reference","frets"]));
        $pdf->save(public_path("factures/" . $reference . ".pdf"));

        // $pdf = PDF::loadView('pdf',$users)->stream();

        // return $pdf->download(Str::slug($data['nom']).'facture.pdf');
        return $pdf->stream();
    }
}
