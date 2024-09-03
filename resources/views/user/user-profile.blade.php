@extends('layouts/layout')
@section('script-head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.css">
    <style>
        .accordion-item:last-of-type .accordion-button.collapsed {
            background-color: #2C3034;
        }

        @media (min-width: 992px) {
            .position-lg-absolute {
                position: absolute;
            }
        }

        .accordion-button:focus,
        .accordion-button:not(.collapsed) {
            box-shadow: unset !important;
        }
    </style>
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
        <div class="row mb-4 mb-lg-5 mt-lg-3">
            <div class="col text-center">
                <h3>Visualizando colaborador</h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <form method="POST" action="{{ route('atualizar_colaborador', $user->id) }}" id="contactForm"
                    class="needs-validation position-relative" novalidate>
                    @method('PUT')
                    @csrf
                    @php
                        $birthDate = new DateTime($user->birth_date);
                        $currentDate = new DateTime();
                        $age = $currentDate->diff($birthDate)->y;
                    @endphp
                    <small class="position-lg-absolute top-0 end-0 text-center d-block text-lg-start">Adicionado em
                        {{ $user->created_at->format('d/m/Y') }},
                        às {{ $user->created_at->format('H:i') }}.</small>
                    <small class="position-lg-absolute top-0 start-0 text-center d-block text-lg-start">Data de nascimento:
                        {{ date('d/m/Y', strtotime($user->birth_date)) }} ({{ $age }} anos).</small>
                    <h5 class="text-center mt-4 mt-lg-0 mb-5">Informações gerais</h5>
                    <div class="row justify-content-end">
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select name="status" class="form-select border-2" aria-label="Default select example">
                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Pendente</option>
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Ativo</option>
                                    <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>Desabilitado</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="nome" id="nome" type="text"
                                    placeholder="Nome" required value="{{ old('nome') ? old('nome') : $user->name }}" />
                                <label for="nome">Nome</label>
                                <div class="invalid-feedback">Nome do colaborador é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select name="cargo" class="form-select border-2" aria-label="Default select example">
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}"
                                            {{ old('cargo') == $position->id ? 'selected' : ($user->position->id == $position->id ? 'selected' : '') }}>
                                            {{ $position->name }}</option>
                                    @endforeach
                                </select>
                                <label for="cargo">Cargo</label>
                            </div>
                        </div>
                    </div>
                    @php
                        $permissionsByName = $userPermissions->keyBy('name');
                    @endphp
                    @if (1 === 1)
                        <h5 class="text-center mt-5 mb-4">Permissões</h5>
                        <div class="row justify-content-lg-end">
                            <div class="col-lg-12 mb-3 text-center">
                                <div class="accordion" id="permissionsAccordion">
                                    @php
                                        $count;
                                        $featuresDescription = [
                                            1 => 'Permissões para módulo de Cliente',
                                            2 => 'Permissões para módulo de Fornecedor',
                                            3 => 'Permissões para módulo de Parceiro',
                                            4 => 'Permissões para módulo de Palestrante',
                                            5 => 'Permissões para módulo de Evento',
                                            6 => 'Permissões para módulo de Colaborador',
                                            7 => 'Permissões para módulo de Financeiro',
                                            8 => 'Permissões para módulo de Estatísticas',
                                            9 => 'Permissões para módulo de Notificações',
                                        ];
                                    @endphp
                                    @php
                                        $currentType = null; // Inicializa a variável para controlar as mudanças de tipo.
                                    @endphp

                                    @foreach ($features as $feature)
                                        @if ($feature->type !== $currentType)
                                            {{-- Fecha o accordion-collapse do tipo anterior --}}
                                            @if ($currentType !== null)
                                </div> {{-- Fecha a div do accordion-collapse --}}
                            </div> {{-- Fecha a div do accordion-item --}}
                    @endif

                    {{-- Cria um novo accordion-item quando o tipo muda --}}
                    <div class="accordion-item border-0 mt-1 mb-3">
                        <h2 class="accordion-header" id="{{ $feature->type }}Heading">
                            <button class="accordion-button text-center collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#{{ $feature->type }}Collapse" aria-expanded="false"
                                aria-controls="{{ $feature->type }}Collapse">
                                {{ $featuresDescription[$feature->type] }}
                            </button>
                        </h2>
                        <div id="{{ $feature->type }}Collapse" class="accordion-collapse collapse"
                            style="background-color: rgba(0, 0, 0, 0.171);" aria-labelledby="{{ $feature->type }}Heading">
                            <div class="accordion-body row">
                                @endif
                                <div class="col-lg-4 col-xxl-3 text-start mb-2">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input align-self-center" type="checkbox" role="switch"
                                            id="flexSwitchCheck{{ $feature->id }}" name="permissions[]"
                                            value="{{ $feature->id }}"
                                            {{ $user->features->contains('id', $feature->id) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="flexSwitchCheck{{ $feature->id }}">{{ $feature->description }}</label>
                                    </div>
                                </div>

                                @php
                                    $currentType = $feature->type;
                                @endphp
                                @endforeach
                                @if ($currentType !== null)
                            </div>
                        </div>
                        @endif
                    </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="row justify-content-center mt-3 mb-5">
        <div class="col text-center">
            <button class="btn btn-success btn-lg" id="submitButton" type="submit">Atualizar
                colaborador</button>
        </div>
    </div>
    @endif
    </form>
    @if ($user->events->count() > 0)
        <h5 class="text-center mt-4 pt-lg-5 mb-5">Eventos que participou</h5>
        <table class="table table-striped py-lg-4" style="width:100%">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cliente</th>
                    <th>Palestrante(s)</th>
                    <th>Data do evento</th>
                    <th>Status</th>
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
                @foreach ($user->events as $event)
                    <tr data-href="{{ route('visualizar_evento', $event->id) }}" style="cursor: pointer">
                        <td>{{ $event->name }}</td>
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
    @endif
    </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    Tem certeza de que deseja excluir o colaborador {{ $user->name }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('deletar_colaborador', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-6 mt-5">
                <a href="{{ route('meus_colaboradores') }}" class="btn btn-info text-decoration-none me-5">Ver todos os
                    colaboradores</a>
            </div>
            <div class="col-6 text-end mt-5">
                <button class="btn btn-outline-danger d-block ms-auto" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal">Excluir</button>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script-body')
    <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script>
        $('.table').DataTable({
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
        $(document).ready(function() {
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (() => {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                const forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
            })()

            // 

            $('#cnpj').inputmask('99.999.999/9999-99', {
                clearMaskOnLostFocus: true
            });

            $('#telefonePrincipal').inputmask('(99) 9999-9999', {
                clearMaskOnLostFocus: true
            });

            $('#telefoneDoResponsavel').inputmask([
                "(99) 9999-9999",
                "(99) 9 9999-9999"
            ], {
                clearMaskOnLostFocus: true
            });

            $('#celularPrincipal').inputmask('(99) 9 9999-9999', {
                clearMaskOnLostFocus: true
            });

            // 

            function atualizarNumero() {
                const temNumero = $('#temNumero').is(':checked');
                const numeroInput = $('#numero');

                if (!temNumero) {
                    numeroInput.val('');
                    numeroInput.prop('disabled', true);
                    numeroInput.prop('required', false);
                } else {
                    numeroInput.prop('disabled', false);
                    numeroInput.prop('required', true);
                }
            }

            atualizarNumero();

            $('#temNumero').change(function() {
                atualizarNumero();
            });

            // 



            function limpa_formulário_cep() {
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#estado").val("");
            }


            $("#cep").blur(function() {
                var cep = $(this).val().replace(/\D/g, '');

                if (cep != "") {
                    var validacep = /^[0-9]{8}$/;

                    if (validacep.test(cep)) {

                        $("#endereco").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#estado").val("...");

                        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#estado").val(dados.uf);
                            } else {
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } else {
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } else {
                    limpa_formulário_cep();
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
