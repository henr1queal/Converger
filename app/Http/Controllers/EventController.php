<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Partner;
use App\Models\Speaker;
use App\Models\Supplier;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['speakers'])
            ->select('id', 'name', 'status', 'payment_term', 'customer_id') // Inclua a coluna customer_id na seleção
            ->orderByRaw('FIELD(status, 2, 1, 3, 4)')
            ->withCount('fiscalNote') // Adicione a contagem de fiscalNote
            ->orderBy('fiscal_note_count', 'desc') // Ordene pelo número de fiscalNote
            ->orderBy('payment_term', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('event/event-list', compact('events'));
    }

    public function create()
    {
        $customers = Customer::all();
        $speakers = Speaker::all();
        $eventThemes = Theme::all();
        return view('event.event-form', compact(['eventThemes', 'speakers', 'customers']));
    }

    public function store(Request $request)
    {
        function mensagem()
        {
            return 'O campo endereço é obrigatório quando o tipo de evento for Presencial ou Híbrido.';
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'cliente' => 'required|integer',
            'temas' => 'required|array',
            'temas.*' => 'integer',
            'palestrantes' => 'required|array',
            'palestrantes.*' => 'integer',
            'dataHora' => 'nullable|date',
            'tipoEvento' => 'required|in:1,2,3',
            'cep' => 'required_if:tipoEvento,1,3|string|max:9',
            'endereco' => 'required_if:tipoEvento,1,3|string|max:255',
            'bairro' => 'required_if:tipoEvento,1,3|string|max:255',
            'numero' => 'required_if:temNumero,true|integer',
            'complemento' => 'nullable|string|max:255',
            'cidade' => 'required_if:tipoEvento,1,3|string|max:255',
            'estado' => 'required_if:tipoEvento,1|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'observacao' => 'nullable|string|max:1000',
        ], [

            'endereco.required_if' => mensagem(),
            'estado.required_if' => mensagem(),
            'bairro.required_if' => mensagem(),
            'cidade.required_if' => mensagem(),
            'cep.required_if' => mensagem(),
        ]);

        $event = new Event();
        $event->name = $request->nome;
        $event->start_date_time = $request->dataHora;
        $event->cep = $request->cep;
        $event->neighborhood = $request->bairro;
        $event->number = $request->numero;
        $event->address = $request->endereco;
        $event->complement = $request->complemento;
        $event->city = $request->cidade;
        $event->state = $request->estado;
        $event->observation = $request->observacao;
        $event->customer_id = $request->cliente;
        $event->event_type_id = $request->tipoEvento;
        $event->user_id = Auth()->id();

        try {
            $event->save();
            $event->themes()->attach($request->temas);
            $event->speakers()->attach($request->palestrantes);
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->route('visualizar_evento', $event->id);
    }

    public function show(string $id)
    {
        $customers = Customer::all();
        $partners = Partner::all();
        $speakers = Speaker::all();
        $suppliers = Supplier::all();
        $sellers = User::where('position_id', 3)->get();
        $eventThemes = Theme::all();
        $event = Event::with([
            'customer',
            'fiscalNote.sendedEmail' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Substitua 'created_at' pelo campo que deseja usar para ordenar
            },
            'hosting',
            'partner',
            'speakers',
            'themes',
            'transportPlane',
            'transportBusBetweenCity',
            'transportTaxi',
            'transportUber',
            'transportBusSameCity',
        ])->findOrFail($id);

        if (
            $event->transportPlane->count() === 0 &&
            $event->transportBusBetweenCity->count() === 0 &&
            $event->transportTaxi->count() === 0 &&
            $event->transportUber->count() === 0 &&
            $event->transportBusSameCity->count() === 0
        ) {
            $hasTransport = false;
        } else {
            $hasTransport = true;
        }

        return view('event.event-details', compact('event', 'customers', 'speakers', 'eventThemes', 'partners', 'suppliers', 'hasTransport', 'sellers'));
    }

    public function update(Request $request, string $id)
    {

        $event = Event::with([
            'suppliers',
            'hosting',
            'partner',
            'seller',
            'speakers',
            'transportPlane',
            'transportBusBetweenCity',
            'transportTaxi',
            'transportUber',
            'transportBusSameCity'
        ])->findOrFail($id);

        function limparValor($valor)
        {
            return (float) str_replace(['R$', ','], ['', ''], $valor);
        }

        $event->themes()->sync($request->temas);

        $total_value_speakers = [];
        if ($request->palestrantes) {
            foreach ($request->palestrantes as $speakerId) {
                if (isset($request->valorPalestrante[$speakerId])) {
                    $valorPalestrante = limparValor($request->valorPalestrante[$speakerId]);
                    $total_value_speakers[] = $valorPalestrante;
                    $data = [
                        'observation' => $request->observacaoPalestrante[$speakerId],
                        'price' => $valorPalestrante,
                        'paid' => $valorPalestrante === null || $valorPalestrante == 0 ? 0 : 1,
                    ];
                    $event->speakers()->updateExistingPivot($speakerId, $data);
                }
            }
        }

        if (array_keys($request->input('fornecedor', []))) {
            $submittedSupplierIds = array_keys($request->input('fornecedor', []));
            foreach ($submittedSupplierIds as $supplierId) {
                $existingSupplier = Supplier::find($supplierId);

                if ($existingSupplier && !$event->suppliers->contains($existingSupplier)) {
                    $pivotData = [
                        'observation' => $request->input("observacaoFornecedor.$supplierId"),
                        'price' => limparValor($request->input("valorFornecedor.$supplierId")),
                        'paid' => $request->has("pagamentoFornecedorEfetuado.$supplierId") ? 1 : 0,
                    ];

                    $event->suppliers()->attach($existingSupplier->id, $pivotData);
                }
            }
        }

        foreach ($event->suppliers as $supplier) {
            $supplierId = $supplier->id;

            if (!in_array($supplierId, $submittedSupplierIds)) {
                $event->suppliers()->detach($supplierId);
            } else {
                $pivotData = [
                    'observation' => $request->input("observacaoFornecedor.$supplierId"),
                    'price' => limparValor($request->input("valorFornecedor.$supplierId")),
                    'paid' => $request->has("pagamentoFornecedorEfetuado.$supplierId") ? 1 : 0,
                ];

                $event->suppliers()->updateExistingPivot($supplierId, $pivotData);
            }
        }

        $totalPrice = limparValor($request->valorTotal);
        $convergerPrice = limparValor($request->valorConverger);

        if ($request->vendedor) {
            $valor_vendedor = limparValor($request->valorVendedor);
            $possible_seller = User::find($request->vendedor);
            if ($possible_seller && $possible_seller->position_id === 3) {
                $event->seller_id = $request->vendedor;
                $event->seller_price = $valor_vendedor;
                $event->seller_observation = $request->observacaoVendedor;
                !$request->pagamentoVendedorEfetuado || $valor_vendedor === "R$ 0.00" ? $event->seller_received = 0 : $event->seller_received = 1;
            }
        } else {
            $event->seller_id = null;
            $event->seller_price = null;
            $event->seller_observation = null;
            $event->seller_received = null;
        }

        if ($request->parceiro) {
            $possible_partner = Partner::where('id', $request->parceiro)->exists();
            if ($possible_partner) {
                $event->partner_id = $request->parceiro;
                $event->partner_price = limparValor($request->valorParceiro);
                $event->partner_observation = $request->observacaoParceiro;
                !$request->pagamentoParceiroEfetuado || $request->valorParceiro === "R$ 0.00" ? $event->partner_received = 0 : $event->partner_received = 1;
            };
        } else {
            $event->partner_id = null;
            $event->partner_price = null;
            $event->partner_observation = null;
            $event->partner_received = null;
        }


        $event->speakers()->sync($request->palestrantes);


        if ($request->tipoTransporte === '0') {
            $methodsToCall = [
                'transportPlane',
                'transportBusBetweenCity',
                'transportTaxi',
                'transportUber',
                'transportBusSameCity',
            ];
        } elseif ($request->tipoTransporte === '1') {
            $methodsToCall = [
                'transportBusBetweenCity',
                'transportBusSameCity',
                'transportUber',
                'transportTaxi',
            ];

            if (
                !empty($request->empresaAereaIda) || !empty($request->numeroAviaoIda) || !empty($request->assentoAviaoIda) || !empty($request->aeroportoOrigemIda)
                || !empty($request->aeroportoDestinoIda) || !empty($request->dataViagemIda) || !empty($request->inicioEmbarqueIda) || !empty($request->previsaoChegadaIda)
                || !empty($request->compradorPassagemIda) || !empty($request->telefoneCompradorIda) || !empty($request->observacaoViagemIda)
            ) {

                $plane_one = $event->transportPlane->where('type', 1)->first();

                if ($plane_one) {
                    $plane_one->update([
                        'company' => $request->empresaAereaIda,
                        'number' => $request->numeroAviaoIda,
                        'seat' => $request->assentoAviaoIda,
                        'origin_airport' => $request->aeroportoOrigemIda,
                        'destiny_airport' => $request->aeroportoDestinoIda,
                        'trip_date' => $request->dataViagemIda,
                        'start_boarding' => $request->inicioEmbarqueIda,
                        'arrival_forecast' => $request->previsaoChegadaIda,
                        'ticket_buyer' => $request->compradorPassagemIda,
                        'phone_buyer' => $request->telefoneCompradorIda,
                        'observation' => $request->observacaoViagemIda,
                        'type' => 1
                    ]);
                } else {
                    $event->transportPlane()->create([
                        'company' => $request->empresaAereaIda,
                        'number' => $request->numeroAviaoIda,
                        'seat' => $request->assentoAviaoIda,
                        'origin_airport' => $request->aeroportoOrigemIda,
                        'destiny_airport' => $request->aeroportoDestinoIda,
                        'trip_date' => $request->dataViagemIda,
                        'start_boarding' => $request->inicioEmbarqueIda,
                        'arrival_forecast' => $request->previsaoChegadaIda,
                        'ticket_buyer' => $request->compradorPassagemIda,
                        'phone_buyer' => $request->telefoneCompradorIda,
                        'observation' => $request->observacaoViagemIda,
                        'type' => 1
                    ]);
                }
            }

            if (
                !empty($request->empresaAereaVolta) || !empty($request->numeroAviaoVolta) || !empty($request->assentoAviaoVolta) || !empty($request->aeroportoOrigemVolta)
                || !empty($request->aeroportoDestinoVolta) || !empty($request->dataViagemVolta) || !empty($request->inicioEmbarqueVolta) || !empty($request->previsaoChegadaVolta)
                || !empty($request->compradorPassagemVolta) || !empty($request->telefoneCompradorVolta) || !empty($request->observacaoViagemVolta)
            ) {

                $plane_two = $event->transportPlane->where('type', 2)->first();

                if ($plane_two) {
                    $plane_two->update([
                        'company' => $request->empresaAereaVolta,
                        'number' => $request->numeroAviaoVolta,
                        'seat' => $request->assentoAviaoVolta,
                        'origin_airport' => $request->aeroportoOrigemVolta,
                        'destiny_airport' => $request->aeroportoDestinoVolta,
                        'trip_date' => $request->dataViagemVolta,
                        'start_boarding' => $request->inicioEmbarqueVolta,
                        'arrival_forecast' => $request->previsaoChegadaVolta,
                        'ticket_buyer' => $request->compradorPassagemVolta,
                        'phone_buyer' => $request->telefoneCompradorVolta,
                        'observation' => $request->observacaoViagemVolta,
                        'type' => 1
                    ]);
                } else {
                    $event->transportPlane()->create([
                        'company' => $request->empresaAereaVolta,
                        'number' => $request->numeroAviaoVolta,
                        'seat' => $request->assentoAviaoVolta,
                        'origin_airport' => $request->aeroportoOrigemVolta,
                        'destiny_airport' => $request->aeroportoDestinoVolta,
                        'trip_date' => $request->dataViagemVolta,
                        'start_boarding' => $request->inicioEmbarqueVolta,
                        'arrival_forecast' => $request->previsaoChegadaVolta,
                        'ticket_buyer' => $request->compradorPassagemVolta,
                        'phone_buyer' => $request->telefoneCompradorVolta,
                        'observation' => $request->observacaoViagemVolta,
                        'type' => 2
                    ]);
                }
            }
        } elseif ($request->tipoTransporte === '2') {
            $methodsToCall = [
                'transportPlane',
                'transportBusSameCity',
                'transportUber',
                'transportTaxi',
            ];

            if (
                !empty($request->empresaOnibusIda) || !empty($request->numeroOnibusIda) || !empty($request->assentoOnibusIda) || !empty($request->terminalOrigemIda)
                || !empty($request->terminalDestinoIda) || !empty($request->dataViagemOnibusEntreCidadesIda) || !empty($request->inicioEmbarqueOnibusIda) || !empty($request->previsaoChegadaOnibusIda)
                || !empty($request->compradorPassagemOnibusIda) || !empty($request->telefoneCompradorOnibusIda) || !empty($request->observacaoViagemOnibusIda)
            ) {

                $plane_one = $event->transportPlane->where('type', 1)->first();

                if ($plane_one) {
                    $plane_one->update([
                        'company' => $request->empresaOnibusIda,
                        'number' => $request->numeroOnibusIda,
                        'seat' => $request->assentoOnibusIda,
                        'origin_city' => $request->terminalOrigemIda,
                        'destiny_city' => $request->terminalDestinonoIda,
                        'trip_date' => $request->dataViagemOnibusEntreCidadesIda,
                        'start_boarding' => $request->inicioEmbarqueOnibusIda,
                        'arrival_forecast' => $request->previsaoChegadaOnibusIda,
                        'ticket_buyer' => $request->compradorPassagemOnibusIda,
                        'phone_buyer' => $request->telefoneCompradorOnibusIda,
                        'observation' => $request->observacaoViagemOnibusIda,
                        'type' => 1
                    ]);
                } else {
                    $event->transportPlane()->create([
                        'company' => $request->empresaOnibusIda,
                        'number' => $request->numeroOnibusIda,
                        'seat' => $request->assentoOnibusIda,
                        'origin_city' => $request->terminalOrigemIda,
                        'destiny_city' => $request->terminalDestinonoIda,
                        'trip_date' => $request->dataViagemOnibusEntreCidadesIda,
                        'start_boarding' => $request->inicioEmbarqueOnibusIda,
                        'arrival_forecast' => $request->previsaoChegadaOnibusIda,
                        'ticket_buyer' => $request->compradorPassagemOnibusIda,
                        'phone_buyer' => $request->telefoneCompradorOnibusIda,
                        'observation' => $request->observacaoViagemOnibusIda,
                        'type' => 1
                    ]);
                }
            }

            if (
                !empty($request->empresaOnibusVolta) || !empty($request->numeroOnibusVolta) || !empty($request->assentoOnibusVolta) || !empty($request->terminalOrigemVolta)
                || !empty($request->terminalDestinoVolta) || !empty($request->dataViagemOnibusEntreCidadesVolta) || !empty($request->inicioEmbarqueOnibusVolta) || !empty($request->previsaoChegadaOnibusVolta)
                || !empty($request->compradorPassagemOnibusVolta) || !empty($request->telefoneCompradorOnibusVolta) || !empty($request->observacaoViagemOnibusVolta)
            ) {

                $plane_two = $event->transportPlane->where('type', 2)->first();

                if ($plane_two) {
                    $plane_two->update([
                        'company' => $request->empresaOnibusVolta,
                        'number' => $request->numeroOnibusVolta,
                        'seat' => $request->assentoOnibusVolta,
                        'origin_city' => $request->terminalOrigemVolta,
                        'destiny_city' => $request->terminalDestinoVolta,
                        'trip_date' => $request->dataViagemOnibusEntreCidadesVolta,
                        'start_boarding' => $request->inicioEmbarqueOnibusVolta,
                        'arrival_forecast' => $request->previsaoChegadaOnibusVolta,
                        'ticket_buyer' => $request->compradorPassagemOnibusVolta,
                        'phone_buyer' => $request->telefoneCompradorOnibusVolta,
                        'observation' => $request->observacaoViagemOnibusVolta,
                        'type' => 1
                    ]);
                } else {
                    $event->transportPlane()->create([
                        'company' => $request->empresaOnibusVolta,
                        'number' => $request->numeroOnibusVolta,
                        'seat' => $request->assentoOnibusVolta,
                        'origin_city' => $request->terminalOrigemVolta,
                        'destiny_city' => $request->terminalDestinoVolta,
                        'trip_date' => $request->dataViagemOnibusEntreCidadesVolta,
                        'start_boarding' => $request->inicioEmbarqueOnibusVolta,
                        'arrival_forecast' => $request->previsaoChegadaOnibusVolta,
                        'ticket_buyer' => $request->compradorPassagemOnibusVolta,
                        'phone_buyer' => $request->telefoneCompradorOnibusVolta,
                        'observation' => $request->observacaoViagemOnibusVolta,
                        'type' => 2
                    ]);
                }
            }
        } elseif ($request->tipoTransporte === '3') {
            $methodsToCall = [
                'transportPlane',
                'transportBusBetweenCity',
                'transportUber',
                'transportTaxi',
            ];

            if (!empty($request->dataLocomocaoOnibusCurtaDistanciaIda) || !empty($request->ObservacaoOnibusCurtaDistanciaIda)) {
                $busSameCityOne = $event->transportBusSameCity->where('type', 1)->first();

                if ($busSameCityOne) {
                    $busSameCityOne->update([
                        'date' => $request->dataLocomocaoOnibusCurtaDistanciaIda,
                        'observation' => $request->ObservacaoOnibusCurtaDistanciaIda,
                    ]);
                } else {
                    $event->transportBusSameCity()->create([
                        'date' => $request->dataLocomocaoOnibusCurtaDistanciaIda,
                        'observation' => $request->ObservacaoOnibusCurtaDistanciaIda,
                        'type' => 1,
                    ]);
                }
            }

            if (!empty($request->dataLocomocaoOnibusCurtaDistanciaVolta) || !empty($request->ObservacaoOnibusCurtaDistanciaVolta)) {
                $busSameCityTwo = $event->transportBusSameCity->where('type', 2)->first();

                if ($busSameCityTwo) {
                    $busSameCityTwo->update([
                        'date' => $request->dataLocomocaoOnibusCurtaDistanciaVolta,
                        'observation' => $request->ObservacaoOnibusCurtaDistanciaVolta,
                    ]);
                } else {
                    $event->transportBusSameCity()->create([
                        'date' => $request->dataLocomocaoOnibusCurtaDistanciaVolta,
                        'observation' => $request->ObservacaoOnibusCurtaDistanciaVolta,
                        'type' => 2,
                    ]);
                }
            }
        } elseif ($request->tipoTransporte === '4') {
            $methodsToCall = [
                'transportPlane',
                'transportBusBetweenCity',
                'transportBusSameCity',
                'transportTaxi',
            ];

            if (!empty($request->dataLocomocaoUberIda) || !empty($request->ObservacaoUberIda)) {
                $UberOne = $event->transportUber->where('type', 1)->first();

                if ($UberOne) {
                    $UberOne->update([
                        'date' => $request->dataLocomocaoUberIda,
                        'observation' => $request->ObservacaoUberIda,
                    ]);
                } else {
                    $event->transportUber()->create([
                        'date' => $request->dataLocomocaoUberIda,
                        'observation' => $request->ObservacaoUberIda,
                        'type' => 1,
                    ]);
                }
            }

            if (!empty($request->dataLocomocaoUberVolta) || !empty($request->ObservacaoUberVolta)) {
                $UberTwo = $event->transportUber->where('type', 2)->first();

                if ($UberTwo) {
                    $UberTwo->update([
                        'date' => $request->dataLocomocaoUberVolta,
                        'observation' => $request->ObservacaoUberVolta,
                    ]);
                } else {
                    $event->transportUber()->create([
                        'date' => $request->dataLocomocaoUberVolta,
                        'observation' => $request->ObservacaoUberVolta,
                        'type' => 2,
                    ]);
                }
            }
        } elseif ($request->tipoTransporte === '5') {
            $methodsToCall = [
                'transportPlane',
                'transportBusBetweenCity',
                'transportUber',
                'transportBusSameCity',
            ];

            if (!empty($request->dataLocomocaoTaxiIda) || !empty($request->ObservacaoTaxiIda)) {
                $taxiOne = $event->transportTaxi->where('type', 1)->first();

                if ($taxiOne) {
                    $taxiOne->update([
                        'date' => $request->dataLocomocaoTaxiIda,
                        'observation' => $request->ObservacaoTaxiIda,
                    ]);
                } else {
                    $event->transportTaxi()->create([
                        'date' => $request->dataLocomocaoTaxiIda,
                        'observation' => $request->ObservacaoTaxiIda,
                        'type' => 1,
                    ]);
                }
            }

            if (!empty($request->dataLocomocaoTaxiVolta) || !empty($request->ObservacaoTaxiVolta)) {
                $taxiTwo = $event->transportTaxi->where('type', 2)->first();

                if ($taxiTwo) {
                    $taxiTwo->update([
                        'date' => $request->dataLocomocaoTaxiVolta,
                        'observacao' => $request->ObservacaoTaxiVolta,
                    ]);
                } else {
                    $event->transportTaxi()->create([
                        'date' => $request->dataLocomocaoTaxiVolta,
                        'observacao' => $request->ObservacaoTaxiVolta,
                        'type' => 2,
                    ]);
                }
            }
        } else {
            $methodsToCall = [
                'transportPlane',
                'transportBusBetweenCity',
                'transportTaxi',
                'transportUber',
                'transportBusSameCity',
            ];
        }

        foreach ($methodsToCall as $method) {
            $event->$method()->delete();
        }

        $event->name = $request->nome;
        $event->address = $request->endereco;
        $event->start_date_time = $request->dataHora;
        $event->observation = $request->observacao;
        $event->cep = $request->cep;
        $event->number = $request->numero;
        $event->neighborhood = $request->bairro;
        $event->city = $request->cidade;
        $event->state = $request->estado;
        $event->complement = $request->complemento;
        $event->event_type_id = $request->tipoEvento;
        $event->status = $request->status;

        $allPaid = $event->speakers->every(function ($speaker) {
            return $speaker->pivot->paid == 1;
        });

        $allPaid ? $event->speaker_payment_date = today() : $event->speaker_payment_date = null;

        $event->total_price = $totalPrice;
        $event->converger_price = $convergerPrice;



        $event->payment_term = $request->prazoPagamento;

        if (!empty($request->nomeHospedagem)) {
            $hosting = $event->hosting;
            if ($hosting != null) {
                $hosting->update([
                    'name' => $request->nomeHospedagem,
                    'check_in' => $request->checkIn,
                    'check_out' => $request->checkOut,
                    'cep' => $request->cepHospedagem,
                    'address' => $request->enderecoHospedagem,
                    'state' => $request->estadoHospedagem,
                    'city' => $request->cidadeHospedagem,
                    'neighborhood' => $request->bairroHospedagem,
                    'number' => $request->numeroHospedagem,
                    'complement' => $request->complementoHospedagem,
                    'reference_point' => $request->pontoDeReferenciaHospedagem,
                    'observation' => $request->observacaoHospedagem,
                ]);
            } else {
                $event->hosting()->create([
                    'name' => $request->nomeHospedagem,
                    'check_in' => $request->checkIn,
                    'check_out' => $request->checkOut,
                    'cep' => $request->cepHospedagem,
                    'address' => $request->enderecoHospedagem,
                    'state' => $request->enderecoHospedagem,
                    'city' => $request->cidadeHospedagem,
                    'neighborhood' => $request->bairroHospedagem,
                    'number' => $request->numeroHospedagem,
                    'complement' => $request->complementoHospedagem,
                    'reference_point' => $request->pontoDeReferenciaHospedagem,
                    'observation' => $request->observacaoHospedagem,
                ]);
            }
        } else {
            $event->hosting()->delete();
        }

        $event->save();

        return redirect()->back()->with('success', 'Evento atualizado com sucesso.');
    }
}
