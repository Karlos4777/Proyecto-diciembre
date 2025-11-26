<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Producto::with(['categoria','catalogo'])->get()->map(function($p){
            return [
                'ID' => $p->id,
                'Codigo' => $p->codigo,
                'Nombre' => $p->nombre,
                'Precio' => $p->precio,
                'Descuento' => $p->descuento ?? 0,
                'Cantidad' => $p->cantidad,
                'Categoria' => $p->categoria->nombre ?? '',
                'Catalogo' => $p->catalogo->nombre ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID','Codigo','Nombre','Precio','Descuento','Cantidad','Categoria','Catalogo'];
    }
}
