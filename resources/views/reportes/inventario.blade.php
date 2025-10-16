<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
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
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #28a745;
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
            color: #28a745;
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
            background-color: #28a745;
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #1e7e34;
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
        .stock-bajo {
            background-color: #fff3cd !important;
        }
        .stock-critico {
            background-color: #f8d7da !important;
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
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        .badge-danger {
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
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            margin: 20px 0 10px 0;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE INVENTARIO</h1>
        <p>TecnoServi - Sistema de Gestión de Stock</p>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Productos</h3>
            <div class="value">{{ $stats['total_productos'] }}</div>
            <div class="label">Productos registrados</div>
        </div>
        <div class="stat-box">
            <h3>Valor Total</h3>
            <div class="value">$ {{ number_format($stats['valor_total'], 2) }}</div>
            <div class="label">Valor del inventario</div>
        </div>
        <div class="stat-box">
            <h3>Stock Bajo</h3>
            <div class="value">{{ $stats['productos_bajo_stock'] }}</div>
            <div class="label">Productos bajo stock mínimo</div>
        </div>
    </div>

    <div class="section-title">DETALLE DE INVENTARIO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Producto</th>
                <th style="width: 15%;">Categoría</th>
                <th style="width: 10%;">Código</th>
                <th style="width: 8%;">Stock</th>
                <th style="width: 8%;">Mínimo</th>
                <th style="width: 10%;">P. Unitario</th>
                <th style="width: 12%;">Valor Total</th>
                <th style="width: 7%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $index => $producto)
            @php
                $stockMinimo = $producto->cantidad_minima ?? 0;
                $cantidad = $producto->cantidad_actual ?? 0;
                $valorTotal = $cantidad * ($producto->precio_venta ?? 0);

                $rowClass = '';
                $badgeClass = 'badge-success';
                $estadoText = 'OK';

                if ($cantidad == 0) {
                    $rowClass = 'stock-critico';
                    $badgeClass = 'badge-danger';
                    $estadoText = 'AGOTADO';
                } elseif ($cantidad < $stockMinimo) {
                    $rowClass = 'stock-bajo';
                    $badgeClass = 'badge-warning';
                    $estadoText = 'BAJO';
                }
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $producto->nombre }}</strong></td>
                <td>{{ $producto->categoria ?? 'Sin categoría' }}</td>
                <td>{{ $producto->codigo ?? 'N/A' }}</td>
                <td class="text-center">{{ $cantidad }}</td>
                <td class="text-center">{{ $stockMinimo }}</td>
                <td class="text-right">$ {{ number_format($producto->precio_venta ?? 0, 2) }}</td>
                <td class="text-right"><strong>$ {{ number_format($valorTotal, 2) }}</strong></td>
                <td class="text-center">
                    <span class="badge {{ $badgeClass }}">{{ $estadoText }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #e9ecef;">
                <td colspan="7" style="text-align: right; font-weight: bold; font-size: 12px;">VALOR TOTAL DEL INVENTARIO:</td>
                <td class="text-right" style="font-weight: bold; font-size: 12px;">$ {{ number_format($stats['valor_total'], 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    @if($stats['productos_bajo_stock'] > 0)
    <div class="section-title">PRODUCTOS CON STOCK BAJO</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Producto</th>
                <th style="width: 15%;">Categoría</th>
                <th style="width: 15%;">Stock Actual</th>
                <th style="width: 15%;">Stock Mínimo</th>
                <th style="width: 15%;">Déficit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                @php
                    $stockMinimo = $producto->cantidad_minima ?? 0;
                    $cantidad = $producto->cantidad_actual ?? 0;
                @endphp
                @if($cantidad < $stockMinimo)
                <tr class="{{ $cantidad == 0 ? 'stock-critico' : 'stock-bajo' }}">
                    <td><strong>{{ $producto->nombre }}</strong></td>
                    <td>{{ $producto->categoria ?? 'Sin categoría' }}</td>
                    <td class="text-center">{{ $cantidad }}</td>
                    <td class="text-center">{{ $stockMinimo }}</td>
                    <td class="text-center"><strong>{{ $stockMinimo - $cantidad }}</strong></td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">RESUMEN POR CATEGORÍA</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Categoría</th>
                <th style="width: 20%;">Cantidad de Productos</th>
                <th style="width: 20%;">Unidades Totales</th>
                <th style="width: 20%;">Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categorias = $productos->groupBy('categoria');
            @endphp
            @foreach($categorias as $categoria => $items)
            <tr>
                <td><strong>{{ $categoria ?? 'Sin categoría' }}</strong></td>
                <td class="text-center">{{ $items->count() }}</td>
                <td class="text-center">{{ $items->sum('cantidad_actual') }}</td>
                <td class="text-right">$ {{ number_format($items->sum(function($p) { return ($p->cantidad_actual ?? 0) * ($p->precio_venta ?? 0); }), 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-center">{{ $productos->count() }}</td>
                <td class="text-center">{{ $productos->sum('cantidad_actual') }}</td>
                <td class="text-right">$ {{ number_format($stats['valor_total'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>TecnoServi</strong> - Sistema de Gestión Profesional</p>
        <p>Documento generado automáticamente - No requiere firma</p>
        <p>Para consultas contacte al departamento de inventario</p>
    </div>
</body>
</html>
