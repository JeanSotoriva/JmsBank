<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use Illuminate\Http\Request;

class UserRepository extends ResponseController
{
    protected $entity;
    protected $user;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }

    public function getUsuario($user)
    {
        return $this->sendResponse($user, 'Dados do Usuário');
    }

    public function updateUsuario(Request $request)
    {
        $user = Auth::user();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->save();
        return $this->sendResponse(Auth::user(), 'Dados Atualizados com Sucesso.');
    }

}