<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use Barryvdh\DomPDF\Facade\Pdf;

class WriterController extends Controller
{
    protected $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        $response = $this->api->get('/writers');
        $writers = $response->json(); // Feltételezzük, hogy az API JSON listát ad

        // CSV Export
        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportCsv($writers);
        }
        
        // PDF Export
        if ($request->has('export') && $request->export == 'pdf') {
            return $this->exportPdf($writers);
        }

        return view('writers.index', compact('writers'));
    }

    public function store(Request $request)
    {
        // Fájl feltöltés előkészítése
        $files = [];
        if ($request->hasFile('portrait_path')) {
            $files['portrait_path'] = $request->file('portrait_path');
        }

        $this->api->post('/writers', $request->all(), $files);
        return redirect()->route('writers.index');
    }

    // PDF Export metódus
    private function exportPdf($data)
    {
        $pdf = Pdf::loadView('exports.writers', ['writers' => $data]);
        return $pdf->download('szerzok_lista.pdf');
    }

    // CSV Export metódus (egyszerű megoldás)
    private function exportCsv($data)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=szerzok.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Név', 'Bio']); // Fejléc

            foreach ($data as $row) {
                fputcsv($file, [$row['name'], $row['bio']]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
