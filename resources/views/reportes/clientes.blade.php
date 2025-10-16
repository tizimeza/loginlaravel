<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Clientes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #6f42c1;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #6f42c1;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        .stat-box h3 {
            color: #6f42c1;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .stat-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .stat-box .label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #6f42c1;
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #5a32a3;
            font-size: 11px;
        }
        table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            font-size: 10px;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #000;
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
            background-color: #6f42c1;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            margin: 20px 0 10px 0;
            border-radius: 3px;
        }
        .cliente-destacado {
            background-color: #fff3cd !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE CLIENTES</h1>
        <p>TecnoServi - Sistema de Gestión de Clientes</p>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Clientes</h3>
            <div class="value">{{ $stats['total_clientes'] }}</div>
            <div class="label">Clientes registrados</div>
        </div>
        <div class="stat-box">
            <h3>Clientes Activos</h3>
            <div class="value">{{ $stats['clientes_activos'] }}</div>
            <div class="label">Con estado activo</div>
        </div>
        <div class="stat-box">
            <h3>Empresas</h3>
            <div class="value">{{ $stats['clientes_premium'] }}</div>
            <div class="label">Clientes corporativos</div>
        </div>
    </div>

    <div class="section-title">LISTADO COMPLETO DE CLIENTES</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 20%;">Nombre</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 12%;">Teléfono</th>
                <th style="width: 10%;">Tipo</th>
                <th style="width: 8%;">Órdenes</th>
                <th style="width: 15%;">Última Orden</th>
                <th style="width: 8%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $index => $cliente)
            @php
                $ordenes = $cliente->ordenesTrabajo ?? collect();
                $totalOrdenes = $ordenes->count();
                $ultimaOrden = $ordenes->sortByDesc('created_at')->first();
                $isDestacado = $totalOrdenes > 5;
            @endphp
            <tr class="{{ $isDestacado ? 'cliente-destacado' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $cliente->nombre }}</strong></td>
                <td>{{ $cliente->email ?? 'N/A' }}</td>
                <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                <td class="text-center">
                    @if($cliente->tipo_cliente == 'empresa')
                        <span class="badge badge-info">EMPRESA</span>
                    @else
                        <span class="badge badge-secondary">PARTICULAR</span>
                    @endif
                </td>
                <td class="text-center"><strong>{{ $totalOrdenes }}</strong></td>
                <td class="text-center">
                    {{ $ultimaOrden ? $ultimaOrden->created_at->format('d/m/Y') : 'Sin órdenes' }}
                </td>
                <td class="text-center">
                    @if($cliente->activo ?? true)
                        <span class="badge badge-success">ACTIVO</span>
                    @else
                        <span class="badge badge-secondary">INACTIVO</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">DETALLE DE CONTACTO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 15%;">Teléfono</th>
                <th style="width: 40%;">Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes->where('activo', true) as $cliente)
            <tr>
                <td><strong>{{ $cliente->nombre }}</strong></td>
                <td>{{ $cliente->email ?? 'Sin email' }}</td>
                <td>{{ $cliente->telefono ?? 'Sin teléfono' }}</td>
                <td>{{ $cliente->direccion ?? 'Sin dirección registrada' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">TOP 10 CLIENTES (Por cantidad de órdenes)</div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Posición</th>
                <th style="width: 30%;">Cliente</th>
                <th style="width: 15%;">Tipo</th>
                <th style="width: 15%;">Total Órdenes</th>
                <th style="width: 17%;">Última Orden</th>
                <th style="width: 15%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $topClientes = $clientes->sortByDesc(function($cliente) {
                    return $cliente->ordenesTrabajo->count();
                })->take(10);
            @endphp
            @foreach($topClientes as $index => $cliente)
            @php
                $totalOrdenes = $cliente->ordenesTrabajo->count();
                $ultimaOrden = $cliente->ordenesTrabajo->sortByDesc('created_at')->first();
            @endphp
            <tr>
                <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                <td><strong>{{ $cliente->nombre }}</strong></td>
                <td class="text-center">
                    @if($cliente->tipo_cliente == 'empresa')
                        <span class="badge badge-info">EMPRESA</span>
                    @else
                        <span class="badge badge-secondary">PARTICULAR</span>
                    @endif
                </td>
                <td class="text-center"><strong style="font-size: 12px;">{{ $totalOrdenes }}</strong></td>
                <td class="text-center">
                    {{ $ultimaOrden ? $ultimaOrden->created_at->format('d/m/Y') : 'N/A' }}
                </td>
                <td class="text-center">
                    @if($cliente->activo ?? true)
                        <span class="badge badge-success">ACTIVO</span>
                    @else
                        <span class="badge badge-secondary">INACTIVO</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">RESUMEN POR TIPO DE CLIENTE</div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Tipo de Cliente</th>
                <th style="width: 25%;">Cantidad</th>
                <th style="width: 25%;">Porcentaje</th>
                <th style="width: 20%;">Órdenes Totales</th>
            </tr>
        </thead>
        <tbody>
            @php
                $empresas = $clientes->where('tipo_cliente', 'empresa');
                $particulares = $clientes->where('tipo_cliente', '!=', 'empresa');
                $totalClientes = $clientes->count() > 0 ? $clientes->count() : 1;
            @endphp
            <tr>
                <td><span class="badge badge-info">EMPRESA</span></td>
                <td class="text-center"><strong>{{ $empresas->count() }}</strong></td>
                <td class="text-center">{{ number_format(($empresas->count() / $totalClientes) * 100, 1) }}%</td>
                <td class="text-center">{{ $empresas->sum(function($c) { return $c->ordenesTrabajo->count(); }) }}</td>
            </tr>
            <tr>
                <td><span class="badge badge-secondary">PARTICULAR</span></td>
                <td class="text-center"><strong>{{ $particulares->count() }}</strong></td>
                <td class="text-center">{{ number_format(($particulares->count() / $totalClientes) * 100, 1) }}%</td>
                <td class="text-center">{{ $particulares->sum(function($c) { return $c->ordenesTrabajo->count(); }) }}</td>
            </tr>
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-center">{{ $clientes->count() }}</td>
                <td class="text-center">100%</td>
                <td class="text-center">{{ $clientes->sum(function($c) { return $c->ordenesTrabajo->count(); }) }}</td>
            </tr>
        </tbody>
    </table>

    @if($clientes->where('activo', false)->count() > 0)
    <div class="section-title">CLIENTES INACTIVOS</div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Nombre</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 15%;">Teléfono</th>
                <th style="width: 15%;">Última Orden</th>
                <th style="width: 20%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes->where('activo', false) as $cliente)
            @php
                $ultimaOrden = $cliente->ordenesTrabajo->sortByDesc('created_at')->first();
            @endphp
            <tr>
                <td><strong>{{ $cliente->nombre }}</strong></td>
                <td>{{ $cliente->email ?? 'N/A' }}</td>
                <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                <td class="text-center">
                    {{ $ultimaOrden ? $ultimaOrden->created_at->format('d/m/Y') : 'Sin órdenes' }}
                </td>
                <td>{{ $cliente->observaciones ?? 'Sin observaciones' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p><strong>TecnoServi</strong> - Sistema de Gestión Profesional</p>
        <p>Documento confidencial - Uso interno exclusivamente</p>
        <p>Los clientes destacados (fondo amarillo) tienen más de 5 órdenes de trabajo</p>
    </div>
</body>
</html>
