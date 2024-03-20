<!-- resources/views/dates.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Dates</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
   
</head>
<body>
    <h1>Liste des Dates</h1>
    
    <ul>
        @foreach ($dates as $index => $date)
            <li>
                <a href="#" class="date-link" data-index="{{ $index }}">{{ $date }}</a> <!-- Ajoute un attribut de données avec l'index de l'itération -->
                <!-- Ajoute la liste des horaires avec un identifiant unique -->
                <ul class="horaires" id="horaires-{{ $index }}">
                    <li>16:00</li>
                    <li>16:30</li>
                    <li>17:00</li>
                    <li>17:30</li>
                    <li>18:00</li>
                    <li><a href="{{ route('welcome') }}">s'inscrire sur la liste d'attente</a></li>
                </ul>
                
            </li>
        @endforeach
    </ul>
</body>
</html>
