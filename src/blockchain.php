<?php
require_once 'block.php';

class Blockchain
{
    public float $gas_fee = 0.0001;
    public string $file_blockchain = "data1/data2/data3/data4/data5/data6.json";
    //  public string $file_blockchain = "wYEB9sCsGdRiBKPbnWlQw0EKM62rHVzToMJuYI9RAJaGa0lBeR0G0JzGWv2NvJDv87f/ZrhBXwE9W8E8Kr791aR4qbEqaHnnFHHwfD6loc3a2H9o2Z8ki2ITzzyhOy5xMBwT9gu/196YFiLvqdadk8lfiiVSDtMvE9Xn3HLZwUkb3POBwItLNLZIHkUofs5OWnmawLL0c34/fYjraj9JwQbhIAqRdTBYgk1lgrgFRPNxPrUIPT1Yq0UvgOINPxurB9EgW9LDOuolaDv/oZYicYaPD9qMTbCpmudh1sF0c4lKzDI0reOp1Xa3N1EnZS7FkpIUEFYmxbYK2tJmBlm/aN5bdWHi7lYWzYXkSI81NUwGXAnaxPoQqOTn17mFGZvtTM3C2MFw9UcsNYbVo34u7Uq.json";
    public array $chain;
    public float $difficulty = 898.1304584;
    public float $reward = 0.0106;
    // public string $super_account = "zxlr5Mau1XC9GFpqMbCo0yKG319IJg5eyWmbXlgtAR3VngcKUVeEmwuOUN6wTL4L1p4";
    // public string $test_account = "0x1jbdbmdhbfuu4bnddddbm124ramdomstring:d";

    public function __construct()
    {
        if (!file_exists($this->file_blockchain)) {
            $this->BlockchainMake();
            $this->createGenesisBlock();
            $this->createSuperAccount();
            $this->createTestAccount(); // for testing newly created transaction function
            $this->createTokenWithGivenContractAddress(
                "xKaHV8OXkFvYonrkQrSIv5ZkCkAdV31j26bznVlgmIRkRIcVUE3qK76tlVCMCAt3ISx",
                "Mithucoin Inu",
                "MITHUW",
                5000000000009,
                "zxlr5Mau1XC9GFpqMbCo0yKG319IJg5eyWmbXlgtAR3VngcKUVeEmwuOUN6wTL4L1p4"
            );
            $this->createTokenWithGivenContractAddress(
                "B3gV3kSzF9qGRcEzNDpT3pfjxnxcYXlDFci3eGYvrKLTS94SVs6kX5sNUxlhdEmsup6",
                "Mithucoin Wrapped Inu",
                "WMITHUW",
                5000000000000,
                "zxlr5Mau1XC9GFpqMbCo0yKG319IJg5eyWmbXlgtAR3VngcKUVeEmwuOUN6wTL4L1p4"
            );
            // Testings goes here (pool token 1 symbol is WMITHUW)
            $this->createLPpool("B3gV3kSzF9qGRcEzNDpT3pfjxnxcYXlDFci3eGYvrKLTS94SVs6kX5sNUxlhdEmsup6", "xKaHV8OXkFvYonrkQrSIv5ZkCkAdV31j26bznVlgmIRkRIcVUE3qK76tlVCMCAt3ISx", "WMITHUW", "MITHUW", 50, 50, "zxlr5Mau1XC9GFpqMbCo0yKG319IJg5eyWmbXlgtAR3VngcKUVeEmwuOUN6wTL4L1p4", 0.99, 1.01);
            
        } else {
            $this->loadBlockchain();
        }
    }

    private function createGenesisBlock(): void
    {
        $genesisBlock = new Block(0, date("Y-m-d H:i:s"), "Genesis Block", '0');
        $this->chain[] = $genesisBlock;
        $this->saveBlockchain();
    }

    public function getLatestBlock(): Block
    {
        return $this->chain[count($this->chain) - 1];
    }

    

    public function addBlock(Block $newBlock): void
    {
        $newBlock->previousHash = $this->getLatestBlock()->hash;
        $newBlock->hash = $newBlock->calculateHash();
        $this->chain[] = $newBlock;
        $this->saveBlockchain();
    }

    public function isChainValid(): bool
    {
        for ($i = 1; $i < count($this->chain); $i++) {
            $currentBlock = $this->chain[$i];
            $previousBlock = $this->chain[$i - 1];

            if ($currentBlock->hash !== $currentBlock->calculateHash()) return false;
            if ($currentBlock->previousHash !== $previousBlock->hash) return false;
        }
        return true;
    }

