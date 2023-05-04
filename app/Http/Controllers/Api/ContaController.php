<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conta;
use App\Models\User;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Services\ContaService;

class ContaController extends ResponseController
{
    protected $contaService;

    public function __construct(ContaService $contaService)
    {
        $this->contaService = $contaService;
    }
    protected $conta;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($numero)
    {
        return $this->contaService->getConta($numero);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->contaService->createConta($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function depositar(Request $request, $numero)
    {
        return $this->contaService->depositar($request, $numero);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pagar(Request $request, $numero)
    {
        return $this->contaService->pagar($request, $numero);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transferir(Request $request, $numero)
    {
        return $this->contaService->transferir($request, $numero);
    }

}
