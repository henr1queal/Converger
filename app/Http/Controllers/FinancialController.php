<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financial_entries = Financial::orderBy('date', 'desc')->where('id', '!=', 1)->get();

        $financial_values_per_month = Financial::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(value) as total_value')
            ->where('id', '!=', 1)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $my_value = Financial::select(['value', 'updated_at'])->findOrFail(1);

        $total_sum_open = Financial::where('id', '!=', 1)
            ->where('status', 0)
            ->sum('value');
        
        $total_sum_to_paid = Financial::where('id', '!=', 1)
            ->where('status', 1)
            ->sum('value');


        return view('financial.financial-resume', compact(['financial_entries', 'total_sum_open', 'total_sum_to_paid', 'my_value', 'financial_values_per_month']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateValue(Request $request)
    {
        $my_value = Financial::find(1);
        if($request->valor) {
            $my_value->value = $request->valor;
        } else {
            $my_value->value = 0.00;
        }
        $my_value->updated_at = now();
        $my_value->save();
        return redirect()->route('meu_financeiro')->with('success', 'Valor de caixa atualizado com sucesso!');
    }
    public function store(Request $request)
    {
        $cost = new Financial();
        $cost->value = $request->valor;
        $cost->description = $request->descricao;
        $cost->date = $request->data;
        $cost->save();
        return redirect()->back()->with('success', 'Despesa adicionada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Financial $financial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Financial $financial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Financial $financial)
    {
        //
    }

    public function updateStatus(Financial $financial)
    {
        if ($financial->status === 1) {
            $financial->status = 0;
            $success = 'A despesa foi declarada como não paga.';
        } else {
            $financial->status = 1;
            $success = 'A despesa foi declarada como paga.';
        }

        $financial->save();
        return redirect()->back()->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Financial $financial)
    {
        $financial->delete();
        return redirect()->back()->with('success', 'Despesa deletada com sucesso.');
    }
}