    public function createUserAccount(string $email, string $recoveryEmail, string $password, string $phone): string
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "User Account Created", $this->getLatestBlock()->hash);
        $block->user_public_key = bin2hex(random_bytes(32));
        $block->user_signature = bin2hex(random_bytes(32));
        $block->user_email_address = $email;
        $block->user_recovery_email_address = $recoveryEmail;
        $block->user_password = password_hash($password, PASSWORD_BCRYPT);
        $block->user_recovery_phone_number = $phone;
        $this->addBlock($block);
        return $block->user_public_key;
        return $block->user_signature;

    }

    public function createUserAccountNormal(string $firstName,string $lastName,string $email, string $recoveryEmail, string $password, string $phone): string
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "User Account Created", $this->getLatestBlock()->hash);
        $block->user_public_key = bin2hex(random_bytes(32));
        $block->user_signature = bin2hex(random_bytes(32));
        $block->user_email_address = $email;
        $block->user_recovery_email_address = $recoveryEmail;
        $block->user_password = password_hash($password, PASSWORD_BCRYPT);
        $block->user_recovery_phone_number = $phone;
        $block->user_first_name = $firstName;
        $block->user_last_name = $lastName;
        $this->addBlock($block);

        return $block->user_public_key;
        return $block->user_signature;

    }

    public function createSuperAccount():void
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Super Account Afif Created", $this->getLatestBlock()->hash);
        $block->user_public_key = "zxlr5Mau1XC9GFpqMbCo0yKG319IJg5eyWmbXlgtAR3VngcKUVeEmwuOUN6wTL4L1p4";
        $block->user_signature = "mT7BHaUUDu0pntsj1xZUZcdrl3nZiC9p90GcHwuS28yWrJEnxbOjmo6fpbWNUYFR4k4";
        $block->user_email_address = "admin@mithucoin.zya.me";
        $block->user_recovery_email_address = "afifsaadman2013@gmail.com";
        $block->user_password = password_hash("1234567890", PASSWORD_BCRYPT);
        $block->user_recovery_phone_number = "017000000";
        $this->addBlock($block);
    }

    public function createTestAccount(): void
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Test Account Created", $this->getLatestBlock()->hash);
        $block->user_public_key = "0x1jbdbmdhbfuu4bnddddbm124ramdomstring:d";
        $block->user_signature = "0x1jbdbmdhbfuu4bnddddbm124ramdomstring:d";
        $block->user_email_address = "Olufemi@gmail.com";
        $block->user_recovery_email_address = "ohiofemi@gmail.com";
        $block->user_password = password_hash("Olufemi123456", PASSWORD_BCRYPT);        
        $block->user_recovery_phone_number = "+12345559584";
        $this->addBlock($block);
    }

    public function createTokenWithGivenContractAddress(
        string $contractAddress,
        string $name,
        string $symbol,
        int $totalSupply,
        string $ownerPublicKey
    ): void {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Token Created", $this->getLatestBlock()->hash);
        $block->mrczero_token_contract_address = $contractAddress;
        $block->mrczero_token_name = $name;
        $block->mrczero_token_symbol = $symbol;
        $block->mrczero_token_total_supply = $totalSupply;
        $block->mrczero_token_owner = $ownerPublicKey;
        $this->addBlock($block);
        // Add the total supply to the owner's balance
        $this->updateUserBalance($ownerPublicKey, $symbol, $totalSupply);
    }

    public function createMRCZeroToken(string $ownerPublicKey, string $tokenName, string $tokenSymbol, int $totalSupply, string $URLtokenlogo): string
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Token Created", $this->getLatestBlock()->hash);
        $block->mrczero_token_contract_address = bin2hex(random_bytes(32));
        $block->mrczero_token_name = $tokenName;
        // Check if the token symbol is already taken
        if ($this->isTokenSymbolTaken($tokenSymbol)) {
            throw new Exception("Token symbol is already taken.");
        }
        $block->mrczero_token_symbol = $tokenSymbol;
        $block->mrczero_token_total_supply = $totalSupply;
        $block->mrczero_token_owner = $ownerPublicKey;
        $block->mrczero_token_logo = $URLtokenlogo;
        $this->addBlock($block);
        // Add the total supply to the owner's balance
        $this->updateUserBalance($ownerPublicKey, $tokenSymbol, $totalSupply);
        return $block->mrczero_token_contract_address;

    }

    
    // Transfer Token (This is an simple implementation, hardr and secured implementation was done in the original file)
    public function transferToken(
        string $fromPublicKey,
        string $toPublicKey,
        float $amount,
        string $tokenSymbol
    ): float {
        // Check if sender has enough balance
        $balanceFrom = $this->getUserBalanceSpecificToken($fromPublicKey, $tokenSymbol);
        if ($balanceFrom < $amount) {
            throw new Exception("Insufficient balance for the transaction.");
        }

        // Create the transaction block
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Token Transfer", $this->getLatestBlock()->hash);
        $block->transaction_token_symbol = $tokenSymbol;
        $block->transaction_amount = $amount;
        $block->transaction_from = $fromPublicKey;
        $block->transaction_to = $toPublicKey;
        $this->addBlock($block);

        // Update the balances
        $fromPreviousBalance = $this->getUserBalanceSpecificToken($fromPublicKey, $tokenSymbol);
        $toPreviousBalance = $this->getUserBalanceSpecificToken($toPublicKey, $tokenSymbol);

        $fromNewAmount = $fromPreviousBalance - $amount - $this->gas_fee;
        $toNewAmount = $toPreviousBalance + $amount;

        $this->updateUserBalance($fromPublicKey, $tokenSymbol, $fromNewAmount);
        $this->updateUserBalance($toPublicKey, $tokenSymbol, $toNewAmount);

        return $amount;
       
    }

   // Check if token symbol is already taken
