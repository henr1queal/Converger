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
                <h3><strong>Colaboradores pendentes</strong></h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        @if ($users->count() === 0)
                            <div class="row">
                                <div class="col text-center">
                                    <p>Não existem colaboradores pendentes.</p>
                                </div>
                            </div>
                        @else
                            <table class="table table-striped py-lg-4" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Cargo</th>
                                        <th>E-mail</th>
                                        <th>Data Nasc.:</th>
                                        <th>Solicitado em:</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->position->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ date('d/m/Y', strtotime($user->birth_date)) }}</td>
                                            <td>{{ $user->created_at->format('d/m/Y - H:i') }}</td>
                                            <td>
                                                <button data-user-id="{{ $user->id }}"
                                                    onclick="nameUser({{ $user->id }}, '{{ $user->name }}', 0)"
                                                    type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                                <button data-user-id="{{ $user->id }}"
                                                    onclick="nameUser({{ $user->id }}, '{{ $user->name }}', 1)"
                                                    type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#confirmAceptModal">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Rejeição</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    Tem certeza de que deseja rejeitar e excluir <span class="nameUser"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('aceitar_recusar_usuario', ['user' => 'USER_ID_PLACEHOLDER', 'value' => 0]) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmAceptModal" tabindex="-1" role="dialog" aria-labelledby="confirmAceptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmAceptModalLabel">Aceitar colaborador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Confirma o acesso de <span class="nameUser"></span> como novo colaborador?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('aceitar_recusar_usuario', ['user' => 'USER_ID_PLACEHOLDER', 'value' => 1]) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-success">Aceitar colaborador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
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
        function nameUser(id, name, choose) {
            selectedUserId = id;
            $('.nameUser').text('');
            $('.nameUser').text(name);
            if (choose === 0) {
                $('#confirmDeleteModal form').attr('action',
                    `{{ route('aceitar_recusar_usuario', ['user' => 'USER_ID_PLACEHOLDER', 'value' => 0]) }}`.replace(
                        'USER_ID_PLACEHOLDER', selectedUserId));
            } else {
                $('#confirmAceptModal form').attr('action',
                    `{{ route('aceitar_recusar_usuario', ['user' => 'USER_ID_PLACEHOLDER', 'value' => 1]) }}`.replace(
                        'USER_ID_PLACEHOLDER', selectedUserId));
            }
        }
    </script>
@endsection
