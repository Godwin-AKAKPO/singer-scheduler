<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1f2937;
            background: #ffffff;
            padding: 20px 24px;
        }

        .header {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .org {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .verse {
            margin-top: 6px;
            font-style: italic;
            font-size: 10px;
            color: #9ca3af;
        }

        /* ── Tableau ── */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #111827;
        }

        .sep td {
            padding: 13px 8px 5px;
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            border-top: 1px solid #d1fae5;
        }

        .sep:first-child td {
            border-top: none;
            padding-top: 6px;
        }

        .row-culte td {
            padding: 8px 8px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }

        .row-culte.c2 td {
            background: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-c1 { background: #059669; color: #ffffff; }
        .badge-c2 { background: #f3f4f6; color: #374151; }

        /* ── Page 2 : Principes + Footer ── */
        .page-break {
            page-break-before: always;
        }

        .principes {
            padding-top: 40px;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.8;
        }

        .principes strong {
            color: #374151;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 8px;
        }

        .principes ol { padding-left: 18px; }
        .principes li { margin-bottom: 6px; }

        .footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .slogan {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .genere { font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>

    <!-- En-tête compact -->
    <div class="header">
        <div class="org">Groupe Musical Salem Singers · EEAD-TU · Programmation des cultes · {{ $moisNom }} {{ $session->annee }}</div>
        <div class="verse">Hébreux 13 v 15 — Par lui, offrons sans cesse à Dieu un sacrifice de louange, c'est-à-dire le fruit de lèvres qui confessent son nom.</div>
    </div>

    <!-- Tableau occupe tout le reste de la page 1 -->
    <table>
        <thead>
            <tr>
                <th style="width:5%"></th>
                <th style="width:9%">Lead</th>
                <th style="width:16%">Sopra</th>
                <th style="width:9%">Alto</th>
                <th style="width:9%">Ténor</th>
                <th style="width:9%">Piano 1</th>
                <th style="width:9%">Piano 2</th>
                <th style="width:8%">Solo</th>
                <th style="width:9%">Basse</th>
                <th style="width:9%">Batterie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dimanches as $dimanche)
                @php
                    $date = \Carbon\Carbon::parse($dimanche)->locale('fr');
                    $dateFormatee = $date->isoFormat('D MMMM');
                    $c1 = collect($programmation)->firstWhere(fn($p) => $p['date'] === $dimanche && $p['culte'] === 'C1');
                    $c2 = collect($programmation)->firstWhere(fn($p) => $p['date'] === $dimanche && $p['culte'] === 'C2');
                    $val  = fn($v) => $v ?: '—';
                    $vals = fn($arr) => !empty(array_filter($arr ?? [])) ? implode(', ', array_filter($arr)) : '—';
                @endphp

                <tr class="sep"><td colspan="10">{{ $dateFormatee }}</td></tr>

                <tr class="row-culte c1">
                    <td><span class="badge badge-c1">C1</span></td>
                    <td>{{ $val($c1['lead'][0] ?? null) }}</td>
                    <td>{{ $vals($c1['choeur']['sopra'] ?? []) }}</td>
                    <td>{{ $val($c1['choeur']['alto'][0] ?? null) }}</td>
                    <td>{{ $val($c1['choeur']['tenor'][0] ?? null) }}</td>
                    <td>{{ $val($c1['piano1'][0] ?? null) }}</td>
                    <td>{{ $val($c1['piano2'][0] ?? null) }}</td>
                    <td>{{ $val($c1['solo'][0] ?? null) }}</td>
                    <td>{{ $val($c1['basse'][0] ?? null) }}</td>
                    <td>{{ $val($c1['batterie'][0] ?? null) }}</td>
                </tr>

                <tr class="row-culte c2">
                    <td><span class="badge badge-c2">C2</span></td>
                    <td>{{ $val($c2['lead'][0] ?? null) }}</td>
                    <td>{{ $vals($c2['choeur']['sopra'] ?? []) }}</td>
                    <td>{{ $val($c2['choeur']['alto'][0] ?? null) }}</td>
                    <td>{{ $val($c2['choeur']['tenor'][0] ?? null) }}</td>
                    <td>{{ $val($c2['piano1'][0] ?? null) }}</td>
                    <td>{{ $val($c2['piano2'][0] ?? null) }}</td>
                    <td>{{ $val($c2['solo'][0] ?? null) }}</td>
                    <td>{{ $val($c2['basse'][0] ?? null) }}</td>
                    <td>{{ $val($c2['batterie'][0] ?? null) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Page 2 : Principes + Footer -->
    <div class="page-break">
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
        </div>
    </div>

</body>
</html>