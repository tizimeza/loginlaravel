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
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
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
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        .stat-box h3 {
            color: #007bff;
            font-size: 12px;
            margin-bottom: 8px;
        }
        .stat-box .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .stat-box .label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        .section-title {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            margin: 20px 0 10px 0;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #0056b3;
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
        .info-table td {
            padding: 6px 10px;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 30%;
            background-color: #e9ecef;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
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
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-primary {
            background-color: #007bff;
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
        .descripcion-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 10px 0;
            font-size: 11px;
            line-height: 1.6;
        }
        .observaciones-box {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 12px;
            margin: 10px 0;
            font-size: 11px;
            line-height: 1.6;
        }
        .total-section {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            border: 2px solid #007bff;
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
            font-size: 12px;
        }
        .total-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ORDEN DE TRABAJO</h1>
        <p>TecnoServi - Sistema de Gestión de Servicios Técnicos</p>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Nº Orden</h3>
            <div class="value">{{ $orden->numero_orden }}</div>
            <div class="label">Número de Orden</div>
        </div>
        <div class="stat-box">
            <h3>Estado</h3>
            <div class="value">
                @php
                    $colorEstado = match($orden->estado) {
                        'pendiente' => 'secondary',
                        'en_proceso' => 'warning',
                        'esperando_repuestos' => 'info',
                        'completado' => 'primary',
                        'entregado' => 'success',
                        'cancelado' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge badge-{{ $colorEstado }}">{{ $orden->estado_formateado }}</span>
            </div>
            <div class="label">Estado Actual</div>
        </div>
        <div class="stat-box">
            <h3>Prioridad</h3>
            <div class="value">
                @php
                    $colorPrioridad = match($orden->prioridad) {
                        'baja' => 'success',
                        'media' => 'info',
                        'alta' => 'warning',
                        'urgent' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge badge-{{ $colorPrioridad }}">{{ $orden->prioridad_formateada }}</span>
            </div>
            <div class="label">Nivel de Prioridad</div>
        </div>
        <div class="stat-box">
            <h3>Tipo Servicio</h3>
            <div class="value">
                <span class="badge badge-info">{{ $orden->tipo_servicio_formateado }}</span>
            </div>
            <div class="label">Tipo de Servicio</div>
        </div>
    </div>

    <div class="section-title">INFORMACIÓN DE LA ORDEN</div>
    <table class="info-table">
        <tr>
            <td>Fecha de Ingreso:</td>
            <td>{{ $orden->fecha_ingreso ? $orden->fecha_ingreso->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
        <tr>
            <td>Fecha Estimada de Entrega:</td>
            <td>{{ $orden->fecha_estimada_entrega ? $orden->fecha_estimada_entrega->format('d/m/Y H:i') : 'Sin fecha estimada' }}</td>
        </tr>
        @if($orden->fecha_entrega_real)
        <tr>
            <td>Fecha Real de Entrega:</td>
            <td>{{ $orden->fecha_entrega_real->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        @if($orden->fecha_asignacion)
        <tr>
            <td>Fecha de Asignación:</td>
            <td>{{ $orden->fecha_asignacion->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        @if($orden->fecha_finalizacion)
        <tr>
            <td>Fecha de Finalización:</td>
            <td>{{ $orden->fecha_finalizacion->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        <tr>
            <td>Horario Preferido:</td>
            <td>{{ $orden->horario_preferido ?? 'Sin especificar' }}</td>
        </tr>
    </table>

    <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
    <table class="info-table">
        <tr>
            <td>Cliente:</td>
            <td>
                @if($orden->cliente)
                    <strong>{{ $orden->cliente->nombre }}</strong>
                    @if($orden->es_cliente_premium)
                        <span class="badge badge-warning">PREMIUM</span>
                    @endif
                @else
                    Sin información de cliente
                @endif
            </td>
        </tr>
        <tr>
            <td>Teléfono:</td>
            <td>
                @if($orden->cliente && $orden->cliente->telefono)
                    {{ $orden->cliente->telefono }}
                @elseif($orden->cliente_telefono)
                    {{ $orden->cliente_telefono }}
                @else
                    No proporcionado
                @endif
            </td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>
                @if($orden->cliente && $orden->cliente->email)
                    {{ $orden->cliente->email }}
                @elseif($orden->cliente_email)
                    {{ $orden->cliente_email }}
                @else
                    No proporcionado
                @endif
            </td>
        </tr>
        @if($orden->cliente && $orden->cliente->direccion)
        <tr>
            <td>Dirección:</td>
            <td>{{ $orden->cliente->direccion }}</td>
        </tr>
        @endif
        @if($orden->telefono_contacto)
        <tr>
            <td>Teléfono de Contacto:</td>
            <td>{{ $orden->telefono_contacto }}</td>
        </tr>
        @endif
    </table>

    @if($orden->vehiculo)
    <div class="section-title">VEHÍCULO ASIGNADO</div>
    <table class="info-table">
        <tr>
            <td>Patente:</td>
            <td><strong>{{ $orden->vehiculo->patente }}</strong></td>
        </tr>
        @if($orden->vehiculo->marca && $orden->vehiculo->modelo)
        <tr>
            <td>Marca y Modelo:</td>
            <td>{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</td>
        </tr>
        @endif
        @if($orden->vehiculo->color)
        <tr>
            <td>Color:</td>
            <td>{{ $orden->vehiculo->color }}</td>
        </tr>
        @endif
        @if($orden->vehiculo->anio)
        <tr>
            <td>Año:</td>
            <td>{{ $orden->vehiculo->anio }}</td>
        </tr>
        @endif
    </table>
    @endif

    <div class="section-title">PERSONAL ASIGNADO</div>
    <table class="info-table">
        <tr>
            <td>Técnico Asignado:</td>
            <td>
                @if($orden->tecnico)
                    <strong>{{ $orden->tecnico->name }}</strong>
                    @if($orden->tecnico->email)
                        <br><small>{{ $orden->tecnico->email }}</small>
                    @endif
                @else
                    <span style="color: #999;">Sin asignar</span>
                @endif
            </td>
        </tr>
        @if($orden->grupoTrabajo)
        <tr>
            <td>Grupo de Trabajo:</td>
            <td>
                <strong>{{ $orden->grupoTrabajo->nombre }}</strong>
                @if($orden->grupoTrabajo->descripcion)
                    <br><small>{{ $orden->grupoTrabajo->descripcion }}</small>
                @endif
            </td>
        </tr>
        @endif
    </table>

    @if($orden->descripcion_problema)
    <div class="section-title">DESCRIPCIÓN DEL PROBLEMA</div>
    <div class="descripcion-box">
        {{ $orden->descripcion_problema }}
    </div>
    @endif

    @if($orden->tareas && $orden->tareas->count() > 0)
    <div class="section-title">TAREAS ASIGNADAS</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Tarea</th>
                <th style="width: 15%;">Tipo</th>
                <th style="width: 15%;">Estado</th>
                <th style="width: 20%;">Asignado a</th>
                <th style="width: 10%;">Completada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orden->tareas as $index => $tarea)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $tarea->nombre }}</strong>
                    @if($tarea->observaciones)
                        <br><small style="color: #666;">{{ $tarea->observaciones }}</small>
                    @endif
                </td>
                <td><span class="badge badge-info">{{ ucfirst($tarea->tipo) }}</span></td>
                <td>
                    @php
                        $colorTarea = match($tarea->estado) {
                            'pendiente' => 'secondary',
                            'en_proceso' => 'warning',
                            'completada' => 'success',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge badge-{{ $colorTarea }}">{{ ucfirst($tarea->estado) }}</span>
                </td>
                <td>{{ $tarea->empleado ? $tarea->empleado->name : 'Sin asignar' }}</td>
                <td class="text-center">{{ $tarea->completada ? 'Sí' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($orden->observaciones)
    <div class="section-title">OBSERVACIONES</div>
    <div class="observaciones-box">
        {{ $orden->observaciones }}
    </div>
    @endif

    @if($orden->motivo_no_terminada)
    <div class="section-title">MOTIVO DE NO FINALIZACIÓN</div>
    <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 12px; margin: 10px 0; font-size: 11px;">
        {{ $orden->motivo_no_terminada }}
    </div>
    @endif

    @if($orden->costo_estimado || $orden->costo_final)
    <div class="section-title">INFORMACIÓN DE COSTOS</div>
    <div class="total-section">
        @if($orden->costo_estimado)
        <div class="total-row">
            <div class="total-label">Costo Estimado:</div>
            <div class="total-value">$ {{ number_format($orden->costo_estimado, 2) }}</div>
        </div>
        @endif
        @if($orden->costo_final)
        <div class="total-row" style="border-top: 2px solid #007bff; padding-top: 10px; margin-top: 10px;">
            <div class="total-label" style="color: #007bff; font-size: 14px;">COSTO FINAL:</div>
            <div class="total-value" style="color: #007bff; font-size: 18px;">$ {{ number_format($orden->costo_final, 2) }}</div>
        </div>
        @endif
    </div>
    @endif

    @if($orden->coordenadas_gps)
    <div class="section-title">UBICACIÓN GPS</div>
    <table class="info-table">
        <tr>
            <td>Coordenadas GPS:</td>
            <td>{{ $orden->coordenadas_gps }}</td>
        </tr>
    </table>
    @endif

    <div class="footer">
        <p><strong>TecnoServi</strong> - Sistema de Gestión Profesional de Servicios Técnicos</p>
        <p>Documento generado automáticamente - Para consultas contacte al departamento de servicios</p>
        <p>Este documento es una representación oficial de la orden de trabajo #{{ $orden->numero_orden }}</p>
    </div>
</body>
</html>
