@extends('layouts/layout')
@section('script-head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.css">
@endsection
@section('content')
    <div class="container px-lg-5 mt-lg-3 mb-5">
        @if (session()->get('success'))
            @php
                $message = session()->get('success');
                $message_success = true;
            @endphp
        @elseif(session()->get('error'))
            @php
                $message = session()->get('error');
                $message_error = true;
            @endphp
        @endif
        @if ($errors->any())
            <div class="row">
                <div class="col">
                    <div class="alert mb-4 mb-lg-3 alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        @if (isset($message_success) || isset($message_error))
            <div class="row">
                <div class="col">
                    <div class="alert mb-4 mb-lg-3 {{ isset($message_success) ? 'alert-success' : 'alert-danger' }}">
                        {{ $message }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row mb-3 mb-lg-5">
            <div class="col text-center">
                <h3><strong>Nossos eventos</strong></h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        @if ($events->count() > 0)
                            <table class="table table-striped py-lg-4 no-datatables" id="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="datatable-col">Nome</th>
                                        <th class="datatable-col">Cliente</th>
                                        <th class="datatable-col">Palestrantes</th>
                                        <th class="datatable-col">Data do evento</th>
                                        <th class="datatable-col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $status_numbers = [
                                            1 => [
                                                'name' => 'Em aberto',
                                                'color' => 'white',
                                            ],
                                            2 => [
                                                'name' => 'Iniciado',
                                                'color' => 'blue',
                                            ],
                                            3 => [
                                                'name' => 'Finalizado',
                                                'color' => 'green',
                                            ],
                                            4 => [
                                                'name' => 'Cancelado',
                                                'color' => 'red',
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($events as $event)
                                        @php
                                            $isStatus2 = $event->status === 2;
                                        @endphp
                                        <tr data-href="{{ route('visualizar_evento', $event->id) }}"
                                            style="cursor: pointer">
                                            <td>
                                                @if(in_array($event->id, $eventsExpiredIds))
                                                <i class="bi bi-exclamation-circle me-2"
                                                    style="font-size: 0.9rem; color: #fd7e14;"></i>
                                                @elseif(in_array($event->id, $eventsFutureIds))
                                                <i class="bi bi-exclamation-circle me-2"
                                                    style="font-size: 0.9rem; color: rgb(94, 216, 238);"></i>
                                                @endif
                                                {{ $event->name }}
                                            </td>
                                            <td>{{ $event->customer->name }}</td>
                                            @if ($event->speakers->count() > 0 && $event->speakers->count() < 2)
                                                <td>{{ $event->speakers->first()->name }}</td>
                                            @elseif($event->speakers->count() > 1)
                                                <td>{{ $event->speakers->first()->name }} e +
                                                    {{ $event->speakers->count() - 1 }}</td>
                                            @else
                                                <td>N/A</td>
                                            @endif
                                            @if ($event->status == 1)
                                                @php
                                                    $color_text = 'black';
                                                @endphp
                                            @else
                                                @php
                                                    $color_text = 'white';
                                                @endphp
                                            @endif
                                            <td>{{ date('d/m/Y \à\s H\hi', strtotime($event->start_date_time)) }}</td>
                                            <td>
                                                <span class="badge rounded-pill"
                                                    style="min-width: 30px; padding: 6px 20px; background-color: {{ $status_numbers[$event->status]['color'] }}; color: {{ $color_text }};">{{ $status_numbers[$event->status]['name'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Ainda não temos eventos cadastrados.</p>
                            <p>Adicione o primeiro evento <a href="{{ route('novo_evento') }}">clicando aqui.</a></p>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tr[data-href]');
            rows.forEach(row => {
                row.addEventListener('dblclick', () => {
                    window.location.href = row.getAttribute('data-href');
                });
            });
        });
    </script>
    <script>
        $('#table').DataTable({
            "ordering": false,
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "Mostrar _MENU_ registros por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sSearch": "Pesquisar:",
                "sZeroRecords": "Nenhum registro correspondente encontrado",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Próximo",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": ativar para classificar a coluna em ordem crescente",
                    "sSortDescending": ": ativar para classificar a coluna em ordem decrescente"
                }
            }
        });
    </script>
@endsection
