<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Partner;
use App\Models\Responsible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with('responsible')->get();
        return view('partner/partner-list', ['partners' => $partners]);
    }

    public function create()
    {
        $banks = Bank::all();
        return view('partner/partner-form', ['banks' => $banks]);
    }

    public function store(Request $request)
    {
        // dd($request->request);
        $validated = $request->validate([
            'nome' => 'required',
            'razaoSocial' => 'max:255',
            'estruturaLegalDaOrganizacao' => 'required|integer|min:1|max:5',
            'cnpj' => 'required|unique:partners|string|',
            'inscMunicipal' => 'nullable|string',
            'cep' => 'string|max:9',
            'endereco' => 'string|max:255',
            'cidade' => 'string|max:50',
            'estado' => 'string|max:2',
            'bairro' => 'string|max:50',
            'numero' => 'nullable|integer|digits_between:1,6',
            'complemento' => 'nullable|string|max:60',
            'eMailPrincipal' => 'string|max:60',
            'telefonePrincipal' => 'nullable|string|max:16',
            'celularPrincipal' => 'nullable|string|max:16',
            'observacao' => 'nullable|string|max:255',
            'agencia' => 'nullable|string',
            'conta' => 'nullable|string',
            'chavePix' => 'nullable|string',
            'banco' => 'nullable|integer',
            'responsavelPeloPagamento' => 'nullable|string|max:250',
            'telefoneDoResponsavel' => 'nullable|string|max:16',
        ]);

        $partner = new Partner();
        $partner->name = $request->nome;
        $partner->corporate_reason = $request->razaoSocial;
        $partner->organization_type_id = $request->estruturaLegalDaOrganizacao;
        $partner->cnpj = $request->cnpj;
        $partner->municipal_registration = $request->inscMunicipal;
        $partner->cep = $request->cep;
        $partner->address = $request->endereco;
        $partner->city = $request->cidade;
        $partner->state = $request->estado;
        $partner->neighborhood = $request->bairro;
        $partner->number = $request->numero;
        $partner->complement = $request->complemento;
        $partner->email = $request->eMailPrincipal;
        $partner->phone = $request->telefonePrincipal;
        $partner->phone_number = $request->celularPrincipal;
        $partner->observation = $request->observacao;
        $partner->agency = $request->agencia;
        $partner->account = $request->conta;
        $partner->pix = $request->chavePix;
        $partner->bank_id = $request->banco;

        $responsible = new Responsible();
        $responsible->full_name = $request->responsavelPeloPagamento;
        $responsible->phone = $request->telefoneDoResponsavel;
        // $responsible->save();

        // $partner->responsible_id = $responsible->id;
        // $partner->save();

        try {
            // Inicie uma transação
            DB::beginTransaction();
        
            // Salve o modelo $responsible
            $responsible->save();
        
            // Atualize o $partner com a nova foreign key
            $partner->responsible_id = $responsible->id;
            $partner->save();
        
            // Se tudo correr bem, confirme a transação
            DB::commit();
            return redirect()->route('visualizar_parceiro', ['id' => $partner->id])->with('success', 'Parceiro adicionado com sucesso');
            
        } catch (\Exception $e) {
            // Se algo der errado, reverta a transação
            // dd($e);
            DB::rollback();
            return redirect()->back()->withErrors(['Erro ao adicionar parceiro.'])->withInput();
        
            // Aqui você pode lidar com o erro da maneira que desejar, como registrar o erro ou lançar uma exceção personalizada.
        }

    }

    public function show(Partner $id)
    {
        $banks = Bank::all();
        return view('partner/partner-profile', ['partner' => $id, 'banks' => $banks]);
    }

    public function edit(Partner $partner)
    {
        //
    }

    public function update(Request $request, Partner $partner)
    {
        $partner->name = $request->nome;
        $partner->corporate_reason = $request->razaoSocial;
        $partner->organization_type_id = $request->estruturaLegalDaOrganizacao;
        $partner->cnpj = $request->cnpj;
        $partner->municipal_registration = $request->inscMunicipal;
        $partner->cep = $request->cep;
        $partner->address = $request->endereco;
        $partner->city = $request->cidade;
        $partner->state = $request->estado;
        $partner->neighborhood = $request->bairro;
        $partner->number = $request->numero;
        $partner->complement = $request->complemento;
        $partner->email = $request->eMailPrincipal;
        $partner->phone = $request->telefonePrincipal;
        $partner->phone_number = $request->celularPrincipal;
        $partner->observation = $request->observacao;
        $partner->agency = $request->agencia;
        $partner->account = $request->conta;
        $partner->pix = $request->chavePix;
        $partner->bank_id = $request->banco;

        $responsible = $partner->responsible;
        if (!$responsible) {
            $responsible = new Responsible();
        }
    
        $responsible->full_name = $request->responsavelPeloPagamento;
        $responsible->phone = $request->telefoneDoResponsavel;
        $responsible->save();
    
        $partner->responsible_id = $responsible->id;
        $partner->save();

        return redirect()->route('visualizar_parceiro', ['id' => $partner->id])->with('success', 'Parceiro atualizado com sucesso');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('meus_parceiros')->with('success', 'Parceiro excluído com sucesso.');
    }
}
