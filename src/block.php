<?php

/* Copyright (c), Afif Ali Saadman , All rights reserved
This code is top secret , it will be only avivaliable for the programmer himself and his contributor (paid).
 */
class Block
{
    public int $index;
    public string $timestamp;
    public mixed $data;
    public string $previousHash;
    public string $hash;
    public ?string $user_first_name = null;
    public ?string $user_last_name = null;
    public ?string $user_public_key = null;
    public ?string $user_signature = null;
    public ?string $user_email_address = null;
    public ?string $user_recovery_email_address = null;
    public ?string $user_recovery_phone_number = null;
    public ?string $user_password = null;
    public ?float $user_balance_specific_token_balance = null;
    public ?string $user_balance_specific_token_symbol = null;
    public ?string $user_miner_address = null;
    public ?int $nonce = null;
    public ?string $transaction_token_symbol = null;
    public ?float $transaction_amount = null;
    public ?string $transaction_from = null;
    public ?string $transaction_to = null;
    public ?string $mrczero_token_contract_address = null;
    public ?string $mrczero_token_name = null;
    public ?string $mrczero_token_symbol = null;
    public ?int $mrczero_token_total_supply = null;
    public ?string $mrczero_token_owner = null;
    public ?string $mrczero_token_logo = null;
    // Liquidity variables goes here
    public ?string $liquidity_token_1_contract_address = null;
    public ?string $liquidity_token_2_contract_address = null;
    public ?string $liquidity_token_1_symbol = null;
    public ?string $liquidity_token_2_symbol = null;
    public ?float $liquidity_token_1_amount = null;
    public ?float $liquidity_token_2_amount = null;
    public ?string $liquidity_pool_owner = null;
    public ?string $liquidity_pool_contract_address = null;
    public ?string $liquidity_pool_name = null;
    public ?float $liquidity_pool_token_1_price = null;
    // Swapping variables goes here
    public ?string $swapping_input_token_contract_address = null;
    public ?string $swapping_output_token_contract_address = null;
    public ?string $swapping_input_token_symbol = null;
    public ?string $swapping_output_token_symbol = null;
    public ?float $swapping_input_token_amount = null;
    public ?float $swapping_output_token_amount = null;
    public ?string $swapper_user_public_key = null;
    


    public function __construct(int $index, string $timestamp, mixed $data, string $previousHash)
    {
        $this->index = $index;
        $this->timestamp = $timestamp;
        $this->data = $data;
        $this->previousHash = $previousHash;
        $this->hash = $this->calculateHash();
    }

    public function calculateHash(): string
    {
        return hash('sha256', $this->index . $this->timestamp . json_encode($this->data) . $this->previousHash);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromArray(array $data): Block
    {
        $block = new self($data['index'], $data['timestamp'], $data['data'], $data['previousHash']);
        foreach ($data as $key => $value) {
            if (property_exists($block, $key)) {
                $block->$key = $value;
            }
        }
        return $block;
    }
}
