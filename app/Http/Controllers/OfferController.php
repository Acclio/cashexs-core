<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\Offer;
use App\Sourcery\Utilities;
use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Controllers\ServiceController;

class OfferController extends ServiceController
{
    /**
     * Display a listing of offers for all bids belonging to logged in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function bidsOffers(Request $request, $page_size = 20)
    {
        try
        {
            $user_id = Auth::user()->id;

            $query = Offer::query()
            ->with(
                'bid',
                'beneficiary'
            )
            ->whereHas('bid', function ($q) use($user_id){
                $q->where("user_id", $user_id);
            })
            ->orderBy('id', 'desc');

            $query = $this->commonFIlter($request, $query);
            $offers = $query->paginate($page_size);

            return $this->success($offers);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    /**
     * Display a listing of offers for a particular bid belonging to logged in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function bidOffers(Request $request, $id, $page_size = 20)
    {
        try
        {
            $user_id = Auth::user()->id;

            $query = Offer::query()
            ->with(
                'bid',
                'beneficiary'
            )
            ->where('bid_id', $id)
            ->whereHas('bid', function ($q) use($user_id){
                $q->where("user_id", $user_id);
            })
            ->orderBy('id', 'desc');

            $query = $this->commonFIlter($request, $query);
            $offers = $query->paginate($page_size);

            return $this->success($offers);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    /**
     * Display a listing of offers the logged in users has posted.
     *
     * @return \Illuminate\Http\Response
     */
    public function tenderedOffers(Request $request, $page_size = 20)
    {
        try
        {
            $query = Offer::query()
            ->with(
                'bid',
                'beneficiary'
            )
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc');

            $query = $this->commonFIlter($request, $query);
            $offers = $query->paginate($page_size);

            return $this->success($offers);
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
    public function store(OfferRequest $request)
    {
        try {
            $data = $request->validated();

            $data['user_id'] = Auth::user()->id;

            $bid = Bid::find($data['bid_id']);
            if(!$bid)
            {
                $message = 'Bid not found';
                return $this->not_found(null, $message);
            }

            if($bid->user_id == $data['user_id'])
            {
                $message = 'User cannot post an offer for own bid';
                return $this->bad_request(null, $message);
            }

            $offer = Offer::create($data);
            $offer->load('bid', 'beneficiary');

            return $this->success($offer);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $offer = Offer::find($id);

            if(!$offer)
            {
                $message = 'Bid not found';
                return $this->not_found(null, $message);
            }

            $offer->load('bid', 'beneficiary');

            if ($offer->user_id != Auth::user()->id) {
                $offer->load('bid');
            }

            return $this->success($offer);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOfferRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $offer = Offer::find($id);
            if(!$offer)
            {
                $message = 'Offer not found';
                return $this->not_found(null, $message);
            }

            if ($offer->user_id == Auth::user()->id) {
                $message = "User cannot accept or decline offers for another user's bid";
                $status = 'Forbidden';
                return $this->forbidden(null, $message, $status);
            }

            $offer->fill($data)->save();
            $offer->load('bid');

            return $this->success($offer);

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
            $offer = Offer::find($id);
            if(!$offer)
            {
                $message = 'Offer not found';
                return $this->not_found(null, $message);
            }

            if($offer->user_id !== Auth::user()->id)
            {
                $message = 'Offer does not belong to this user. Offer cannot be deleted';
                $status = 'OfferOutOfBound';
                return $this->bad_request(null, $message);
            }

            if($offer->accepted !== null){
                $message = 'Offer already accepted. Offer cannot be deleted';
                $status = 'OfferAlreadyAccepted';
                return $this->bad_request(null, $message, $status);
            }

            $offer->delete();
            return $this->success($offer);
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
    private function commonFIlter($request, $query)
    {

        // filter by accepted status
        if(Utilities::checkRequest($request, 'is_accepted'))
        {
            $query = $query->where('accepted', $request->query('is_accepted'));
        }

        // filter by amount
        if(Utilities::checkRequest($request, 'lb_amount') && Utilities::checkRequest($request, 'ub_amount'))
        {
            $query = $query->whereBetween('offer', [$request->query('lb_amount'), $request->query('ub_amount')]);
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
