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
        <div class="row mb-3 mb-lg-5">
            <div class="col text-center">
                <h3><strong>Atualizar caixa</strong></h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-3 text-center">
                        <form action="{{ route('salvar_novo_valor') }}" method="POST">
                            @csrf <!-- Use this if you're using Laravel's CSRF protection -->
                            <div class="form-floating mb-3">
                                <input type="number" step="0.01" class="form-control" name="valor" id="valor" placeholder="Novo valor em caixa">
                                <label for="valor">Novo valor em caixa</label>
                              </div>
                            <button type="submit" class="btn btn-success">Atualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
@endsection
