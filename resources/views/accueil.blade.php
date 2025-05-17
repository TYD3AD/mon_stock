<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon stock</title>
    <style>
        .rouge { background-color: #f8d7da; }
        .jaune { background-color: #fff3cd; }
        .vert { background-color: #d4edda; }
        .noir { background-color: #d6d6d6; color: white; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: left; }
        header { background-color: #007bff; padding: 1rem; color: white; }
        header nav a { color: white; margin-right: 1rem; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="{{ route('accueil') }}">Mon stock</a>
        <a href="#">Consommables opérationnels</a>
        <a href="#">Ma pharmacie</a>
    </nav>
</header>

<main>
    <h2>Consommables proches de la péremption</h2>
    <table>
        <thead>
        <tr>
            <th>Produit</th>
            <th>Zone</th>
            <th>Quantité</th>
            <th>Date de péremption</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($stocks as $stock)
        <tr class="{{ $stock->couleur }}">
            <td>{{ $stock->produit->nom }}</td>
            <td>{{ $stock->zoneStock->nom }}</td>
            <td>{{ $stock->quantite }}</td>
            <td>{{ \Carbon\Carbon::parse($stock->date_peremption)->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Aucun consommable enregistré avec date de péremption</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</main>

</body>
</html>
