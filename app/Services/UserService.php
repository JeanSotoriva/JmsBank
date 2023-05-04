<?php 

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use Illuminate\Support\Facades\Auth;


class UserService extends ResponseController
{

    protected $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function getUsuario($user)
    {
        return $this->repository->getUsuario($user);
    }

    public function updateUsuario(Request $request)
    {
        return $this->repository->updateUsuario($request);
    }

    
}