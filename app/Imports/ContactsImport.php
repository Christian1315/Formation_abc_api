<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class ContactsImport implements ToModel,WithHeadingRow,WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $contact)
    {
        return Contact::create([
            'firstname'    => $contact['firstname'],
            'lastname'     => $contact['lastname'],
            'phone'    => $contact['phone'],
            'detail'    => $contact['detail'],
        ]);
    }

    public function chunkSize(): int
    {
        return 10;
    }
}
