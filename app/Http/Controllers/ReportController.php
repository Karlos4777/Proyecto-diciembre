<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosExport;
use App\Exports\PedidosExport;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function productosCsv(): StreamedResponse
    {
        $filename = 'productos_' . now()->format('Ymd_His') . '.csv';
        $productos = Producto::with(['categoria','catalogo'])->orderBy('id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID','Codigo','Nombre','Precio','Descuento','Cantidad','Categoria','Catalogo'];

        return Response::stream(function() use ($productos,$columns){
            $output = fopen('php://output','w');
            // BOM para Excel UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, $columns,';');
            foreach($productos as $p){
                fputcsv($output,[
                    $p->id,
                    $p->codigo,
                    $p->nombre,
                    number_format($p->precio,2,'.',''),
                    $p->descuento ?? 0,
                    $p->cantidad,
                    $p->categoria->nombre ?? '',
                    $p->catalogo->nombre ?? '',
                ],';');
            }
            fclose($output);
        },200,$headers);
    }

    public function pedidosCsv(): StreamedResponse
    {
        $filename = 'pedidos_' . now()->format('Ymd_His') . '.csv';
        $pedidos = Pedido::with(['user'])->orderBy('id','desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID','Usuario','Email','Fecha','Estado','Total'];

        return Response::stream(function() use ($pedidos,$columns){
            $output = fopen('php://output','w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output,$columns,';');
            foreach($pedidos as $p){
                fputcsv($output,[
                    $p->id,
                    $p->user->name ?? '',
                    $p->user->email ?? '',
                    $p->created_at->format('Y-m-d'),
                    $p->estado,
                    number_format($p->total,2,'.',''),
                ],';');
            }
            fclose($output);
        },200,$headers);
    }

    public function productosPdf()
    {
        $productos = Producto::with(['categoria','catalogo'])->orderBy('id')->get();
        $pdf = Pdf::loadView('reportes.productos_pdf', compact('productos'));
        return $pdf->download('productos_' . now()->format('Ymd_His') . '.pdf');
    }

    public function pedidosPdf()
    {
        $pedidos = Pedido::with(['user','lineas'])->orderBy('id','desc')->get();
        $pdf = Pdf::loadView('reportes.pedidos_pdf', compact('pedidos'));
        return $pdf->download('pedidos_' . now()->format('Ymd_His') . '.pdf');
    }

    public function productosExcel()
    {
        return Excel::download(new ProductosExport, 'productos_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function pedidosExcel()
    {
        return Excel::download(new PedidosExport, 'pedidos_' . now()->format('Ymd_His') . '.xlsx');
    }
}
