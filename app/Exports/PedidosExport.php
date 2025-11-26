<?php

namespace App\Exports;

use App\Models\Pedido;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PedidosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pedido::with('user')->get()->map(function($p){
            return [
                'ID' => $p->id,
                'Usuario' => $p->user->name ?? '',
                'Email' => $p->user->email ?? '',
                'Fecha' => $p->created_at->format('Y-m-d'),
                'Estado' => $p->estado,
                'Total' => $p->total,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID','Usuario','Email','Fecha','Estado','Total'];
    }
}
