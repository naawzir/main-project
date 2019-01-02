<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    // Display the Transaction/Cases page with the correct navigation
    public function index()
    {
        return;
    }

    // Populate the list of Filters on the Transaction/Cases page
    // public function getFilters()
    // {

    // }

    // Populate the Transactions/Cases Page with given $params
    // Set $params if none passed
    public function getTransactions($params = null)
    {
        $transaction = new Transaction;

        if ($params) {
            $transactions = $transaction->getTransactions($params);
        } else {
            $defaultDate = strtotime('first day of this month');

            $params = array( 'date' =>
                                        array(
                                            'col' => 'date_created',
                                            'op' => '>',
                                            'val' => $defaultDate
                                        ),
                                        array(
                                            'col' => 'active',
                                            'op' => '=',
                                            'val' => 1
                                        )
                                    );
            $transactions = $transaction->getTransactions($params);
        }

        return $transactions;
    }
}
