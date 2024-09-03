@extends('layouts/layout')
@section('script-head')
    <link href="{{ asset('../resources/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .was-validated .custom-select:invalid+.select2 .select2-selection {
            border-color: var(--bs-form-invalid-border-color) !important;
        }

        .was-validated .custom-select:valid+.select2 .select2-selection {
            border-color: var(--bs-form-valid-border-color);
        }

        *:focus {
            outline: 0px;
        }

        .select2-search__field::placeholder {
            color: var(--bs-card-color);
            font-size: 1rem !important;
        }

        .select2-selection__choice {
            color: #000000 !important;
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
        <div class="row mb-3 mb-lg-5">
            <div class="col text-center">
                <h3><strong>Financeiro</strong></h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-4 text-center">
                        <h5 class="text-success">Meu caixa:</h5>
                        <h4 class="text-success"><strong>R$ {{ $my_value->value }}</strong></h4>
                        <a href="{{ route('atualizar_caixa') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="bi bi-pencil me-2" viewBox="0 0 16 16">
                                <path
                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                            </svg>Deseja editar?</a>
                        <br>
                        <small>Editado pela última vez em
                            {{ $my_value->updated_at->format('d/m/Y \à\s H:i') }}.</small>
                    </div>
                    <div class="col-lg-4 text-center">
                        <h5 class="text-danger">Despesas não pagas:</h5>
                        <h4 class="text-danger"><strong>R$ {{ $total_sum_open }}</strong></h4>
                        @if (isset($total_sum_to_paid))
                            <h5 class="mt-lg-3">Despesas pagas:</h5>
                            <h4><strong>R$ {{ $total_sum_to_paid }}</strong></h4>
                        @endif
                    </div>
                    <div class="col-lg-4 text-center">
                        <h5 class="text-success">Meu valor líquido:</h5>
                        <h4 class="text-success"><strong>R$
                                {{ $total_sum_to_paid !== 0 ? $my_value->value - $total_sum_to_paid : $my_value->value }}</strong>
                        </h4>
                    </div>
                </div>
                <div class="row justify-content-center mt-lg-5">
                    <div class="col-lg-12 col-xxl-12 text-center">
                        @if ($financial_entries->count() === 1)
                            <p>Você ainda não registrou despesas. Inicie adicionando uma despesa abaixo:</p>
                        @endif
                        <h5 class="mt-lg-5">Despesas:</h5>

                        <form action="{{ route('salvar_nova_despesa') }}" method="post">
                            @csrf
                            <div class="row justify-content-center align-items-center mt-4">
                                <div class="col-lg-5">
                                    <div class="form-floating">
                                        <textarea class="form-control" required name="descricao" placeholder="Descrição" id="descricao"></textarea>
                                        <label for="descricao">Descrição</label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-floating">
                                        <input required type="number" step='0.01' name="valor" class="form-control"
                                            placeholder="Valor" id="valor">
                                        <label for="valor">Valor</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input required type="date" name="data" class="form-control" id="data">
                                        <label for="data">Data de transação</label>
                                    </div>
                                </div>
                                <div class="col-2 text-start">
                                    <button class="btn btn-success py-3 w-100" required type="submit"><i
                                            class="bi bi-plus-lg"></i> Nova despesa</button>
                                </div>
                            </div>
                        </form>
                        @if ($financial_entries->count() > 0)
                            <table class="table table-striped mt-5" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="col-lg-5 col-xxl-5">Descrição</th>
                                        <th class="col-lg-2 col-xxl-2">Valor</th>
                                        <th class="col-lg-2 col-xxl-3">Data</th>
                                        <th class="col-lg-2 col-xxl-2">Status</th>
                                        <th class="col-lg-4 col-xxl-1">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($financial_entries as $financial)
                                        @if ($financial->id !== 1)
                                            @php
                                                $financial->status == 1 ? ($status = 'Pago') : ($status = 'Não pago');
                                            @endphp
                                            <tr>
                                                <td>{{ $financial->description }}</td>
                                                <td>{{ $financial->value }}</td>
                                                <td>{{ date('d/m/Y', strtotime($financial->date)) }}</td>
                                                <td>{{ $status }}</td>
                                                <td class="d-flex justify-content-between">
                                                    <form class="ms-xxl-5" method="POST"
                                                        action="{{ route('atualizar_status_despesa', $financial->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="h-100 me-lg-2 btn {{ $financial->status == 0 ? 'btn-primary' : 'btn-light' }}">{{ $financial->status == 0 ? 'Confirmar pagamento' : 'Desconfirmar pagamento' }}</button>
                                                    </form>

                                                    <form method="POST"
                                                        action="{{ route('deletar_despesa', $financial->id) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="h-100 btn btn-danger">Excluir</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="row justify-content-center mt-lg-5">
                    <div class="col-8 text-center">
                        @if ($financial_entries->count() > 0)
                            <h5>Levantamento mensal</h5>
                            @php
                                $months_portuguese = [
                                    1 => 'Janeiro',
                                    2 => 'Fevereiro',
                                    3 => 'Março',
                                    4 => 'Abril',
                                    5 => 'Maio',
                                    6 => 'Junho',
                                    7 => 'Julho',
                                    8 => 'Agosto',
                                    9 => 'Setembro',
                                    10 => 'Outubro',
                                    11 => 'Novembro',
                                    12 => 'Dezembro',
                                ];
                            @endphp
                            <table class="table table-striped mt-4">
                                <thead>
                                    <tr>
                                        <th>Mês</th>
                                        <th>Ano</th>
                                        <th>Despesas do mês</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($financial_values_per_month as $entry)
                                        <tr>
                                            <td>{{ $months_portuguese[$entry->month] }}</td>
                                            <td>{{ $entry->year }}</td>
                                            <td>{{ $entry->total_value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script-body')
    @endsection
