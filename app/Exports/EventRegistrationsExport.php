<?php

namespace App\Exports;

use App\Models\EventRegistration;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class EventRegistrationsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.registrations', [
            'registrations' => EventRegistration::all()
        ]);
    }
}
