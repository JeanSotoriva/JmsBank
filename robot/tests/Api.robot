*** Settings ***
Library         RequestsLibrary
Library         Collections
Library         String
Library         SeleniumLibrary
Resource        ../resources/Api.resource

*** Test Cases ***
Iniciar a api e criar session
    Criar Secao

Cadastrar Usuario
    Criar um usuario

Fazer login com o usuario criado
    Logar com o usuario criado e criar token autenticacao

Trazer os dados do usuario logado
    Trazer dados do usuario logado e validado por token

Editar os dados do usuario logado
    Editar dados do usuario logado validado por token

Criar conta bancaria para o usuario logado
    Criar conta bancaria para o usuario logado validado por token

Busca os dados da conta para o usuario logado
    Buscar dados da conta Bancaria para o usuario validado por token

Deposita na conta do usuario logado
    Depositar na conta do usuario validado por token

Pagar com debito na conta do usuario logado
    Pagamento na conta do usuario validado por token

Transeferencia de valor da conta do usuario logado para outra
    Transferencia da conta do usuario para conta destino validado por token