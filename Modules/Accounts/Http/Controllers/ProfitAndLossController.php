<?php

namespace Modules\Accounts\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Accounts\Entities\Accounts;
use Modules\Company\Entities\Company;
use Modules\Accounts\Entities\Transactions;

class ProfitAndLossController extends BaseController
{
    public function index()
    {
        $profit = Accounts::with('transactions')->where('HeadType', 'I')
                                                ->get();

        $loss = Accounts::with('transactions')->where('HeadType', 'E')
                                              ->get();

        $company_info = Company::where('id', $this->user->company_id)->first();

        return view('accounts::ProfitAndLoss.index', compact('company_info', 'profit', 'loss'));
    }

    //search by date function
    public function search_by_date(Request $request)
    {
        if ($request->ajax()) {
            $from = $request->from;
            $to = $request->to;

            $profit = [];
            $loss = [];

            $profit_amount = [];
            $loss_amount = [];

            $totalIncome = 0;
            $totalExpense = 0;

            $transactions = Transactions::whereBetween('VDate',[$from, $to])
                                        ->select('COAID')
                                        ->groupBy('COAID')
                                        ->get();

            foreach( $transactions as $transaction ){
                if( $transaction->coa->HeadType == 'I' ){
                    array_push($profit,$transaction->coa);
                }else if( $transaction->coa->HeadType == 'E' ){
                    array_push($loss,$transaction->coa);
                }
            }

            // return $loss;
            foreach( $profit as $p ){
                if($p->transactions->count() > 0){
                    array_push($profit_amount,$p->transactions->whereBetween('VDate',[$from,$to])->sum('Credit'));
                    $totalIncome += $p->transactions->whereBetween('VDate',[$from,$to])->sum('Credit');
                }else{
                    $totalIncome  = 0.00 ;
                }
            }

            foreach( $loss as $l ){
                if($l->transactions->count() > 0){
                    array_push($loss_amount,$l->transactions->whereBetween('VDate',[$from,$to])->sum('Debit'));
                    $totalExpense += $l->transactions->whereBetween('VDate',[$from,$to])->sum('Debit');
                }else{
                    $totalExpense  = 0.00 ;
                }
            }


            $result = '';
            $diff = 0;
            if( $totalIncome > $totalExpense ){
                $diff = $totalIncome - $totalExpense;
                $result = 'Profit';
            }else{
                $result = 'Loss';
                $diff = $totalExpense - $totalIncome;
            }

            $company_info = Company::where('id', $this->user->company_id)->first();
            return response()->json([
                'company_info' => $company_info,
                'profit' => $profit,
                'loss' => $loss,

                'profit_amount' => $profit_amount,
                'loss_amount' => $loss_amount,

                'totalIncome' => $totalIncome,
                'totalExpense' => $totalExpense,

                'result' => $result,
                'diff' => $diff,

                'from' => $from,
                'to' => $to,
            ], 200);
        }
    }
}
