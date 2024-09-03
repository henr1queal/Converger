@extends('layouts/layout')
@section('script-head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <h3><strong>Estatísticas</strong></h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center mt-5 mt-lg-4 mb-4 mb-lg-5">Temas de palestras que mais vendem:</h5>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                @if ($themes_most_sale->count() > 0)
                                    <table class="table table-striped py-lg-4" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 60%;">Tema</th>
                                                <th style="width: 40%;">Qtd. eventos finalizados</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($themes_most_sale as $theme)
                                                <tr>
                                                    <td>{{ $theme->theme }}</td>
                                                    <td>{{ $theme->events_count }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                <p class="text-center">Ainda não temos registros de vendas de temas.</p>
                                @endif
                            </div>
                        </div>
                        <hr class="w-25 mx-auto my-4 my-lg-5">
                        <h5 class="text-center mt-5 mt-lg-4 mb-4 mb-lg-5">Palestrantes que mais vendem:</h5>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                @if($speakers_most_sale->count() > 0)
                                <table class="table table-striped py-lg-4" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 60%;">Nome</th>
                                            <th style="width: 40%;">Qtd. eventos finalizados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($speakers_most_sale as $speaker)
                                            <tr data-href="{{ route('visualizar_palestrante', $speaker->id) }}"
                                                style="cursor: pointer">
                                                <td>{{ $speaker->name }}</td>
                                                <td>{{ $speaker->events_count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <p class="text-center">Ainda não temos registros de vendas de palestrantes.</p>
                                @endif
                            </div>
                        </div>
                        <hr class="w-25 mx-auto my-4 my-lg-5">
                        <h5 class="mb-4 text-center">Total de vendas realizadas:</h5>
                        <div class="row justify-content-center">
                            <div class="col-10 col-lg-4 col-xxl-3 rounded border-2 text-center py-3"
                                style="border: 1px solid white;">
                                <h4 class="mb-0 text-success"><strong>R$ {{ $total_value }}</strong></h4>
                            </div>
                        </div>
                        <hr class="w-25 mx-auto my-4 my-lg-5">
                        <h5 class="text-center mt-5 mt-lg-4 mb-4 mb-lg-5">Orçamento entre datas:</h5>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-6 col-lg-3 col-xxl-2">
                                <div class="form-floating">
                                    <input type="date" id="startDate" value="2000-01-01" class="form-control">
                                    <label for="startDate">Data inicial</label>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-xxl-2">
                                <div class="form-floating">
                                    <input type="date" id="endDate" value="2000-01-01" class="form-control">
                                    <label for="endDate">Data final</label>
                                </div>
                            </div>
                            <div class="text-center text-lg-start mt-4 mt-lg-0 col-lg-3 col-xxl-2">
                                <button id="calculateSum" class="btn btn-primary w-100">Calcular Soma</button>
                            </div>
                        </div>
                        <div class="mt-3 text-center" id="totalSum"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script>
        // Obtém a data atual
        const currentDate = new Date();

        // Formata a data no formato "YYYY-MM-DD"
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;

        // Define o valor padrão do campo de data
        document.getElementById("endDate").value = formattedDate;
    </script>
    <script>
        $('#table').DataTable({
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
        $('#calculateSum').on('click', function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: './sua-rota-para-calcular-soma',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(data) {
                    $('#totalSum').html('Soma Total entre as datas acima: <strong>R$ ' + data.totalSum +
                        '</strong>');
                }
            });
        });
    </script>
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
@endsection
