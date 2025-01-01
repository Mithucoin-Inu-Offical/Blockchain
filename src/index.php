<?php
require_once 'blockchain.php';

$blockchain = new Blockchain();
header('Content-Type: application/json');

// Get the function name from the URL query parameter
$function = $_GET['function'] ?? null;

// Collect other parameters from the URL
$params = $_GET;

// Check if the function is provided
if (!$function) {
    echo json_encode(['error' => 'Mithucoin Blockchain Server error 100: Invalid request format or missing function name.']);
    exit;
}

try {
    switch ($function) {
        case 'getLatestBlock':
            $response = $blockchain->getLatestBlock();
            break;

        case 'createUserAccountNormal':
            $response = $blockchain->createUserAccountNormal(
                $params['firstName'],
                $params['lastName'],
                $params['email'],
                $params['recoveryEmail'],
                $params['password'],
                $params['phone']
            );
            break;

        case 'createMRCZeroToken':
            $response = $blockchain->createMRCZeroToken(
                $params['ownerPublicKey'],
                $params['tokenName'],
                $params['tokenSymbol'],
                $params['totalSupply'],
                $params['URLtokenlogo']
            );
            break;

        case 'transferToken':
            $response = $blockchain->transferToken(
                $params['fromPublicKey'],
                $params['toPublicKey'],
                $params['amount'],
                $params['tokenSymbol']
            );
            break;
        case 'isTokenSymbolTaken':
            $response = $blockchain->isTokenSymbolTaken($params['tokenSymbol']);
            break;
        case 'getUserBalanceSpecificToken':
            $response = $blockchain->getUserBalanceSpecificToken($params['publicKey'], $params['tokenSymbol']);
            break;
        case 'createLPpool':
            $response = $blockchain->createLPpool(
                $params['token1ContractAddress'],
                $params['token2ContractAddress'],
                $params['token_1_symbol'],
                $params['token_2_symbol'],
                $params['token1Amount'],
                $params['token2Amount'],
                $params['ownerPublicKey'],
                $float['minPriceRange'],
                $float['maxPriceRange']
            );
            break;
        case 'removeLPpool':
            $response = $blockchain->removeLPpool(
                $params['token1symbol'],
                $params['token2symbol'],
                $params['ownerPublicKey']
            );
            break;
        case 'getPoolOwner':
            $response = $blockchain->getPoolOwner($params['token1symbol'], $params['token2symbol']);
            break;
        case 'isPoolRemoved':
            $response = $blockchain->isPoolRemoved($params['token1symbol'], $params['token2symbol']);
            break;
        case 'getTokenOwner':
            $response = $blockchain->getTokenOwner($params['tokenSymbol']);
            break;
        case 'getLatestBlockHash':
            $response = $blockchain->getLatestBlockHash();
            break;     
        case 'getUserAllTokens':
            $response = $blockchain->getUserAllTokens($params['publicKey']);
            break;  
        case 'hardwareMining':
            $response = $blockchain->hardwareMining($params['userPublickey'],$params['solvedBlockHash']);
            break;
        case 'getTokenPrice':
            $response = $blockchain->getTokenPrice($params['tokenSymbol']);
            break;
        case 'getTokenSymbol':
            $response = $blockchain->getTokenSymbol($params['contractAddress']);   
            break;
        case 'getAllBlockHashandNames':
            $response = $blockchain->getAllBlockHashandNames();
            break;
        case 'SwapperFunctionLPUsage':
            $response = $blockchain->SwapperFunctionLPusage(
                $params['input_contract'],
                $params['output_contract'],
                $params['input_symbol'],
                $params['output_symbol'],
                $params['input_amount'],
                $params['user_public_key']
            );   
            break;
        case 'getTransactionThings':
            $response = $blockchain->getTransactionFromAndTo($params['transactionHash']);
            break;                
        case 'getLiquidtyPoolInfo':
            $response = $blockchain->getLiquidityPoolInfo($params['token1symbol'], $params['token2symbol']);
            break;
        case 'getSwappingBlockInfo':
            $response = $blockchain->getSwappingBlockInfo($params['blockHash']);
            break;                      
        case 'getTokenTransactionHistory':
            $response = $blockchain->getTokenTransactionHistory($params['tokenSymbol']);
            break;
        case 'getTokenBlockInfo':
            $response = $blockchain->getTokenBlockInfo($params['tokenSymbol']);
            break;    
        case 'getLatestBlockHash'  :
            $response = $blockchain->getLatestBlockHash();
            break;  
        case 'getBlockPreviousHash'  :
            $response = $blockchain->getBlockPreviousHash($params['blockHash']);
            break;
        case 'getBlockIndex'  :
            $response = $blockchain->getBlockIndex($params['blockHash']);
            break;  
        default:
            throw new Exception('Function not recognized.');
    }

    echo json_encode(['success' => true, 'data' => $response]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
