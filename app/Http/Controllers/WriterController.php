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

        if ($response->successful()) {
            $writers = $response->json('writers'); // Vagy 'data', ahogy a backend adja
            $writers = is_array($writers) ? $writers : [];
        } else {
            $writers = [];
            if (!$request->has('export')) { // Exportnál ne dobjunk view hibát
                return back()->withErrors(['api_error' => 'Hiba az API elérésekor.']);
            }
        }

        // --- CSV EXPORT ---
        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportCsv($writers);
        }

        // --- PDF EXPORT ---
        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('exports.writers_pdf', ['writers' => $writers]);
            return $pdf->download('szerzok_lista.pdf');
        }

        return view('writers.index', compact('writers'));
    }

    public function store(Request $request)
    {
        // 1. Validáció a kliens oldalon (hogy ne küldjünk hibás adatot)
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'portrait_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kép ellenőrzés
        ]);

        // 2. Fájl előkészítése, ha van feltöltve
        $files = [];
        if ($request->hasFile('portrait_path')) {
            $files['portrait_path'] = $request->file('portrait_path');
        }

        // 3. Küldés az API-nak
        // Az ApiService post metódusa: post($url, $adatok, $fajlok)
        $response = $this->api->post('/writers', $request->except('portrait_path'), $files);

        // 4. Válasz kezelése
        if ($response->successful()) {
            return redirect()->route('writers.index')->with('success', 'Szerző sikeresen létrehozva!');
        }

        // Hiba esetén visszaküldjük az űrlapra a hibaüzenettel és a beírt adatokkal
        return back()
            ->withErrors(['api_error' => 'Hiba történt a mentés során.'])
            ->withInput();
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
        // 1. A HIÁNYZÓ RÉSZ: A $headers változó definiálása
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=szerzok_lista.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 2. A callback függvény a tartalom generálásához
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM a helyes ékezetekhez Excelben
            
            // Fejléc (Dátum nélkül, ahogy javítottuk)
            fputcsv($file, ['ID', 'Név', 'Biográfia'], ';');

            foreach ($data as $row) {
                fputcsv($file, [
                    $row['id'],
                    $row['name'],
                    $row['bio'] ?? '', // Ha nincs bio, üres legyen
                ], ';');
            }
            fclose($file);
        };

        // 3. Válasz visszaadása a $headers változóval
        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('writers.create');
    }

    public function edit($id)
    {
        // Lekérjük a szerző adatait az API-tól
        $response = $this->api->get("/writers/{$id}");

        if ($response->successful()) {
            $writer = $response->json();
            return view('writers.edit', compact('writer'));
        }

        return back()->withErrors(['api_error' => 'A szerző nem található vagy hiba történt.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'portrait_path' => 'nullable|image|max:2048',
        ]);

        $files = [];
        if ($request->hasFile('portrait_path')) {
            $files['portrait_path'] = $request->file('portrait_path');
        }
        
        if (!empty($files)) {
            $data = $request->except('portrait_path');
            $data['_method'] = 'PATCH';
            $response = $this->api->post("/writers/{$id}", $data, $files);
        } else {

            $response = $this->api->patch("/writers/{$id}", $request->except('portrait_path'));
        }

        if ($response->successful()) {
            return redirect()->route('writers.index')->with('success', 'Szerző sikeresen frissítve!');
        }

        return back()->withErrors(['api_error' => 'Hiba történt a frissítés során.'])->withInput();
    }

    public function destroy($id)
    {
        $response = $this->api->delete("/writers/{$id}");

        if ($response->successful()) {
            return redirect()->route('writers.index')->with('success', 'Szerző törölve.');
        }

        return back()->withErrors(['api_error' => 'Hiba a törlésnél.']);
    }
}
