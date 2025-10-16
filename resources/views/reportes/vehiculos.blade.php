<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Vehículos</title>
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
            border-bottom: 3px solid #fd7e14;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #fd7e14;
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
            color: #fd7e14;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .stat-box .value {
            font-size: 22px;
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
            background-color: #fd7e14;
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #dc6502;
            font-size: 10px;
        }
        table td {
            padding: 8px 6px;
            border: 1px solid #dee2e6;
            font-size: 9px;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #28a745;
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
            background-color: #fd7e14;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            margin: 20px 0 10px 0;
            border-radius: 3px;
        }
        .alerta-vtv {
            background-color: #fff3cd !important;
        }
        .estado-mantenimiento {
            background-color: #ffe5cc !important;
        }
        .estado-fuera {
            background-color: #f8d7da !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE VEHÍCULOS</h1>
        <p>TecnoServi - Gestión de Flota Vehicular</p>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Vehículos</h3>
            <div class="value">{{ $stats['total_vehiculos'] }}</div>
            <div class="label">Vehículos en flota</div>
        </div>
        <div class="stat-box">
            <h3>Disponibles</h3>
            <div class="value">{{ $vehiculos->where('estado', 'disponible')->count() }}</div>
            <div class="label">Operativos</div>
        </div>
        <div class="stat-box">
            <h3>En Uso</h3>
            <div class="value">{{ $vehiculos->where('estado', 'en_uso')->count() }}</div>
            <div class="label">Asignados</div>
        </div>
        <div class="stat-box">
            <h3>Mantenimiento</h3>
            <div class="value">{{ $vehiculos->where('estado', 'mantenimiento')->count() }}</div>
            <div class="label">En reparación</div>
        </div>
    </div>

    <div class="section-title">LISTADO COMPLETO DE VEHÍCULOS</div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Patente</th>
                <th style="width: 12%;">Marca</th>
                <th style="width: 12%;">Modelo</th>
                <th style="width: 6%;">Año</th>
                <th style="width: 12%;">Tipo</th>
                <th style="width: 10%;">Combustible</th>
                <th style="width: 10%;">Kilometraje</th>
                <th style="width: 10%;">VTV</th>
                <th style="width: 10%;">Capacidad</th>
                <th style="width: 8%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiculos as $vehiculo)
            @php
                $vtvProxima = false;
                if ($vehiculo->fecha_vencimiento_vtv) {
                    $diasParaVtv = now()->diffInDays($vehiculo->fecha_vencimiento_vtv, false);
                    $vtvProxima = $diasParaVtv >= 0 && $diasParaVtv <= 30;
                }

                $rowClass = '';
                if ($vehiculo->estado == 'mantenimiento') {
                    $rowClass = 'estado-mantenimiento';
                } elseif ($vehiculo->estado == 'fuera_servicio') {
                    $rowClass = 'estado-fuera';
                } elseif ($vtvProxima) {
                    $rowClass = 'alerta-vtv';
                }

                // Determinar badge de estado
                $estadoBadge = 'badge-success';
                $estadoTexto = 'DISPONIBLE';
                switch($vehiculo->estado) {
                    case 'en_uso':
                        $estadoBadge = 'badge-info';
                        $estadoTexto = 'EN USO';
                        break;
                    case 'mantenimiento':
                        $estadoBadge = 'badge-warning';
                        $estadoTexto = 'MANTENIMIENTO';
                        break;
                    case 'fuera_servicio':
                        $estadoBadge = 'badge-danger';
                        $estadoTexto = 'FUERA SERVICIO';
                        break;
                }
            @endphp
            <tr class="{{ $rowClass }}">
                <td><strong>{{ $vehiculo->patente }}</strong></td>
                <td>{{ $vehiculo->marca }}</td>
                <td>{{ $vehiculo->modelo }}</td>
                <td class="text-center">{{ $vehiculo->anio }}</td>
                <td>
                    @php
                        $tipoFormateado = '';
                        switch($vehiculo->tipo_vehiculo) {
                            case 'transit':
                                $tipoFormateado = 'Ford Transit';
                                break;
                            case 'kangoo':
                                $tipoFormateado = 'Renault Kangoo';
                                break;
                            case 'partner':
                                $tipoFormateado = 'Peugeot Partner';
                                break;
                            default:
                                $tipoFormateado = ucfirst($vehiculo->tipo_vehiculo);
                        }
                    @endphp
                    {{ $tipoFormateado }}
                </td>
                <td class="text-center">{{ ucfirst($vehiculo->combustible) }}</td>
                <td class="text-right">{{ number_format($vehiculo->kilometraje, 0) }} km</td>
                <td class="text-center">
                    @if($vehiculo->fecha_vencimiento_vtv)
                        {{ $vehiculo->fecha_vencimiento_vtv->format('m/Y') }}
                        @if($vtvProxima)
                            <br><span class="badge badge-warning">PRÓXIMA</span>
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td class="text-right">{{ number_format($vehiculo->capacidad_carga) }} kg</td>
                <td class="text-center">
                    <span class="badge {{ $estadoBadge }}">{{ $estadoTexto }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">INFORMACIÓN DE MANTENIMIENTO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Patente</th>
                <th style="width: 20%;">Vehículo</th>
                <th style="width: 12%;">Kilometraje</th>
                <th style="width: 12%;">VTV</th>
                <th style="width: 12%;">Neumáticos</th>
                <th style="width: 20%;">Servicios Pendientes</th>
                <th style="width: 12%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiculos as $vehiculo)
            <tr>
                <td><strong>{{ $vehiculo->patente }}</strong></td>
                <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</td>
                <td class="text-right">{{ number_format($vehiculo->kilometraje) }} km</td>
                <td class="text-center">
                    @if($vehiculo->fecha_vencimiento_vtv)
                        {{ $vehiculo->fecha_vencimiento_vtv->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td class="text-center">
                    @if($vehiculo->fecha_cambio_neumaticos)
                        {{ $vehiculo->fecha_cambio_neumaticos->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td style="font-size: 8px;">
                    {{ $vehiculo->servicios_pendientes ?? 'Ninguno' }}
                </td>
                <td class="text-center">
                    @php
                        $estadoBadge = 'badge-success';
                        $estadoTexto = 'OK';

                        if ($vehiculo->estado == 'mantenimiento') {
                            $estadoBadge = 'badge-warning';
                            $estadoTexto = 'MANT.';
                        } elseif ($vehiculo->estado == 'fuera_servicio') {
                            $estadoBadge = 'badge-danger';
                            $estadoTexto = 'FUERA';
                        } elseif ($vehiculo->servicios_pendientes) {
                            $estadoBadge = 'badge-warning';
                            $estadoTexto = 'PENDIENTE';
                        }
                    @endphp
                    <span class="badge {{ $estadoBadge }}">{{ $estadoTexto }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $vehiculosConVtvProxima = $vehiculos->filter(function($v) {
            if ($v->fecha_vencimiento_vtv) {
                $diasParaVtv = now()->diffInDays($v->fecha_vencimiento_vtv, false);
                return $diasParaVtv >= 0 && $diasParaVtv <= 30;
            }
            return false;
        });

        $vehiculosConVtvVencida = $vehiculos->filter(function($v) {
            if ($v->fecha_vencimiento_vtv) {
                return $v->fecha_vencimiento_vtv->isPast();
            }
            return false;
        });

        $vehiculosConAlertas = $vehiculosConVtvProxima->merge($vehiculosConVtvVencida)->unique('id');
    @endphp

    @if($vehiculosConAlertas->count() > 0)
    <div class="section-title">ALERTAS DE VTV</div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Patente</th>
                <th style="width: 30%;">Vehículo</th>
                <th style="width: 15%;">Tipo de Alerta</th>
                <th style="width: 15%;">Fecha VTV</th>
                <th style="width: 15%;">Estado VTV</th>
                <th style="width: 10%;">Días</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiculos as $vehiculo)
                @if($vehiculo->fecha_vencimiento_vtv)
                    @php
                        $diasVtv = now()->diffInDays($vehiculo->fecha_vencimiento_vtv, false);
                        $mostrar = false;
                        $tipoAlerta = '';
                        $estadoVtv = '';
                        $diasTexto = '';

                        if ($diasVtv < 0) {
                            $mostrar = true;
                            $tipoAlerta = 'VTV VENCIDA';
                            $estadoVtv = 'badge-danger';
                            $diasTexto = abs($diasVtv) . ' días vencida';
                        } elseif ($diasVtv <= 30) {
                            $mostrar = true;
                            $tipoAlerta = 'VTV Próxima a Vencer';
                            $estadoVtv = 'badge-warning';
                            $diasTexto = $diasVtv . ' días restantes';
                        }
                    @endphp
                    @if($mostrar)
                    <tr>
                        <td><strong>{{ $vehiculo->patente }}</strong></td>
                        <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})</td>
                        <td>
                            <span class="badge {{ $estadoVtv }}">{{ $tipoAlerta }}</span>
                        </td>
                        <td class="text-center">{{ $vehiculo->fecha_vencimiento_vtv->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $estadoVtv }}">
                                {{ $diasVtv < 0 ? 'VENCIDA' : 'VIGENTE' }}
                            </span>
                        </td>
                        <td class="text-center">{{ $diasTexto }}</td>
                    </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">RESUMEN POR TIPO DE VEHÍCULO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Tipo de Vehículo</th>
                <th style="width: 20%;">Cantidad</th>
                <th style="width: 20%;">Porcentaje</th>
                <th style="width: 20%;">Km Promedio</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tiposVehiculos = $vehiculos->groupBy('tipo_vehiculo');
                $totalVehiculos = $vehiculos->count() > 0 ? $vehiculos->count() : 1;
            @endphp
            @foreach($tiposVehiculos as $tipo => $vehiculosPorTipo)
            <tr>
                <td>
                    <strong>
                        @php
                            $tipoFormateado = '';
                            switch($tipo) {
                                case 'transit':
                                    $tipoFormateado = 'Ford Transit';
                                    break;
                                case 'kangoo':
                                    $tipoFormateado = 'Renault Kangoo';
                                    break;
                                case 'partner':
                                    $tipoFormateado = 'Peugeot Partner';
                                    break;
                                default:
                                    $tipoFormateado = ucfirst($tipo ?? 'Sin especificar');
                            }
                        @endphp
                        {{ $tipoFormateado }}
                    </strong>
                </td>
                <td class="text-center">{{ $vehiculosPorTipo->count() }}</td>
                <td class="text-center">{{ number_format(($vehiculosPorTipo->count() / $totalVehiculos) * 100, 1) }}%</td>
                <td class="text-right">{{ number_format($vehiculosPorTipo->avg('kilometraje'), 0) }} km</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-center">{{ $vehiculos->count() }}</td>
                <td class="text-center">100%</td>
                <td class="text-right">{{ number_format($vehiculos->avg('kilometraje'), 0) }} km</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">RESUMEN POR MARCA</div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Marca</th>
                <th style="width: 30%;">Cantidad de Vehículos</th>
                <th style="width: 30%;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @php
                $marcasVehiculos = $vehiculos->groupBy('marca');
            @endphp
            @foreach($marcasVehiculos as $marca => $vehiculosPorMarca)
            <tr>
                <td><strong>{{ $marca }}</strong></td>
                <td class="text-center">{{ $vehiculosPorMarca->count() }}</td>
                <td class="text-center">{{ number_format(($vehiculosPorMarca->count() / $totalVehiculos) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">RESUMEN POR ESTADO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Estado</th>
                <th style="width: 30%;">Cantidad</th>
                <th style="width: 30%;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @php
                $estados = [
                    'disponible' => 'Disponible',
                    'en_uso' => 'En Uso',
                    'mantenimiento' => 'En Mantenimiento',
                    'fuera_servicio' => 'Fuera de Servicio'
                ];
            @endphp
            @foreach($estados as $estadoKey => $estadoLabel)
                @php
                    $cantidad = $vehiculos->where('estado', $estadoKey)->count();
                @endphp
                @if($cantidad > 0)
                <tr>
                    <td><strong>{{ $estadoLabel }}</strong></td>
                    <td class="text-center">{{ $cantidad }}</td>
                    <td class="text-center">{{ number_format(($cantidad / $totalVehiculos) * 100, 1) }}%</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>TecnoServi</strong> - Sistema de Gestión de Flota Vehicular</p>
        <p>Los vehículos con fondo amarillo tienen VTV próxima a vencer (30 días o menos)</p>
        <p>Los vehículos con fondo naranja están en mantenimiento</p>
        <p>Los vehículos con fondo rojo están fuera de servicio</p>
        <p>Para consultas contacte al departamento de logística</p>
    </div>
</body>
</html>
