<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Transaction extends Model implements AuditableContract
{
    use Auditable;

    public function transactionCustomers()
    {
        return $this->hasMany(TransactionCustomer::class, 'transaction_id', 'id');
    }

    // A Transaction belongs to an Address
    public function address()
    {
        return $this->belongsTo('App\Address');
    }

    // A Transaction can have only One Agent
    public function agency()
    {
        return $this->hasOne('App\Agency');
    }

    // A Transaction can have only One Agent Branch
    public function agencyBranch()
    {
         return $this->hasOne('App\AgencyBranch');
    }

    // A Transaction can have only one Agent User
    public function agencyUser()
    {
        return $this->hasOne('App\AgentUser');
    }

    // A Transaction can only have one Collection of Services
    public function serviceCollection()
    {
        return $this->hasOne('App\ServiceCollection');
    }

    // We can get all Transactions by passing the model
    // some parameter values in the form of an array
    public function getTransactions($params)
    {
        $whereData = [];

        foreach ($params as $param) {
            $whereData[] = [$param['col'], $param['op'], $param['val']];
        }

        return $transactions = self::where($whereData)->get();
    }

    // We can get all the Types of Transaction
    public function getTypes()
    {
        $transactions = self::distinct()
            ->select('type')
            ->whereNotNull('type')
            ->get();

        $transactionTypes = [];
        foreach ($transactions as $transaction) {
            $transactionTypes[] = ucfirst($transaction->type);
        }
        return $transactionTypes;
    }
}
