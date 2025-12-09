<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Szerzők Listája</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif; /* Fontos a magyar ékezetekhez! */
            margin-top: 120px; /* Hely a fejlécnek */
            margin-bottom: 60px; /* Hely a láblécnek */
        }
        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .logo {
            height: 60px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
        <h2>KönyvKlub - Szerzők Listája</h2>
    </header>

    <footer>
        <p>Generálva: {{ date('Y. m. d. H:i') }} | KönyvKlub API Kliens</p>
    </footer>

    <main>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Név</th>
                    <th>Biográfia</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($writers as $writer)
                    <tr>
                        <td>{{ $writer['id'] }}</td>
                        <td>{{ $writer['name'] }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($writer['bio'] ?? '', 100) }}</td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>