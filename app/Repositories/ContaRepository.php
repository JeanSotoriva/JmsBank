<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\User;
use App\Models\Lancamento;
use App\Models\Conta;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Symfony\Component\VarDumper\Cloner\Data;

class ContaRepository extends ResponseController
{
    protected $entity;

    public function __construct(Conta $conta)
    {
        $this->entity = $conta;
    }

    public function getConta($numero, $id)
    {
        $userConta = $this->entity->where('usuario_id', $id)->first();
        if($numero == $userConta->numero){
            return $this->sendResponse([
                $userConta->nome,
                $userConta->numero,
                $userConta->saldo,
                $userConta->created_at,
                
            ], 'Aqui esta o numero da conta.');
        }else{
            return $this->sendError(['Voce nao e o dono dessa conta']);
        }
    }

    public function createConta(Request $request, $id)
    {
        $user = User::find($id);
        $conta_gerada = $this->gerarConta();

        $this->entity->create([
            'usuario_id' => $id,
            'numero' => $conta_gerada,
            'nome' => $user->name,
            'saldo' => $request['saldo'],
        ]);
        Lancamento::create([
            'numero_conta' => $conta_gerada,
            'descricao' => 'Saldo Inicial',
            'tipo' => 'credito', 
            'valor' => $request->saldo,
        ]);
        return $this->sendResponse([
            $user->name,
            $conta_gerada,
            $user->created_at,
        ], 'Conta criada com Sucesso.');
    }

    public function depositar(Request $request, $numero)
    {
        $id = Auth::user()->id;
        $conta = $this->entity->where('usuario_id', $id)->first();
        $validacaoConta = $this->validaConta($numero, $conta);
        if(!empty($validacaoConta)){
            return $validacaoConta;
        }
        $deposito = $request['valor'];
        $descricao = $request['descricao'];
        $conta->saldo += $deposito;
        $conta->save();
        $create = Lancamento::create([
            'numero_conta' => $conta->numero,
            'descricao' => $descricao,
            'tipo' => 'credito',
            'valor' => $request->valor,
        ]);
        return $this->sendResponse([
            $deposito,
            $descricao,
            $create->created_at,
        ], 'Deposito Efetuado com Sucesso!');
    }

    public function pagar(Request $request, $numero)
    {
        $id = Auth::user()->id;
        $conta = $this->entity->where('usuario_id', $id)->first();
        $validacaoConta = $this->validaConta($numero, $conta);
        if(!empty($validacaoConta)){
            return $validacaoConta;
        }
        $validaValor = $this->validaValor($request, $conta);
        if(!empty($validaValor)){
            return $validaValor;
        }
        $pagamento = $request['valor'];
        $descricao = $request['descricao'];
        $conta->saldo -= $pagamento;
        $conta->save();
        $create = Lancamento::create([
            'numero_conta' => $conta->numero,
            'descricao' => $descricao,
            'tipo' => 'debito',
            'valor' => -abs($request->valor),
        ]);
        return $this->sendResponse([
            $pagamento,
            $descricao,
            $create->created_at,
        ], 'Pagamento Efetuado com Sucesso!');
    }

    public function transferir(Request $request, $numero)
    {
        $contaOrigem = $this->entity->where('numero', $numero)->first();
        $contaDestino = $this->entity->where('numero', $request->destino)->first();
        $validacao = $this->validaTransferencia($contaOrigem, $contaDestino, $request);
        if(!empty($validacao)){
            return $validacao;
        }
        $valor = $request['valor'];
        $descricao = $request['descricao'];
        $contaDestino->saldo += $valor;
        $contaOrigem->saldo -= $valor;
        $contaOrigem->save();
        $contaDestino->save();
        Lancamento::create([
            'numero_conta' => $contaDestino->numero,
            'descricao' => $descricao,
            'tipo' => 'credito',
            'valor' => $valor,
        ]);  
        Lancamento::create([
            'numero_conta' => $contaOrigem->numero,
            'descricao' => $descricao,
            'tipo' => 'debito',
            'valor' => -abs($valor),
        ]);
        return $this->sendResponse([
            $valor ,
            $descricao,
        ], 'Transferencia Efetuada com Sucesso!');
    }

    public function gerarConta()
    {
        do { 
            $conta_gerada = rand(1, 99999);
        }while($this->entity->where('numero', $conta_gerada)->first());
        return $conta_gerada;
    }

    public function validaConta($numero, $conta)
    {   
        if($numero != $conta->numero){
            return $this->sendError(['Voce nao e o dono dessa conta']);
        }
    }

    public function validaValor($request, $conta)
    {
        if($request->valor <= 0){
            return $this->sendError(['Voce nao pode depositar valores negativos!']);
        }
        if($request->valor > $conta->saldo){
            return $this->sendError(['Voce nao pode pagar ou transferir valores maiores que seu saldo!']);
        }
    }

    public function validaTransferencia($contaOrigem, $contaDestino, $request){
        $contaExistente = $this->entity->where('numero', $request->destino)->first();
        if($contaExistente == null){
            return $this->sendError(['Conta de destino nao existe!']);
        }
        if(($request->valor <= 0) || ($contaOrigem->saldo < $request->valor)){
            return $this->sendError(['Voce nao pode transferir valores negativos ou acima do seu saldo']);
        }
        if($contaOrigem->numero == $contaDestino->numero){
            return $this->sendError(['Você não pode transferir para a mesma conta']);
        }
    }
}