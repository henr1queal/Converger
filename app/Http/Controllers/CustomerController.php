<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\Responsible;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['responsible', 'events'])->get();
        return view('customer/customer-list', ['customers' => $customers]);
    }
    
    public function create()
    {
        $banks = Bank::all();
        return view('customer/customer-form', ['banks' => $banks]);
    }

    public function store(Request $request)
    {
        // dd($request->request);
        $validated = $request->validate([
            'nome' => 'required',
            'razaoSocial' => 'nullable|max:255',
            'estruturaLegalDaOrganizacao' => 'nullable|integer|max:1',
            'cnpj' => 'nullable|unique:customers|string',
            'inscMunicipal' => 'nullable|string',
            'cep' => 'nullable|string|max:9',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:2',
            'bairro' => 'nullable|string|max:50',
            'numero' => 'nullable|integer|digits_between:1,6',
            'complemento' => 'nullable|string|max:60',
            'emailPrincipal' => 'unique:customers|required|string|max:70',
            'telefonePrincipal' => 'nullable|nullable|string|max:16',
            'celularPrincipal' => 'nullable|string|max:16',
            'origemDoCliente' => 'required|max:1',
            'observacao' => 'nullable|string|max:255',
            'agencia' => 'nullable|string',
            'conta' => 'nullable|string',
            'chavePix' => 'nullable|string',
            'condicoesDePagamento' => 'nullable|integer',
            'banco' => 'nullable|integer',
            'responsavelPeloPagamento' => 'nullable|string|max:250',
            'telefoneDoResponsavel' => 'nullable|string|max:16',
        ]);
        
// dd('teste');

        $customer = new Customer();
        $customer->name = $request->nome;
        $customer->corporate_reason = $request->razaoSocial;
        $customer->organization_type_id = $request->estruturaLegalDaOrganizacao;
        $customer->cnpj = $request->cnpj;
        $customer->municipal_registration = $request->inscMunicipal;
        $customer->cep = $request->cep;
        $customer->address = $request->endereco;
        $customer->city = $request->cidade;
        $customer->state = $request->estado;
        $customer->neighborhood = $request->bairro;
        $customer->number = $request->numero;
        $customer->complement = $request->complemento;
        $customer->emailPrincipal = $request->emailPrincipal;
        $customer->phone = $request->telefonePrincipal;
        $customer->phone_number = $request->celularPrincipal;
        $customer->observation = $request->observacao;
        $customer->agency = $request->agencia;
        $customer->account = $request->conta;
        $customer->pix = $request->chavePix;
        $customer->financial_observation = $request->condicoesDePagamento;
        $customer->bank_id = $request->banco;

        if($request->responsavelPeloPagamento){
            $responsible = new Responsible();
            $responsible->full_name = $request->responsavelPeloPagamento;
            $responsible->phone = $request->telefoneDoResponsavel;
            $responsible->save();
            $customer->responsible_id = $responsible->id;
        }

        $customer->save();
        
        return redirect()->route('visualizar_cliente', ['id' => $customer->id])->with('success', 'Cliente adicionado com sucesso');
    }

    public function show(Customer $id)
    {
        $banks = Bank::all();
        return view('customer/customer-profile', ['customer' => $id, 'banks' => $banks]);
    }

    public function edit(Customer $customer)
    {
        //
    }

    public function update(Request $request, Customer $customer)
    {
        // dd($request);
        $customer->name = $request->nome;
        $customer->corporate_reason = $request->razaoSocial;
        $customer->organization_type_id = $request->estruturaLegalDaOrganizacao;
        $customer->cnpj = $request->cnpj;
        $customer->municipal_registration = $request->inscMunicipal;
        $customer->cep = $request->cep;
        $customer->address = $request->endereco;
        $customer->city = $request->cidade;
        $customer->state = $request->estado;
        $customer->neighborhood = $request->bairro;
        $customer->number = $request->numero;
        $customer->complement = $request->complemento;
        $customer->emailPrincipal = $request->emailPrincipal;
        $customer->phone = $request->telefonePrincipal;
        $customer->phone_number = $request->celularPrincipal;
        $customer->origin = $request->origemDoCliente;
        $customer->observation = $request->observacao;
        $customer->agency = $request->agencia;
        $customer->account = $request->conta;
        $customer->pix = $request->chavePix;
        $customer->financial_observation = $request->condicoesDePagamento;
        $customer->bank_id = $request->banco;

        // $responsible = new Responsible();
        // $responsible->full_name = $request->responsavelPeloPagamento;
        // $responsible->phone = $request->telefoneDoResponsavel;
        // $responsible->save();

        // $customer->responsible_id = $responsible->id;
        $customer->save();

        return redirect()->route('visualizar_cliente', ['id' => $customer->id])->with('success', 'Cliente atualizado com sucesso');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('meus_clientes')->with('success', 'Cliente excluído com sucesso.');
    }
}
