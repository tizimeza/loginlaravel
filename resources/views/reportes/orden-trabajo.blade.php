<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Trabajo - {{ $orden->numero_orden }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .orden-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .orden-info h2 {
            color: #007bff;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            display: table-cell;
            width: 65%;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            margin-bottom: 12px;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #e9ecef;
            color: #333;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
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
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .total-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .total-label {
            display: table-cell;
            width: 70%;
            text-align: right;
            font-weight: bold;
            padding-right: 15px;
        }
        .total-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-size: 14px;
        }
        .grand-total {
            font-size: 18px;
            color: #007bff;
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TECNOSERVI</h1>
        <p>Sistema de Gestión de Órdenes de Trabajo</p>
        <p>Servicios de Internet y Soporte Técnico</p>
    </div>

    <div class="orden-info">
        <h2>ORDEN DE TRABAJO {{ $orden->numero_orden }}</h2>
        <div class="info-row">
            <div class="info-label">Estado:</div>
            <div class="info-value">
                <span class="estado-badge estado-{{ $orden->estado }}">
                    {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Fecha de Creación:</div>
            <div class="info-value">{{ $orden->created_at->format('d/m/Y H:i') }}</div>
        </div>
        @if($orden->fecha_inicio)
        <div class="info-row">
            <div class="info-label">Fecha de Inicio:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($orden->fecha_inicio)->format('d/m/Y H:i') }}</div>
        </div>
        @endif
        @if($orden->fecha_fin)
        <div class="info-row">
            <div class="info-label">Fecha de Finalización:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($orden->fecha_fin)->format('d/m/Y H:i') }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <h3>INFORMACIÓN DEL CLIENTE</h3>
        <table>
            <tr>
                <td style="width: 25%; font-weight: bold;">Nombre:</td>
                <td style="width: 75%;">{{ $orden->cliente->nombre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email:</td>
                <td>{{ $orden->cliente->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Teléfono:</td>
                <td>{{ $orden->cliente->telefono ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Dirección:</td>
                <td>{{ $orden->cliente->direccion ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if($orden->vehiculo)
    <div class="section">
        <h3>VEHÍCULO ASIGNADO</h3>
        <table>
            <tr>
                <td style="width: 25%; font-weight: bold;">Patente:</td>
                <td style="width: 75%;">{{ $orden->vehiculo->patente }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Modelo:</td>
                <td>{{ $orden->vehiculo->modelo->marca->nombre ?? '' }} {{ $orden->vehiculo->modelo->nombre ?? 'N/A' }}</td>
            </tr>
            @if($orden->vehiculo->tipo)
            <tr>
                <td style="font-weight: bold;">Tipo:</td>
                <td>{{ ucfirst($orden->vehiculo->tipo) }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <div class="section">
        <h3>TÉCNICO ASIGNADO</h3>
        <table>
            <tr>
                <td style="width: 25%; font-weight: bold;">Nombre:</td>
                <td style="width: 75%;">{{ $orden->tecnico->name ?? 'Sin asignar' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email:</td>
                <td>{{ $orden->tecnico->email ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if($orden->grupoTrabajo)
    <div class="section">
        <h3>GRUPO DE TRABAJO</h3>
        <table>
            <tr>
                <td style="width: 25%; font-weight: bold;">Nombre del Grupo:</td>
                <td style="width: 75%;">{{ $orden->grupoTrabajo->nombre }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Descripción:</td>
                <td>{{ $orden->grupoTrabajo->descripcion ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="section">
        <h3>DESCRIPCIÓN DEL TRABAJO</h3>
        <table>
            <tr>
                <td>{{ $orden->descripcion ?? 'Sin descripción' }}</td>
            </tr>
        </table>
    </div>

    @if($orden->tareas && $orden->tareas->count() > 0)
    <div class="section">
        <h3>TAREAS REALIZADAS</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Descripción</th>
                    <th style="width: 15%;">Estado</th>
                    <th style="width: 20%;">Fecha Inicio</th>
                    <th style="width: 15%;">Duración</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orden->tareas as $tarea)
                <tr>
                    <td>{{ $tarea->descripcion }}</td>
                    <td>
                        <span class="estado-badge estado-{{ $tarea->estado }}">
                            {{ ucfirst(str_replace('_', ' ', $tarea->estado)) }}
                        </span>
                    </td>
                    <td>{{ $tarea->fecha_inicio ? \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $tarea->duracion_estimada ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <h3>COSTOS</h3>
        <div class="total-section">
            @if($orden->costo_mano_obra)
            <div class="total-row">
                <div class="total-label">Mano de Obra:</div>
                <div class="total-value">$ {{ number_format($orden->costo_mano_obra, 2) }}</div>
            </div>
            @endif
            @if($orden->costo_materiales)
            <div class="total-row">
                <div class="total-label">Materiales:</div>
                <div class="total-value">$ {{ number_format($orden->costo_materiales, 2) }}</div>
            </div>
            @endif
            @if($orden->costo_final)
            <div class="total-row grand-total">
                <div class="total-label">TOTAL:</div>
                <div class="total-value">$ {{ number_format($orden->costo_final, 2) }}</div>
            </div>
            @endif
        </div>
    </div>

    @if($orden->observaciones)
    <div class="section">
        <h3>OBSERVACIONES</h3>
        <table>
            <tr>
                <td>{{ $orden->observaciones }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>TecnoServi - Sistema de Gestión Profesional</p>
        <p>Este documento es una representación oficial de la orden de trabajo</p>
    </div>
</body>
</html>
