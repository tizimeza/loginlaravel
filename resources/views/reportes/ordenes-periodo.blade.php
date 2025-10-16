<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Órdenes por Periodo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #17a2b8;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #17a2b8;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .periodo-info {
            background-color: #e7f6f8;
            padding: 12px;
            border-left: 4px solid #17a2b8;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .periodo-info strong {
            color: #17a2b8;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 20%;
            background-color: #f8f9fa;
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        .stat-box h3 {
            color: #17a2b8;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .stat-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .stat-box .label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #17a2b8;
            color: white;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #117a8b;
            font-size: 10px;
        }
        table td {
            padding: 6px;
            border: 1px solid #dee2e6;
            font-size: 9px;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .estado-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .estado-pendiente {
            background-color: #ffc107;
            color: #000;
        }
        .estado-en_proceso {
            background-color: #17a2b8;
            color: white;
        }
        .estado-completado {
            background-color: #28a745;
            color: white;
        }
        .estado-cancelado {
            background-color: #dc3545;
            color: white;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            background-color: #17a2b8;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            margin: 15px 0 10px 0;
            border-radius: 3px;
        }
        .summary-box {
            background-color: #e7f6f8;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .summary-label {
            display: table-cell;
            width: 70%;
            text-align: right;
            font-weight: bold;
            padding-right: 15px;
        }
        .summary-value {
            display: table-cell;
            width: 30%;
            text-align: right;
        }
        .grand-total {
            font-size: 14px;
            color: #17a2b8;
            border-top: 2px solid #17a2b8;
            padding-top: 8px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ÓRDENES POR PERIODO</h1>
        <p>TecnoServi - Sistema de Gestión de Órdenes de Trabajo</p>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="periodo-info">
        <strong>Periodo consultado:</strong>
        {{ \Carbon\Carbon::parse($request->fecha_desde)->format('d/m/Y') }}
        al
        {{ \Carbon\Carbon::parse($request->fecha_hasta)->format('d/m/Y') }}
        ({{ \Carbon\Carbon::parse($request->fecha_desde)->diffInDays(\Carbon\Carbon::parse($request->fecha_hasta)) }} días)
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>Total</h3>
            <div class="value">{{ $stats['total_ordenes'] }}</div>
            <div class="label">Órdenes</div>
        </div>
        <div class="stat-box">
            <h3>Pendientes</h3>
            <div class="value">{{ $stats['pendientes'] }}</div>
            <div class="label">Sin iniciar</div>
        </div>
        <div class="stat-box">
            <h3>En Proceso</h3>
            <div class="value">{{ $stats['en_proceso'] }}</div>
            <div class="label">Activas</div>
        </div>
        <div class="stat-box">
            <h3>Completadas</h3>
            <div class="value">{{ $stats['completadas'] }}</div>
            <div class="label">Finalizadas</div>
        </div>
        <div class="stat-box">
            <h3>Facturado</h3>
            <div class="value">$ {{ number_format($stats['total_facturado'], 0) }}</div>
            <div class="label">Total periodo</div>
        </div>
    </div>

    <div class="section-title">LISTADO DE ÓRDENES</div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Nº Orden</th>
                <th style="width: 20%;">Cliente</th>
                <th style="width: 15%;">Técnico</th>
                <th style="width: 25%;">Descripción</th>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 8%;">Estado</th>
                <th style="width: 12%;">Costo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordenes as $orden)
            <tr>
                <td><strong>{{ $orden->numero_orden }}</strong></td>
                <td>{{ $orden->cliente->nombre ?? 'Sin cliente' }}</td>
                <td>{{ $orden->tecnico->name ?? 'Sin asignar' }}</td>
                <td>{{ Str::limit($orden->descripcion ?? 'Sin descripción', 60) }}</td>
                <td class="text-center">{{ $orden->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="estado-badge estado-{{ $orden->estado }}">
                        {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                    </span>
                </td>
                <td class="text-right">$ {{ number_format($orden->costo_final ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($ordenes->count() == 0)
    <div style="text-align: center; padding: 30px; background-color: #f8f9fa; border-radius: 5px;">
        <p style="font-size: 12px; color: #666;">No se encontraron órdenes en el periodo seleccionado</p>
    </div>
    @endif

    <div class="section-title">ANÁLISIS POR ESTADO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Estado</th>
                <th style="width: 20%;">Cantidad</th>
                <th style="width: 20%;">Porcentaje</th>
                <th style="width: 30%;">Monto Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = $stats['total_ordenes'] > 0 ? $stats['total_ordenes'] : 1;
                $estados = [
                    'pendiente' => ['cantidad' => $stats['pendientes'], 'monto' => $ordenes->where('estado', 'pendiente')->sum('costo_final')],
                    'en_proceso' => ['cantidad' => $stats['en_proceso'], 'monto' => $ordenes->where('estado', 'en_proceso')->sum('costo_final')],
                    'completado' => ['cantidad' => $stats['completadas'], 'monto' => $ordenes->where('estado', 'completado')->sum('costo_final')],
                ];
            @endphp
            @foreach($estados as $estado => $data)
            <tr>
                <td>
                    <span class="estado-badge estado-{{ $estado }}">
                        {{ ucfirst(str_replace('_', ' ', $estado)) }}
                    </span>
                </td>
                <td class="text-center"><strong>{{ $data['cantidad'] }}</strong></td>
                <td class="text-center">{{ number_format(($data['cantidad'] / $total) * 100, 1) }}%</td>
                <td class="text-right">$ {{ number_format($data['monto'], 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-center">{{ $stats['total_ordenes'] }}</td>
                <td class="text-center">100%</td>
                <td class="text-right">$ {{ number_format($stats['total_facturado'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($ordenes->count() > 0)
    <div class="section-title">RESUMEN POR TÉCNICO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 35%;">Técnico</th>
                <th style="width: 20%;">Órdenes Asignadas</th>
                <th style="width: 20%;">Completadas</th>
                <th style="width: 25%;">Total Facturado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tecnicoStats = $ordenes->groupBy('user_id');
            @endphp
            @foreach($tecnicoStats as $userId => $ordenesDelTecnico)
            @php
                $tecnico = $ordenesDelTecnico->first()->tecnico;
            @endphp
            <tr>
                <td><strong>{{ $tecnico->name ?? 'Sin asignar' }}</strong></td>
                <td class="text-center">{{ $ordenesDelTecnico->count() }}</td>
                <td class="text-center">{{ $ordenesDelTecnico->where('estado', 'completado')->count() }}</td>
                <td class="text-right">$ {{ number_format($ordenesDelTecnico->sum('costo_final'), 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-center">{{ $ordenes->count() }}</td>
                <td class="text-center">{{ $ordenes->where('estado', 'completado')->count() }}</td>
                <td class="text-right">$ {{ number_format($stats['total_facturado'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="summary-box">
        <div class="summary-row">
            <div class="summary-label">Promedio por Orden:</div>
            <div class="summary-value">
                $ {{ $stats['total_ordenes'] > 0 ? number_format($stats['total_facturado'] / $stats['total_ordenes'], 2) : '0.00' }}
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Tasa de Completación:</div>
            <div class="summary-value">
                {{ $stats['total_ordenes'] > 0 ? number_format(($stats['completadas'] / $stats['total_ordenes']) * 100, 1) : '0' }}%
            </div>
        </div>
        <div class="summary-row grand-total">
            <div class="summary-label">FACTURACIÓN TOTAL:</div>
            <div class="summary-value">$ {{ number_format($stats['total_facturado'], 2) }}</div>
        </div>
    </div>

    <div class="footer">
        <p><strong>TecnoServi</strong> - Sistema de Gestión Profesional</p>
        <p>Este reporte es confidencial y de uso interno exclusivamente</p>
        <p>Para consultas contacte al departamento de administración</p>
    </div>
</body>
</html>