public function isTokenSymbolTaken(string $tokenSymbol): bool
{
    // Iterate over all blocks in the blockchain
    foreach ($this->chain as $block) {
        // Ensure the block has the correct structure and the token symbol exists
        if (isset($block->mrczero_token_symbol) && $block->mrczero_token_symbol === $tokenSymbol) {
            return true; // Token symbol is taken
        }
    }

    // Return false if no matching token symbol is found
    return false;
}

    
    public function transferGaslessAuthoredTokenMined(
        string $fromPublicKey,
        string $toPublicKey,
        float $amount,
        string $tokenSymbol
    ): float {
        // Check if sender has enough balance
        $balanceFrom = $this->getUserBalanceSpecificToken($fromPublicKey, $tokenSymbol);
        if ($balanceFrom < $amount) {
            throw new Exception("Insufficient balance for the transaction.");
        }

        // Create the transaction block
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Token Transfer Mining Reward", $this->getLatestBlock()->hash);
        $block->transaction_token_symbol = $tokenSymbol;
        $block->transaction_amount = $amount;
        $block->transaction_from = $fromPublicKey;
        $block->transaction_to = $toPublicKey;
        $this->addBlock($block);

        // Update the balances
        $fromPreviousBalance = $this->getUserBalanceSpecificToken($fromPublicKey, $tokenSymbol);
        $toPreviousBalance = $this->getUserBalanceSpecificToken($toPublicKey, $tokenSymbol);

        $fromNewAmount = $fromPreviousBalance - $amount;
        $toNewAmount = $toPreviousBalance + $amount;

        $this->updateUserBalance($fromPublicKey, $tokenSymbol, $fromNewAmount);
        $this->updateUserBalance($toPublicKey, $tokenSymbol, $toNewAmount);

        return $toNewAmount;
       
    }

    private function updateUserBalance(string $publicKey, string $tokenSymbol, float $amount): void
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Update Balance", $this->getLatestBlock()->hash);
        $block->user_public_key = $publicKey;
        $block->user_balance_specific_token_balance = $amount;
        $block->user_balance_specific_token_symbol = $tokenSymbol;
        $this->addBlock($block);
        
    }

    public function getUserBalanceSpecificToken(string $publicKey, string $tokenSymbol): float
    {
       // Get user balance
        $balance = 0.00;
        foreach ($this->chain as $block) {
            if ($block->user_public_key === $publicKey && $block->user_balance_specific_token_symbol === $tokenSymbol) {
                $balance = $block->user_balance_specific_token_balance;
            }
        }
        return $balance;
    }

    public function BlockchainMake(): void
    {
        $dir1= 'data1';
        $dir2= 'data2';
        $dir3= 'data3';

        $dir4= 'data4';
        $dir5= 'data5';
        $file = 'data6.json';
        // Create directories recursively
        $dirPath = "$dir1/$dir2/$dir3/$dir4/$dir5";
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true); // Ensure directories are created recursively
        }
        touch("$dirPath/$file");
    }

    public function loadBlockchain(): void
    {
        $data = file_get_contents($this->file_blockchain);
        $json = json_decode($data, true);
        $this->chain = [];

        if (isset($json['chain']) && is_array($json['chain'])) {
            foreach ($json['chain'] as $blockData) {
                $this->chain[] = Block::fromArray($blockData);
            }
        }
    }


    // Save blockchain to file
    public function saveBlockchain(): void
    {
        // Create the directories if they don't exist
        $dirPath = dirname($this->file_blockchain);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true); // Create directories recursively
        }

        // Save the blockchain data to the file
        $data = ['chain' => array_map(fn($block) => $block->toArray(), $this->chain)];
        file_put_contents($this->file_blockchain, json_encode($data, JSON_PRETTY_PRINT));
    }



    // Liquidity functions goes here

    public function createLPpool (
        string $token1ContractAddress,
        string $token2ContractAddress,
        string $token1Symbol,
        string $token2Symbol,
        float $token1Amount,
        float $token2Amount,
        string $ownerPublicKey,
        float $minPriceRange,
        float $maxPriceRange
    ): void {

       // Check balance
       $balanceToken1 = $this->getUserBalanceSpecificToken($ownerPublicKey, $token1Symbol);
         $balanceToken2 = $this->getUserBalanceSpecificToken($ownerPublicKey, $token2Symbol);
         // Check if token 2 is MITHUW, if not, throw an nuclear error.
            if ($token2Symbol !== "MITHUW") {
                throw new Exception("Token 2 must be MITHUW.");
            }

        if ($balanceToken1 < $token1Amount || $balanceToken2 < $token2Amount) {
            throw new Exception("Insufficient balance for the transaction.");
        }

        // Create the LP pool block
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "LP Pool Created", $this->getLatestBlock()->hash);
        $block->liquidity_token_1_contract_address = $token1ContractAddress;
        $block->liquidity_token_2_contract_address = $token2ContractAddress;
        $block->liquidity_token_1_symbol = $token1Symbol;
        $block->liquidity_token_2_symbol = $token2Symbol;
        $block->liquidity_token_1_amount = $token1Amount;
        $block->liquidity_token_2_amount = $token2Amount;
        $block->liquidity_pool_owner = $ownerPublicKey;
        $this->addBlock($block);

        // Update owner's balance (Token 2 will be used for the price, cut the gass fees according to it)
        
        $userToken1PreviousBalance = $this->getUserBalanceSpecificToken($ownerPublicKey, $token1Symbol);
        $userToken2PreviousBalance = $this->getUserBalanceSpecificToken($ownerPublicKey, $token2Symbol);
        $userToken1NewAmount = $userToken1PreviousBalance - $token1Amount;
        $userToken2NewAmount = $userToken2PreviousBalance - $token2Amount - $this->gas_fee;
        $this->updateUserBalance($ownerPublicKey, $token1Symbol, $userToken1NewAmount);
        $this->updateUserBalance($ownerPublicKey, $token2Symbol, $userToken2NewAmount);

        // Making the price for the token 1 according to the range using the random function
        $price = $this->custom_rand($minPriceRange, $maxPriceRange);
        $block->liquidity_pool_token_1_price = $price;
        $this->addBlock($block);
    }

    public function custom_rand(float $min, float $max): float
    {
        return $min + lcg_value() * abs($max - $min);
    }
    // Remove Liquidity
    public function removeLPpool(string $token1Symbol, string $token2Symbol, string $PublicKey) : void
    {
        // Check is the tokens have liquidity pool for token1 symbol price, if zero, then throw an error
        $price = $this->getTokenPrice($token1Symbol);
        if ($price === 0.00) {
            throw new Exception("No liquidity pool found for the token symbol.");
        }

        // Check if the user is the token of the liquidity
        $owner = $this->getPoolOwner($token1Symbol, $token2Symbol);
        if ($owner !== $PublicKey) {
            throw new Exception("You are not the owner of the liquidity pool.");
        }

        // Set the token 1 price to zero
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "LP Pool Removed", $this->getLatestBlock()->hash);
        $block->liquidity_token_1_symbol = $token1Symbol;
        $block->liquidity_token_2_symbol = $token2Symbol;
        $block->liquidity_pool_owner = $PublicKey;
        $block->liquidity_pool_token_1_price = 0.00;
        $this->addBlock($block);

        // Update the owner's balance
        $userToken1PreviousBalance = $this->getUserBalanceSpecificToken($PublicKey, $token1Symbol);
        $userToken2PreviousBalance = $this->getUserBalanceSpecificToken($PublicKey, $token2Symbol);
        $userToken1NewAmount = $userToken1PreviousBalance + $block->liquidity_token_1_amount;
        $userToken2NewAmount = $userToken2PreviousBalance + $block->liquidity_token_2_amount;
        $this->updateUserBalance($PublicKey, $token1Symbol, $userToken1NewAmount);
        $this->updateUserBalance($PublicKey, $token2Symbol, $userToken2NewAmount);


        
    }

    // Is pool removed
    public function isPoolRemoved(string $token1Symbol, string $token2Symbol): bool
    {
        // Check if block name is LP Pool Removed
        foreach ($this->chain as $block) {
            if ($block->liquidity_token_1_symbol === $token1Symbol && $block->liquidity_token_2_symbol === $token2Symbol) {
                if ($block->data === "LP Pool Removed") {
                    return true;
                }
            }
        }
        return false;
    }
    
    // Get pool owner
    public function getPoolOwner(string $token1Symbol, string $token2Symbol): string
    {
        // Check if the pool is removed
        if ($this->isPoolRemoved($token1Symbol, $token2Symbol)) {
            throw new Exception("The pool is removed.");
        }

        $owner = "";
        foreach ($this->chain as $block) {
            if ($block->liquidity_token_1_symbol === $token1Symbol && $block->liquidity_token_2_symbol === $token2Symbol) {
                $owner = $block->liquidity_pool_owner;
            }
        }
        return $owner;

    }

    // Get token owner
    public function getTokenOwner(string $tokenSymbol): string
    {
        $owner = "";
        foreach ($this->chain as $block) {
            if ($block->mrczero_token_symbol === $tokenSymbol) {
                $owner = $block->mrczero_token_owner;
            }
        }
        return $owner;
    }

    public function dumpBlockchainInJSON(): array
    {
        $chainwhole = $this->chain;
        echo json_encode($chainwhole, JSON_PRETTY_PRINT);
        return $chainwhole;
    }

    public function dumpBlockchainInHumanReadableText(): void
    {
        foreach ($this->chain as $block) {
            echo "Block Hash" . $block->index . "\n";
            echo "Timestamp: " . $block->timestamp . "\n";
            echo "Data: " . $block->data . "\n";
            echo "Previous Hash: " . $block->previousHash . "\n";
            echo "Hash: " . $block->hash . "\n";
            echo "\n";
        }
    }
    //  Mining
    public function miningAlgorithm(Block $block) :string
    {
        // Mining algorithm (Proof of work)
        $block->nonce = 0;

        while (substr($block->hash, 0, $this->difficulty) !== str_repeat("0", $this->difficulty)) {
            $block->nonce++;
            $block->hash = $block->calculateHash();
        }

        return $block->hash;
    }

    public function miningReward(string $user_public_key) :void
    {
        
        $this->transferGaslessAuthoredTokenMined("zxlr5Mau1XC9GFpqMbCo0yKG319IJg5eyWmbXlgtAR3VngcKUVeEmwuOUN6wTL4L1p4",$user_public_key, $this->reward,"MITHUW");
    }

    public function miningAutomated(string $user_public_key): string
    {
        
        $latest_block = $this->getLatestBlock();
        $new_block = new Block($latest_block->index + 1, date("Y-m-d H:i:s"), "Mining block", $latest_block->hash);
        $this->miningAlgorithm($new_block);
        $this->miningReward($user_public_key);
        $this->addBlock($new_block);
        return $new_block->hash;

    }
    // Get latest block hash
    public function getLatestBlockHash(): string
    {
        return $this->getLatestBlock()->hash;
    }
    // Get latest block data and index
    public function getLatestBlockDataAndIndex(): array
    {
        $latestBlock = $this->getLatestBlock();
        return ['index' => $latestBlock->index, 'data' => $latestBlock->data];
    }
    // Get how many tokens a user own, & return their symbol
    public function getUserAllTokens(string $publicKey): array
    {
        $tokens = [];
        foreach ($this->chain as $block) {
            if ($block->user_public_key === $publicKey) {
                $tokens[] = $block->user_balance_specific_token_symbol;
            }
        }
        return $tokens;
    }
    // Get all block's hash & names
