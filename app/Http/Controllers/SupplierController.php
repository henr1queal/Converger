<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CategorySupplier;
use App\Models\Supplier;
use App\Models\Responsible;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('responsible')->get();
        return view('supplier/supplier-list', ['suppliers' => $suppliers]);
    }
    
    public function create()
    {
        $categories = CategorySupplier::all();
        $banks = Bank::all();
        return view('supplier/supplier-form', ['banks' => $banks, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required',
            'categoria' => 'required|integer|digits_between:1,6',
            'estruturaLegalDaOrganizacao' => 'required|integer|max:1',
            'cnpj' => 'required|unique:suppliers|string|',
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
        
        $supplier = new Supplier();
        $supplier->name = $request->nome;
        $supplier->organization_type_id = $request->estruturaLegalDaOrganizacao;
        $supplier->cnpj = $request->cnpj;
        $supplier->municipal_registration = $request->inscMunicipal;
        $supplier->cep = $request->cep;
        $supplier->address = $request->endereco;
        $supplier->city = $request->cidade;
        $supplier->state = $request->estado;
        $supplier->neighborhood = $request->bairro;
        $supplier->number = $request->numero;
        $supplier->complement = $request->complemento;
        $supplier->email = $request->eMailPrincipal;
        $supplier->phone = $request->telefonePrincipal;
        $supplier->phone_number = $request->celularPrincipal;
        $supplier->observation = $request->observacao;
        $supplier->agency = $request->agencia;
        $supplier->account = $request->conta;
        $supplier->pix = $request->chavePix;
        $supplier->bank_id = $request->banco;
        $supplier->category_id = $request->categoria;

        $responsible = new Responsible();
        $responsible->full_name = $request->responsavelPeloPagamento;
        $responsible->phone = $request->telefoneDoResponsavel;
        $responsible->save();

        $supplier->responsible_id = $responsible->id;
        $supplier->save();
        
        return redirect()->route('visualizar_fornecedor', ['id' => $supplier->id])->with('success', 'Fornecedor adicionado com sucesso');
    }

    public function show(Supplier $id)
    {
        $categories = CategorySupplier::all();
        $banks = Bank::all();
        return view('supplier/supplier-profile', ['supplier' => $id, 'banks' => $banks, 'categories' => $categories]);
    }

    public function categories()
    {
        $categories = CategorySupplier::all();
        
        return view('supplier/supplier-category-manage', compact('categories'));
    }

    public function newCategory(Request $request)
    {
        $categorySupplier = new CategorySupplier();
        $categorySupplier->name = $request->nome;
        $categorySupplier->save();
        
        return redirect()->route('categorias')->with('success', 'Categoria de fornecedores adicionada');
    }
    
    public function deleteCategory(CategorySupplier $categorySupplier)
    {
        $categorySupplier->delete();
        
        return redirect()->route('categorias')->with('success', 'Categoria deletada.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->name = $request->nome;
        $supplier->organization_type_id = $request->estruturaLegalDaOrganizacao;
        $supplier->cnpj = $request->cnpj;
        $supplier->municipal_registration = $request->inscMunicipal;
        $supplier->cep = $request->cep;
        $supplier->address = $request->endereco;
        $supplier->city = $request->cidade;
        $supplier->state = $request->estado;
        $supplier->neighborhood = $request->bairro;
        $supplier->number = $request->numero;
        $supplier->complement = $request->complemento;
        $supplier->email = $request->eMailPrincipal;
        $supplier->phone = $request->telefonePrincipal;
        $supplier->phone_number = $request->celularPrincipal;
        $supplier->observation = $request->observacao;
        $supplier->agency = $request->agencia;
        $supplier->account = $request->conta;
        $supplier->pix = $request->chavePix;
        $supplier->bank_id = $request->banco;

        $supplier->save();

        return redirect()->route('visualizar_fornecedor', ['id' => $supplier->id])->with('success', 'Fornecedor atualizado com sucesso');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('meus_fornecedores')->with('success', 'Fornecedor excluído com sucesso.');
    }
}
