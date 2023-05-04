*** Settings ***
Library   Collections
Library   String
Library   RequestsLibrary
Library   SeleniumLibrary
Library   JSONLibrary

*** Variable ***
${URL_API}          http://localhost:8989       
${MSG_CREATE_USER}   User register successfully.
${MSG_LOGIN_USER}   User login successfully.
${MSG_GET_USER}    Dados do Usuário
${MSG_PUT_USER}    Dados Atualizados com Sucesso.
${MSG_CONTA_CREATE}   Conta criada com Sucesso.
${MSG_GET_CONTA}   Aqui esta o numero da conta.
${MSG_DEPOSITO_CONTA}   Deposito Efetuado com Sucesso!
${MSG_PAGAMENTO_CONTA}   Pagamento Efetuado com Sucesso!
${MSG_TRANSFERENCIA_CONTA}   Transferencia Efetuada com Sucesso!

** Keywords ***
Criar Secao
  Create Session    Api   ${URL_API}

Encerrar Secoes
  Delete All Sessions

Criar um usuario
  ${HEADER}     Create Dictionary         content-type=application/json
  ${Response}   POST On Session    Api    ${URL_API}/usuario   expected_status=200   headers=${HEADER}   data={"name":"Jean Teste 2", "email":"JeanTeste2@gmail.com", "password":"123456"}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_CREATE_USER}

Logar com o usuario criado e criar token autenticacao
  ${HEADER}   Create Dictionary   content-type=application/json
  ${Response}   POST On Session    Api     ${URL_API}/usuario/login    expected_status=200   headers=${HEADER}    data={"email":"JeanTeste2@gmail.com","password": "123456"}   
  ${TOKEN}    Get From Dictionary   ${Response.json()["data"]}    token
  Set Suite Variable   ${TOKEN}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_LOGIN_USER}

Trazer dados do usuario logado e validado por token
  ${HEADER}   Create Dictionary   Authorization=Bearer ${TOKEN} 
  Set Suite Variable   ${HEADER}
  ${Response}   GET On Session    Api     ${URL_API}/usuario    expected_status=200   headers=${HEADER} 
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_GET_USER}

Editar dados do usuario logado validado por token
  ${HEADER}   Create Dictionary   content-type=application/json   Authorization=Bearer ${TOKEN} 
  Set Suite Variable   ${HEADER}
  ${Response}   PUT On Session    Api     ${URL_API}/usuario    expected_status=200   headers=${HEADER}    data={"name":"Jean Sotoriva Teste","email":"jeansotorivaEditado@gmail.com","password": "123456"}   
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_PUT_USER}

Criar conta bancaria para o usuario logado validado por token
  ${HEADER}   Create Dictionary   content-type=application/json   Authorization=Bearer ${TOKEN}
  ${Response}   POST On Session    Api    ${URL_API}/conta   expected_status=200   headers=${HEADER}   data={"saldo":"3"}
  ${NUMERO}=    Get Value From Json     ${Response.json()}    $.data
  ${CONTA_CRIADA}=     Set Variable    ${NUMERO[0][1]}
  Set Suite Variable   ${CONTA_CRIADA}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_CONTA_CREATE}

Buscar dados da conta Bancaria para o usuario validado por token
  ${HEADER}   Create Dictionary   Authorization=Bearer ${TOKEN}
  ${Response}   GET On Session    Api    ${URL_API}/conta/${CONTA_CRIADA}     expected_status=200     headers=${HEADER}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_GET_CONTA}

Depositar na conta do usuario validado por token
  ${HEADER}   Create Dictionary   content-type=application/json     Authorization=Bearer ${TOKEN}
  ${Response}   POST On Session    Api     ${URL_API}/conta/${CONTA_CRIADA}/deposito    expected_status=200   headers=${HEADER}    data={"descricao":"Creditando para guardar a grana","valor":"100"}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_DEPOSITO_CONTA}
 
Pagamento na conta do usuario validado por token
  ${HEADER}   Create Dictionary   content-type=application/json     Authorization=Bearer ${TOKEN}
  ${Response}   POST On Session    Api     ${URL_API}/conta/${CONTA_CRIADA}/pagamento    expected_status=200   headers=${HEADER}    data={"descricao":"Debitando para pagar Robot","valor":"50"}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_PAGAMENTO_CONTA}

Transferencia da conta do usuario para conta destino validado por token
  ${HEADER}   Create Dictionary   content-type=application/json     Authorization=Bearer ${TOKEN}
  ${Response}   POST On Session    Api     ${URL_API}/conta/${CONTA_CRIADA}/transferir    expected_status=200   headers=${HEADER}    data={"descricao":"Transferindo dinheiro que devia para voce","valor":"50","destino":"14777"}
  ${msg}    Set Variable    ${Response.json()["message"]}
  Should Be Equal   ${msg}    ${MSG_TRANSFERENCIA_CONTA}