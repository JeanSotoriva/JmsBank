<?php 

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\User;
use App\Models\Lancamento;
use App\Models\Conta;
use App\Http\Controllers\Controller;
use App\Repositories\ContaRepository;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Symfony\Component\VarDumper\Cloner\Data;

class ContaService extends ResponseController
{

    protected $repository;

    public function __construct(ContaRepository $contaRepository)
    {
        $this->repository = $contaRepository;
    }

    public function getConta($numero)
    {
        $id = Auth::user()->id;
        return $this->repository->getConta($numero, $id);
    }

    public function createConta(Request $request)
    {
        $response = $this->validaSaldo($request);
        if(!empty($response)){
            return $response;
        }
        $id = Auth::user()->id;
        return $this->repository->createConta($request, $id);
    }

    public function depositar(Request $request, $numero)
    {
        $validaSaldo = $this->validaValor($request);
        if(!empty($validaSaldo)){
            return $validaSaldo;
        }
        return $this->repository->depositar($request, $numero);
    }

    public function pagar(Request $request, $numero)
    {
        $validaValor = $this->validaValor($request);
        if(!empty($validaValor)){
            return $validaValor;
        }
        return $this->repository->pagar($request, $numero);
    }

    public function transferir(Request $request, $numero)
    {
        return $this->repository->transferir($request, $numero);
    }

    public function validaSaldo(Request $request)
    {   
        if($request->saldo < 0){
            return $this->sendError(['Transação Negada! Saldo inicial negativo!']);
        }
    } 
    public function validaValor(Request $request)
    {
        if($request->valor <= 0){
            return $this->sendError(['Voce nao pode depositar ou pagar com valores negativos!']);
        }
    }
}