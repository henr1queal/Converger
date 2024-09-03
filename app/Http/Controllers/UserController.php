<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::join('positions', 'users.position_id', '=', 'positions.id')
            ->where('users.position_id', '<>', 1)
            ->where('users.status', 1)
            ->orderBy('positions.name', 'asc')
            ->orderBy('name', 'asc')
            ->select('users.*', 'positions.name as position_name') // Alias para a coluna 'name' da tabela 'positions'
            ->get();
        return view('user/user-list', ['users' => $users]);
    }

    public function pending()
    {
        $users = User::where('status', 0)->with('position')->get();
        return view('user/pending', ['users' => $users]);
    }

    public function feedback(User $user, $value)
    {
        if ($value == 0) {
            $user->delete();
            return redirect()->back()->with(['success' => 'Usuário rejeitado e deletado com sucesso.']);
        } else {
            $user->status = 1;
            $user->created_at = now();
            $user->save();
            return redirect()->route('visualizar_colaborador', $user->id)->with(['success' => $user->name . ' agora é um colaborador ativo.']);
        }
    }

    public function main()
    {
        $user = Auth::user();
        if ($user !== null && $user->status === 1) {
            $name_user = Auth()->user()->name;
            return view('start.start', ['name_user' => $name_user]);
        } else {
            return view('auth.login');
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        if($user->status !== 0) {
            $positions = Position::all();
            $features = Feature::orderBy('type', 'asc')->get();
            return view('user/user-profile', compact('user', 'positions', 'features'));
        }
        return redirect()->route('meus_colaboradores_pendentes')->with('error', 'O colaborador precisa ser aceito para visualizar o perfil.');
    }
    
    public function edit(string $id)
    {
        //
    }
    
        public function update(User $user, Request $request)
        {
            // dd($request->request);
            $user->name = $request->nome;
            $user->position_id = $request->cargo;
            $user->status = $request->status;
            $user->features()->sync($request->permissions);
            $user->save();
            return redirect()->back()->with('success', 'Colaborador atualizado com sucesso!');
        }

    public function destroy(string $id)
    {
        //
    }
}
