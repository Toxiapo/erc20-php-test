<?php

require __DIR__ . '/vendor/autoload.php'; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$geth = new \EthereumRPC\EthereumRPC('localhost', 8545);
$erc20 = new \ERC20\ERC20($geth);

$contract = getenv(USDT_CONTRACT_ADDRESS);
$payer = getenv(PAYER);
$payee = getenv(PAYEE); 
$amount = "0.01";

$wallet_password = getenv(WALLET_PASSWORD);

$token = $erc20->token($contract);
$token_balance = $token->balanceOf($payer);
$eth_balance = $geth->eth()->getBalance($payer); 
$gas_fee = 0.00004;

echo "Balance: $token_balance {$token->symbol()}\n";
echo "Balance: $eth_balance ETH\n"; 

if(floatval($token_balance) > 0 && floatval($eth_balance) > $gas_fee) { 
  $data = $token->encodedTransferData($payee, $amount);
  $transaction = 
    $geth
      ->personal()
      ->transaction($payer, $contract)
      ->amount("0")
      ->data($data);

   //$txId = $transaction->send($wallet_password); 

   //var_dump($txId);
}