public function getAllBlockHashandNames(): array
{
    $hashes = [];
    $datas = [];
    
    foreach ($this->chain as $block) {
        $hashes[] = $block->hash;  // Add the block's hash to the array
        $datas[] = $block->data;   // Add the block's data to the array
    }

    return ['hashes' => $hashes, 'data' => $datas];  // Return both arrays as an associative array
}
 
 // Get transaction from and to
public function getTransactionFromAndTo(string $blockHash): array
{
    // Initialize the 'from', 'to', and 'amount' variables
    $from = "";
    $to = "";
    $amount = 0; // Assuming the transaction amount is numeric

    // Loop through the blockchain to find the block with the given hash
    foreach ($this->chain as $block) {
        if ($block->hash === $blockHash) {
            $from = $block->transaction_from;
            $to = $block->transaction_to;
            $amount = $block->transaction_amount; // Add the amount
            break; // Exit the loop once the block is found
        }
    }

    // Return the 'from', 'to', and 'amount' values
    return ['from' => $from, 'to' => $to, 'amount' => $amount];
}

    // hardware mining
    public function hardwareMining(string $user_public_key,string $solved_block_hash): string
    {
        $isBlockAccepted = false;
        $latest_block = $this->getLatestBlock();
        $new_block = new Block($latest_block->index + 1, date("Y-m-d H:i:s"), "Mining block", $latest_block->hash);
        $new_block->hash = $solved_block_hash;
       // $this->miningReward($user_public_key);
        $this->addBlock($new_block);
        $this->miningReward($user_public_key);
        return $new_block->hash;
        $isBlockAccepted = true;
        return $this->boolToString($isBlockAccepted);

    }
    public function boolToString(bool $bool): string
    {
        return $bool ? "true" : "false";
    }
    // Receive mined or solved block hash
    public function receiveMinedBlock(string $blockHash): string
    {
        return $blockHash;
    }
 
    // Token price catcher function
    public function getTokenPrice(string $tokenSymbol): float
    {
        // Check if the token's pool is removed
        if ($this->isPoolRemoved($tokenSymbol, "MITHUW")) {
            throw new Exception("The pool is removed.");
        }
        $price = 0.00;
        foreach ($this->chain as $block) {
            if ($block->liquidity_token_1_symbol === $tokenSymbol) {
                    $price = $block->liquidity_pool_token_1_price;
            }
        }
        return $price;
    }

    // Object to string
    public function objToStr($obj): string
    {
        return json_encode($obj);
    }

    // String to object
    public function strToObj($str): object
    {
        return json_decode($str);
    }

    public function floatTostring(float $float): string
    {
        return strval($float);
    }

    public function transferUsingContractAddress(string $fromPublicKey, string $toPublicKey, float $amount, string $tokenContractAddress): float
    {
        // Check if sender has enough balance
        $balanceFrom = $this->getUserBalanceSpecificToken($fromPublicKey, $tokenContractAddress);
        if ($balanceFrom < $amount) {
            throw new Exception("Insufficient balance for the transaction.");
        }

        // Create the transaction block
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Token Transfer", $this->getLatestBlock()->hash);
        $block->transaction_token_symbol = $tokenContractAddress;
        $block->transaction_amount = $amount;
        $block->transaction_from = $fromPublicKey;
        $block->transaction_to = $toPublicKey;
        $this->addBlock($block);

        // Update the balances
        $fromPreviousBalance = $this->getUserBalanceSpecificToken($fromPublicKey, $tokenContractAddress);
        $toPreviousBalance = $this->getUserBalanceSpecificToken($toPublicKey, $tokenContractAddress);

        $fromNewAmount = $fromPreviousBalance - $amount - $this->gas_fee;
        $toNewAmount = $toPreviousBalance + $amount;

        $this->updateUserBalanceCW($fromPublicKey, $tokenContractAddress, $fromNewAmount);
        $this->updateUserBalanceCW($toPublicKey, $tokenContractAddress, $toNewAmount);

        return $amount;
    }

    public function UpdateUserBalanceCW(string $publicKey, string $tokenContractAddress, float $amount): void
    {
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Update Balance", $this->getLatestBlock()->hash);
        $block->user_public_key = $publicKey;
        $block->user_balance_specific_token_balance = $amount;
        $block->user_balance_specific_token_symbol = $this->getTokenSymbol($tokenContractAddress);
        $this->addBlock($block);
    }
    // Get token Symbol using contract address
    public function getTokenSymbol(string $contractAddress): string
    {
        $symbol = "";
        foreach ($this->chain as $block) {
            if ($block->mrczero_token_contract_address === $contractAddress) {
                $symbol = $block->mrczero_token_symbol;
            }
        }
        return $symbol;
    }

    // Swapping Functions Goes Here
    /*
    public function SwapperFunctionLPusage(string $input_contract, string $output_contract, string $input_symbol, string $output_symbol, string $input_amount, string $swapper_user_address) : string
    {
        // Check if the user have enough balance of the token input
        $balance = $this->getUserBalanceSpecificToken($swapper_user_address, $input_symbol);
        if ($balance < $input_amount) {
            throw new Exception("Insufficient balance for the transaction.");
        }

        // Create the swapping block
        $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Swapping", $this->getLatestBlock()->hash);
        $block->swapping_input_token_contract_address = $input_contract;
        $block->swapping_output_token_contract_address = $output_contract;
        $block->swapping_input_token_symbol = $input_symbol;
        $block->swapping_output_token_symbol = $output_symbol;
        $block->swapping_input_token_amount = $input_amount;
        $block->swapping_output_token_amount = $input_amount * $this->getTokenPrice($input_symbol);
        $block->swapper_user_public_key = $swapper_user_address;
        $this->addBlock($block);

        // Update the balances
        $fromPreviousBalance = $this->getUserBalanceSpecificToken($swapper_user_address, $input_symbol);
        $toPreviousBalance = $this->getUserBalanceSpecificToken($swapper_user_address, $output_symbol);

        $fromNewAmount = $fromPreviousBalance - $input_amount;
        $toNewAmount = $toPreviousBalance + $block->swapping_output_token_amount;

        $this->updateUserBalance($swapper_user_address, $input_symbol, $fromNewAmount);
        $this->updateUserBalance($swapper_user_address, $output_symbol, $toNewAmount);

        return $this->floatTostring($block->swapping_output_token_amount);
        echo $block->swapping_output_token_amount;
    }*/

    // Swapping Functions Goes Here
