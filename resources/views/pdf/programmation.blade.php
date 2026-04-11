<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1f2937;
            background: #ffffff;
            padding: 28px 32px;
        }

        /* ── En-tête ── */
        .header {
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .org {
            font-size: 8px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .titre-ligne {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
        }

        .titre {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }

        .mois {
            font-size: 13px;
            font-weight: bold;
            color: #059669;
        }

        .verse {
            margin-top: 8px;
            font-style: italic;
            font-size: 7.5px;
            color: #9ca3af;
        }

        /* ── Tableau ── */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 7.5px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1.5px solid #111827;
        }

        /* Séparateur de dimanche */
        .sep td {
            padding: 10px 6px 4px;
            font-size: 8px;
            font-weight: bold;
            color: #059669;
            border-top: 1px solid #d1fae5;
        }

        .sep:first-child td {
            border-top: none;
            padding-top: 6px;
        }

        /* Lignes culte */
        .row-culte td {
            padding: 5px 6px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 9px;
        }

        .row-culte.c2 td {
            background: #fafafa;
            border-bottom: 1px solid #eeeeee;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
        }

        .badge-c1 {
            background: #059669;
            color: #ffffff;
        }

        .badge-c2 {
            background: #f3f4f6;
            color: #374151;
        }

        .vide { color: #d1d5db; }

        /* ── Pied ── */
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .slogan {
            font-size: 9px;
            font-weight: bold;
            color: #059669;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .generé {
            font-size: 7px;
            color: #9ca3af;
        }

        /* ── Principes ── */
        .principes {
            margin-top: 14px;
            font-size: 7.5px;
            color: #6b7280;
            line-height: 1.6;
        }

        .principes strong {
            color: #374151;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .principes ol {
            padding-left: 12px;
            margin-top: 4px;
        }

        .principes li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="org">Groupe Musical Salem Singers · EEAD-TU</div>
        <div class="titre-ligne">
            <div class="titre">Programmation des cultes</div>
            <div class="mois">{{ $moisNom }} {{ $session->annee }}</div>
        </div>
        <div class="verse">
            Hébreux 13 v 15 — Par lui, offrons sans cesse à Dieu un sacrifice de louange, c'est-à-dire le fruit de lèvres qui confessent son nom.
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%"></th>
                <th style="width:9%">Lead</th>
                <th style="width:16%">Choeur P1</th>
                <th style="width:9%">Choeur P2</th>
                <th style="width:9%">Choeur P3</th>
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
                    $date = \Carbon\Carbon::parse($dimanche)->locale('fr');
                    $dateFormatee = $date->isoFormat('dddd D MMMM');
                    $c1 = collect($programmation)->firstWhere(fn($p) => $p['date'] === $dimanche && $p['culte'] === 'C1');
                    $c2 = collect($programmation)->firstWhere(fn($p) => $p['date'] === $dimanche && $p['culte'] === 'C2');
                @endphp

                <tr class="sep">
                    <td colspan="10">{{ $dateFormatee }}</td>
                </tr>

                <tr class="row-culte c1">
                    <td><span class="badge badge-c1">C1</span></td>
                    <td>{{ $c1['lead'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ implode(', ', $c1['choeur']['p1'] ?? []) ?: '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['choeur']['p2'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['choeur']['p3'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['piano1'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['piano2'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['solo'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['basse'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c1['batterie'][0] ?? '<span class="vide">—</span>' }}</td>
                </tr>

                <tr class="row-culte c2">
                    <td><span class="badge badge-c2">C2</span></td>
                    <td>{{ $c2['lead'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ implode(', ', $c2['choeur']['p1'] ?? []) ?: '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['choeur']['p2'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['choeur']['p3'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['piano1'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['piano2'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['solo'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['basse'][0] ?? '<span class="vide">—</span>' }}</td>
                    <td>{{ $c2['batterie'][0] ?? '<span class="vide">—</span>' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="principes">
        <strong>Principes</strong>
        <ol>
            <li>Le forum est actualisé au plus tard le Mercredi. Le lead partage son répertoire au plus tard le Mercredi soir.</li>
            <li>Les COs se réservent le droit de remplacer les membres irréguliers aux répétitions, sans motifs valables.</li>
            <li>Les membres ne venant aux cultes que lorsqu'ils sont programmés sont progressivement retirés des programmations.</li>
        </ol>
    </div>

    <div class="footer">
        <div class="slogan">Dieu élève Salem Singers !!!</div>
        <div class="generé">Généré par Singer Scheduler</div>
    </div>

</body>
</html>