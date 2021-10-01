<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bid;
use App\Enums\BidStatus;
use App\Sourcery\Utilities;
use Illuminate\Http\Request;
use App\Http\Requests\BidRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServiceController;

class BidController extends ServiceController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page_size = 20)
    {
        try
        {
            $query = Bid::query()
            ->with(
                'selling',
                'buying',
                'beneficiary',
                'offers'
            )
            ->orderBy('id', 'desc');

            Log::debug($query->get());

            $query = $this->commonFilter($request, $query);

            $bids = $query->paginate($page_size);

            return $this->success($bids);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }


    /**
     * Display a listing of the resource specific to logged in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function mine(Request $request, $page_size = 20)
    {
        try
        {
            $query = Bid::query()
            ->with(
                'selling',
                'buying',
                'beneficiary',
                'offers'
            )
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc');

            $query = $this->commonFilter($request, $query);

            // search by a beneficiary
            if($request->has('keyword') && $request->filled('keyword') && $request->query('keyword') != "null")
            {
                $search = $request->query('keyword');
                $query = $query->whereHas('beneficiaries', function ($q) use ($search) {
                    $q->where("name", "ilike", "%$search%")
                        ->orWhere("account_no", "ilike", "%$search%");
                });
            }

            $bids = $query->paginate($page_size);

            return $this->success($bids);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    /**
     * Display a listing of bids the logged in user is interested in.
     *
     * @return \Illuminate\Http\Response
     */
    public function interested(Request $request, $page_size = 20)
    {
        try
        {
            $user_id = Auth::user()->id;

            $query = Bid::query()
            ->with(
                'selling',
                'buying',
                'beneficiary',
                'offers'
            )
            ->whereHas('offers', function ($q) use ($user_id){
                $q->where("user_id", $user_id);
            })
            ->orderBy('id', 'desc');

            $query = $this->commonFilter($request, $query);

            $bids = $query->paginate($page_size);

            return $this->success($bids);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BidRequest $request)
    {
        try {
            $data = $request->validated();

            $data['user_id'] = Auth::user()->id;
            $data['status'] = BidStatus::Open;

            $bid = Bid::create($data);
            $bid->load('user', 'selling', 'buying', 'beneficiary', 'offers');

            return $this->success($bid);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $bid = Bid::find($id);

            if(!$bid)
            {
                $message = 'Bid not found';
                return $this->not_found(null, $message);
            }

            $bid->load('user', 'selling', 'buying', 'beneficiary', 'offers');

            if ($bid->user_id != Auth::user()->id) {
                $bid->load('user', 'selling', 'buying');
            }

            return $this->success($bid);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function update(BidRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $bid = Bid::find($id);
            if(!$bid)
            {
                $message = 'Bid not found';
                return $this->not_found(null, $message);
            }

            if ($bid->status > 1) {
                $message = 'Bid is closed. Bid cannot be updated';
                $status = 'BidIsClosed';
                return $this->bad_request(null, $message, $status);
            }

            $bid->fill($data)->save();
            $bid->load('user', 'selling', 'buying', 'beneficiary', 'offers');

            return $this->success($bid);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $bid = Bid::find($id);
            $bid->load('offers');
            if(!$bid)
            {
                $message = 'Bid not found';
                return $this->not_found(null, $message);
            }

            if ($bid->status =! BidStatus::Open || $bid->offers->count() > 0) {
                if ($bid->status =! BidStatus::Open) {
                    $message = 'Bid is either closed or has an accepted offer. Bid cannot be deleted';
                    $status = 'BidIsClosed';
                }
                elseif($bid->offers->count() > 0){
                    $message = 'Bid is not empty. Bid cannot be deleted';
                    $status = 'BidIsNotEmpty';
                }

                return $this->bad_request(null, $message, $status);
            }

            $bid->delete();
            return $this->success($bid);
        }
        catch (\Throwable $ex)
        {
            return ResponseHandler::exceptionResponse($ex);
        }
    }


    /**PRIVATE FUNCTIONS */

    /**
     * Filter common parameters in fetching a list of the resource
     *
     * @param  \Illuminate\Http\Request  $request, $query
     * @return $query
     */
    private function commonFilter($request, $query)
    {
        // filter by currency to be sold
        if(Utilities::checkRequest($request, 'selling_currency'))
        {
            $query = $query->where('selling_currency_id', $request->query('selling_currency'));
        }

        // filter by currency to be bought
        if(Utilities::checkRequest($request, 'buying_currency'))
        {
            $query = $query->where('buying_currency_id', $request->query('buying_currency'));
        }

        // filter by status
        if(Utilities::checkRequest($request, 'status'))
        {
            $query = $query->where('status', $request->query('status'));
        }

        // filter by amount
        if(Utilities::checkRequest($request, 'lb_amount') && Utilities::checkRequest($request, 'ub_amount'))
        {
            $query = $query->whereBetween('amount', [$request->query('lb_amount'), $request->query('ub_amount')]);
        }

        // filter by rate
        if(Utilities::checkRequest($request, 'lb_rate') && Utilities::checkRequest($request, 'ub_rate'))
        {
            $query = $query->whereBetween('rate', [$request->query('lb_rate'), $request->query('ub_rate')]);
        }

        // filter by date
        if(Utilities::checkRequest($request, 'start_date') && Utilities::checkRequest($request, 'end_date'))
        {
            $from = Carbon::createFromFormat('d/m/Y', $request->query('start_date'));
            $to = Carbon::createFromFormat('d/m/Y', $request->query('end_date'));

            $query = $query->whereBetween('created_at', [$from, $to]);
        }

        return $query;
    }


}