public function SwapperFunctionLPusage(string $input_contract, string $output_contract, string $input_symbol, string $output_symbol, string $input_amount, string $swapper_user_address) : string
{
    // Check if the user has enough balance of the input token
    $balance = $this->getUserBalanceSpecificToken($swapper_user_address, $input_symbol);
    if ((float)$balance < (float)$input_amount) {
        throw new Exception("Insufficient balance for the transaction.");
    }

    // Create the swapping block
    $block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Swapping", $this->getLatestBlock()->hash);
    $block->swapping_input_token_contract_address = $input_contract;
    $block->swapping_output_token_contract_address = $output_contract;
    $block->swapping_input_token_symbol = $input_symbol;
    $block->swapping_output_token_symbol = $output_symbol;
    $block->swapping_input_token_amount = $input_amount;
    
    // Ensure getTokenPrice returns a float for accurate multiplication
    $input_token_price = (float)$this->getTokenPrice($output_symbol);
    $block->swapping_output_token_amount = (float)$input_amount * $input_token_price;
    
    $block->swapper_user_public_key = $swapper_user_address;

    // Add the new block to the chain
    $this->addBlock($block);

    // Update the balances
    $fromPreviousBalance = $this->getUserBalanceSpecificToken($swapper_user_address, $input_symbol);
    $toPreviousBalance = $this->getUserBalanceSpecificToken($swapper_user_address, $output_symbol);

    // Calculate new balances
    $fromNewAmount = (float)$fromPreviousBalance - (float)$input_amount;
    $toNewAmount = (float)$toPreviousBalance + $block->swapping_output_token_amount;

    // Update the user's balances
    $new_updtae_bal_1_block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Update Balance", $this->getLatestBlock()->hash);
    $new_updtae_bal_1_block->user_public_key = $swapper_user_address;
    $new_updtae_bal_1_block->user_balance_specific_token_balance = $fromNewAmount;
    $new_updtae_bal_1_block->user_balance_specific_token_symbol = $input_symbol;
    $this->addBlock($new_updtae_bal_1_block);

    $new_updtae_bal_2_block = new Block(count($this->chain), date("Y-m-d H:i:s"), "Update Balance", $this->getLatestBlock()->hash);
    $new_updtae_bal_2_block->user_public_key = $swapper_user_address;
    $new_updtae_bal_2_block->user_balance_specific_token_balance = $toNewAmount;
    $new_updtae_bal_2_block->user_balance_specific_token_symbol = $output_symbol;
    $this->addBlock($new_updtae_bal_2_block);



    // Log the swapping output amount (optional, can be removed if not needed)
    // echo $block->swapping_output_token_amount; // Not necessary if you return the value

    return $this->floatTostring($block->swapping_output_token_amount);
}

