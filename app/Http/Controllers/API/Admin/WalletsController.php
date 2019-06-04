<?php

namespace App\Http\Controllers\API\Admin;

use App\{Http\Controllers\Controller, Http\Requests\API\Wallet\UpdateWalletRequest, Services\WalletService, Wallet};
use Illuminate\Http\Response;

class WalletsController extends Controller
{

    private $wallet;

    public function __construct (WalletService $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWalletRequest $request
     * @param Wallet $wallet
     * @return Response
     */
    public function update (UpdateWalletRequest $request, Wallet $wallet)
    {
        $this->wallet->update($wallet, $request->only('amount'));

        return $this->isSuccess();
    }
}
