<?php

namespace App\Http\Controllers\Generate;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Nomina;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GenerateController extends Controller
{
    
    public function generate($data)
    {
       // dd($data);
        $factura = Factura::with(['departamento', 'user'])->findOrFail($data['id']);

        $pdf = Pdf::loadView('pdf.factura', compact('factura'));
        
        $fileName = 'invoice_' . time() . '.pdf';
        $filePath = 'pdfFacturas/' . $fileName;
        
        \Storage::put($filePath, $pdf->output());

        // Guardamos el path en la factura
        $factura->update([
            'comprobante_pdf' => $filePath,
        ]);

         return redirect()->back();// Si quieres, devuelve la URL pública
    }

    public function nomina_generate($data)
    {
        $user = User::findOrFail($data['user_id']);
        $nomina = Nomina::findOrFail($data['id']);

        $pdf = Pdf::loadView('pdf.nomina', compact('user','nomina'));

        $fileName = 'nomina_' . $nomina->id . '_' . time() . '.pdf';
        $filePath = 'pdfNomina/' . $fileName;

        Storage::put($filePath, $pdf->output());

        $nomina->update([
            'comprobante_pdf' => $filePath,
        ]);

        return $filePath; // Devolver el path del archivo generado
    }

   
}