// Get swapping block info
public function getSwappingBlockInfo(string $blockHash): array
{
    // Initialize the variables
    $input_contract = "";
    $output_contract = "";
    $input_symbol = "";
    $output_symbol = "";
    $input_amount = 0;
    $output_amount = 0;
    $swapper_user_address = "";

    // Loop through the blockchain to find the block with the given hash
    foreach ($this->chain as $block) {
        if ($block->hash === $blockHash) {
            $input_contract = $block->swapping_input_token_contract_address;
            $output_contract = $block->swapping_output_token_contract_address;
            $input_symbol = $block->swapping_input_token_symbol;
            $output_symbol = $block->swapping_output_token_symbol;
            $input_amount = $block->swapping_input_token_amount;
            $output_amount = $block->swapping_output_token_amount;
            $swapper_user_address = $block->swapper_user_public_key;
            break; // Exit the loop once the block is found
        }
    }

    // Return the swapping block info
    return [
        'input_contract' => $input_contract,
        'output_contract' => $output_contract,
        'input_symbol' => $input_symbol,
        'output_symbol' => $output_symbol,
        'input_amount' => $input_amount,
        'output_amount' => $output_amount,
        'swapper_user_address' => $swapper_user_address
    ];


}

// get Liquidity pool info
public function getLiquidityPoolInfo(string $token1Symbol, string $token2Symbol): array
{
    // Initialize the variables
    $token1Contract = "";
    $token2Contract = "";
    $token1Amount = 0;
    $token2Amount = 0;
    $owner = "";
    $price = 0.00;

    // Loop through the blockchain to find the block with the given token symbols
    foreach ($this->chain as $block) {
        if ($block->liquidity_token_1_symbol === $token1Symbol && $block->liquidity_token_2_symbol === $token2Symbol) {
            $token1Contract = $block->liquidity_token_1_contract_address;
            $token2Contract = $block->liquidity_token_2_contract_address;
            $token1Amount = $block->liquidity_token_1_amount;
            $token2Amount = $block->liquidity_token_2_amount;
            $owner = $block->liquidity_pool_owner;
            $price = $block->liquidity_pool_token_1_price;
            break; // Exit the loop once the block is found
        }
    }

    // Return the liquidity pool info
    return [
        'token1_contract' => $token1Contract,
        'token2_contract' => $token2Contract,
        'token1_amount' => $token1Amount,
        'token2_amount' => $token2Amount,
        'owner' => $owner,
        'price' => $price
    ];



}
// Get token block info
public function getTokenBlockInfo(string $tokenSymbol): array
{
    // Initialize the variables
    $contractAddress = "";
    $name = "";
    $symbol = "";
    $totalSupply = 0;
    $owner = "";

    // Loop through the blockchain to find the block with the given token symbol
    foreach ($this->chain as $block) {
        if ($block->mrczero_token_symbol === $tokenSymbol) {
            $contractAddress = $block->mrczero_token_contract_address;
            $name = $block->mrczero_token_name;
            $symbol = $block->mrczero_token_symbol;
            $totalSupply = $block->mrczero_token_total_supply;
            $owner = $block->mrczero_token_owner;
            break; // Exit the loop once the block is found
        }
    }

    // Return the token block info
    return [
        'contract_address' => $contractAddress,
        'name' => $name,
        'symbol' => $symbol,
        'total_supply' => $totalSupply,
        'owner' => $owner
    ];

}

