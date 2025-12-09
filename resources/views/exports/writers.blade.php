<!DOCTYPE html>
<html>
<head>
    <style>
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 50px; text-align: center; }
        footer { position: fixed; bottom: 0px; left: 0px; right: 0px; height: 30px; text-align: center; }
        body { margin-top: 60px; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('logo.png') }}" height="40px">
        <h1>Szerzők Listája</h1>
    </header>

    <footer>
        Generálva: {{ date('Y-m-d') }}
    </footer>

    <table>
        </table>
</body>
</html>