<!DOCTYPE html>
<html>
<head>
    <title>Liste des Membres</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            color: gray;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: rgb(79, 79, 79);
        }
        .missing-documents {
            color: red;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Membres</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date de Création</th>
                    <th>Documents Manquants</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member['firstname'] }} {{ $member['lastname'] }}</td>
                        <td>{{ $member['email'] }}</td>
                        <td>{{ $member['created_at'] }}</td>
                        <td>
                            @if(count($member['documents_missing']) > 0)
                                <span class="missing-documents">
                                    @foreach($member['documents_missing'] as $missingDocument)
                                        {{ $missingDocument }}<br>
                                    @endforeach
                                </span>
                            @else
                                Tous les documents sont présents
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