// Get all transaction history of  a token token
public function getTokenTransactionHistory(string $tokenSymbol): array
{
    // Initialize the array to store the transaction history
    $transactions = [];

    // Loop through the blockchain to find all blocks with the given token symbol
    foreach ($this->chain as $block) {
        if ($block->transaction_token_symbol === $tokenSymbol) {
            $transactions[] = [
                'from' => $block->transaction_from,
                'to' => $block->transaction_to,
                'amount' => $block->transaction_amount
            ];
        }
    }

    // Return the transaction history
    return $transactions;
}

 // Get a block's previous block hash
public function getBlockPreviousHash(string $blockHash): string
{
    // Initialize the previous hash variable
    $previousHash = "";

    // Loop through the blockchain to find the block with the given hash
    foreach ($this->chain as $block) {
        if ($block->hash === $blockHash) {
            $previousHash = $block->previousHash;
            break; // Exit the loop once the block is found
        }
    }

    // Return the previous hash
    return $previousHash;
}

// Get a block's data
public function getBlockData(string $blockHash): string
{
    // Initialize the data variable
    $data = "";

    // Loop through the blockchain to find the block with the given hash
    foreach ($this->chain as $block) {
        if ($block->hash === $blockHash) {
            $data = $block->data;
            break; // Exit the loop once the block is found
        }
    }

    // Return the block's data
    return $data;
}

// Get block's index
public function getBlockIndex(string $blockHash): int
{
    // Initialize the index variable
    $index = 0;

    // Loop through the blockchain to find the block with the given hash
    foreach ($this->chain as $block) {
        if ($block->hash === $blockHash) {
            $index = $block->index;
            break; // Exit the loop once the block is found
        }
    }

    // Return the block's index
    return $index;
}
}
?>
