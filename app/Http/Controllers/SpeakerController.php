<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CategorySpeaker;
use App\Models\Speaker;
use App\Models\Theme;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::all();
        return view('speaker/speaker-list', ['speakers' => $speakers]);
    }

    public function create()
    {
        $themes = Theme::all();
        $banks = Bank::all();
        return view('speaker/speaker-form', ['banks' => $banks, 'themes' => $themes]);
    }

    public function store(Request $request)
    {
        // dd(strlen($request->cpf));
        $validated = $request->validate([
            'nome' => 'required',
            'temas' => 'required|array',
            'cpf' => [
                'required',
                'unique:speakers',
                'string',
                function ($attribute, $value, $fail) {
                    $cleanedValue = preg_replace('/[^0-9]/', '', $value);

                    if (strlen($cleanedValue) !== 11) {
                        $fail("O campo $attribute deve conter 11 dígitos numéricos.");
                    }
                },
            ],
            'rg' => 'required|unique:speakers|string',
            'cep' => 'string|max:9',
            'endereco' => 'string|max:255',
            'cidade' => 'string|max:50',
            'estado' => 'string|max:2',
            'bairro' => 'string|max:50',
            'numero' => 'nullable|integer|digits_between:1,6',
            'complemento' => 'nullable|string|max:60',
            'eMailPrincipal' => 'string|max:60',
            'celularPrincipal' => 'nullable|string|max:16',
            'observacao' => 'nullable|string|max:255',
            'agencia' => 'nullable|string',
            'conta' => 'nullable|string',
            'chavePix' => 'nullable|string',
            'banco' => 'nullable|integer',
        ]);

        try {
            $speaker = new Speaker();
            $speaker->name = $request->nome;
            $speaker->birth_date = $request->data_nascimento;
            $speaker->cpf = $request->cpf;
            $speaker->rg = $request->rg;
            $speaker->cep = $request->cep;
            $speaker->address = $request->endereco;
            $speaker->city = $request->cidade;
            $speaker->state = $request->estado;
            $speaker->neighborhood = $request->bairro;
            $speaker->number = $request->numero;
            $speaker->complement = $request->complemento;
            $speaker->email = $request->eMailPrincipal;
            $speaker->phone_number = $request->celularPrincipal;
            $speaker->observation = $request->observacao;
            $speaker->agency = $request->agencia;
            $speaker->account = $request->conta;
            $speaker->pix = $request->chavePix;
            $speaker->bank_id = $request->banco;
            $speaker->save();
            $themeIds = $request->temas;
            $speaker->themes()->sync($themeIds);
            return redirect()->route('visualizar_palestrante', ['id' => $speaker->id])->with('success', 'Palestrante adicionado com sucesso');
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('error', 'Erro ao adicionar o palestrante,')->withInput();            
        

        return redirect()->route('visualizar_palestrante', ['id' => $speaker->id])->with('success', 'Palestrante adicionado com sucesso');
    }

    public function show(Speaker $id)
    {
        $themes = Theme::all();
        $banks = Bank::all();
        return view('speaker/speaker-profile', ['speaker' => $id, 'banks' => $banks, 'themes' => $themes]);
    }

    public function edit(Speaker $speaker)
    {
        //
    }

    public function update(Request $request, Speaker $speaker)
    {
        try {
            $speaker->name = $request->nome;
            $speaker->birth_date = $request->data_nascimento;
            $speaker->cpf = $request->cpf;
            $speaker->rg = $request->rg;
            $speaker->cep = $request->cep;
            $speaker->address = $request->endereco;
            $speaker->city = $request->cidade;
            $speaker->state = $request->estado;
            $speaker->neighborhood = $request->bairro;
            $speaker->number = $request->numero;
            $speaker->complement = $request->complemento;
            $speaker->email = $request->eMailPrincipal;
            $speaker->phone_number = $request->celularPrincipal;
            $speaker->observation = $request->observacao;
            $speaker->agency = $request->agencia;
            $speaker->account = $request->conta;
            $speaker->pix = $request->chavePix;
            $speaker->bank_id = $request->banco;
            $speaker->save();
            $themeIds = $request->temas;
            $speaker->themes()->sync($themeIds);
            return redirect()->back()->with('success', 'Palestrante atualizado com sucesso');
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('error', 'Erro ao atualizar o palestrante,')->withInput();
    }

    public function destroy(Speaker $speaker)
    {
        $speaker->delete();
        return redirect()->route('meus_palestrantes')->with('success', 'Palestrante excluído com sucesso.');
    }
}
