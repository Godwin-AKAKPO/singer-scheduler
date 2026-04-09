<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            background: #fff;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .verse {
            text-align: center;
            font-style: italic;
            font-size: 8px;
            color: #9ca3af;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background-color: #1d4ed8;
            color: #fff;
        }

        thead th {
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .date-cell {
            font-weight: bold;
            color: #1d4ed8;
            white-space: nowrap;
        }

        .culte-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1d4ed8;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }

        .culte-badge.c2 {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .role-label {
            color: #6b7280;
            font-size: 8px;
        }

        .empty {
            color: #d1d5db;
        }

        .footer {
            margin-top: 14px;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }

        .dieu-eleve {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            color: #1d4ed8;
            margin-top: 6px;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Groupe Musical Salem Singers (EEAD-TU)</h1>
        <p>Programmation des cultes — {{ $moisNom }} {{ $session->annee }}</p>
    </div>

    <div class="verse">
        Hébreux 13 v 15 : Par lui, offrons sans cesse à Dieu un sacrifice de louange,
        c'est-à-dire le fruit de lèvres qui confessent son nom
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:9%">Date</th>
                <th style="width:5%">Culte</th>
                <th style="width:9%">Lead</th>
                <th style="width:14%">Chœur P1</th>
                <th style="width:9%">Chœur P2</th>
                <th style="width:9%">Chœur P3</th>
                <th style="width:9%">Piano 1</th>
                <th style="width:9%">Piano 2</th>
                <th style="width:9%">Solo</th>
                <th style="width:9%">Basse</th>
                <th style="width:9%">Batterie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dimanches as $dimanche)
                @php
                    $dateFormatee = \Carbon\Carbon::parse($dimanche)->locale('fr')->isoFormat('ddd D MMM');
                    $c1 = collect($programmation)->firstWhere(fn($p) => $p['date'] === $dimanche && $p['culte'] === 'C1');
                    $c2 = collect($programmation)->firstWhere(fn($p) => $p['date'] === $dimanche && $p['culte'] === 'C2');
                @endphp

                {{-- Culte 1 --}}
                <tr>
                    <td class="date-cell" rowspan="2">{{ $dateFormatee }}</td>
                    <td><span class="culte-badge">C1</span></td>
                    <td>{{ $c1['lead'][0] ?? '—' }}</td>
                    <td>{{ implode(', ', $c1['choeur']['p1'] ?? []) ?: '—' }}</td>
                    <td>{{ $c1['choeur']['p2'][0] ?? '—' }}</td>
                    <td>{{ $c1['choeur']['p3'][0] ?? '—' }}</td>
                    <td>{{ $c1['piano1'][0] ?? '—' }}</td>
                    <td>{{ $c1['piano2'][0] ?? '—' }}</td>
                    <td>{{ $c1['solo'][0] ?? '—' }}</td>
                    <td>{{ $c1['basse'][0] ?? '—' }}</td>
                    <td>{{ $c1['batterie'][0] ?? '—' }}</td>
                </tr>

                {{-- Culte 2 --}}
                <tr>
                    <td><span class="culte-badge c2">C2</span></td>
                    <td>{{ $c2['lead'][0] ?? '—' }}</td>
                    <td>{{ implode(', ', $c2['choeur']['p1'] ?? []) ?: '—' }}</td>
                    <td>{{ $c2['choeur']['p2'][0] ?? '—' }}</td>
                    <td>{{ $c2['choeur']['p3'][0] ?? '—' }}</td>
                    <td>{{ $c2['piano1'][0] ?? '—' }}</td>
                    <td>{{ $c2['piano2'][0] ?? '—' }}</td>
                    <td>{{ $c2['solo'][0] ?? '—' }}</td>
                    <td>{{ $c2['basse'][0] ?? '—' }}</td>
                    <td>{{ $c2['batterie'][0] ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dieu-eleve">DIEU ÉLÈVE SALEM SINGERS !!!</div>

    <div class="footer">
        Généré automatiquement par Singer Scheduler
    </div>

</body>
</html>