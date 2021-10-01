<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BeneficiaryRequest;
use App\Sourcery\Utilities;

class BeneficiaryController extends ServiceController
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
            $query = Beneficiary::query()
            ->with(
                'bank',
                'country'
            )
            ->where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc');

            // filter by country
            if(Utilities::checkRequest($request, 'country_id'))
            {
                $query = $query->where('country_id', $request->query('country_id'));
            }

            // search by a keyword
            if(Utilities::checkRequest($request, 'keyword'))
            {
                $search = $request->query('keyword');
                $query = $query->where(function ($q) use ($search) {
                    $q->where("name", "ilike", "%$search%")
                        ->orWhere("account_no", "ilike", "%$search%");
                });
            }

            $beneficiaries = $query->paginate($page_size);

            return $this->success($beneficiaries);
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
    public function store(BeneficiaryRequest $request)
    {
        try {
            
            $data = $request->validated();

            $data['user_id'] = Auth::user()->id;
            $data['account_no'] = trim($data['account_no']);

            $existing_beneficiary = Beneficiary::where('bank_id', $data['bank_id'])
            ->where('user_id', $data['user_id'])
            ->where('account_no', $data['account_no'])
            ->first();

            if($existing_beneficiary)
            {
                $message = 'Duplicate beneficiary exists';
                return $this->error($message, null, 'Conflict', 409);
            }

            $beneficiary = Beneficiary::create($data);
            $beneficiary->load('bank', 'country', 'user');

            return $this->success($beneficiary);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $beneficiary = Beneficiary::find($id);

            if(!$beneficiary || $beneficiary->user_id != Auth::user()->id)
            {
                $message = 'Beneficiary not found';
                return $this->not_found(null, $message);
            }

            $beneficiary->load('bank', 'country', 'user');
            return $this->success($beneficiary);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function update(BeneficiaryRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $beneficiary = Beneficiary::find($id);
            if(!$beneficiary)
            {
                $message = 'Beneficiary not found';
                return $this->not_found(null, $message);
            }

            $data['account_no'] = trim($data['account_no']);

            $existing_beneficiary = Beneficiary::where('bank_id', $data['bank_id'])
            ->where('user_id', Auth::user()->id)
            ->where('account_no', $data['account_no'])
            ->where('id', '<>', $id)
            ->first();

            if($existing_beneficiary)
            {
                $message = 'Duplicate beneficiary exists';
                return $this->error($message, null, 'Conflict', 409);
            }

            $beneficiary->fill($data)->save();
            $beneficiary->load('bank', 'country', 'user');

            return $this->success($beneficiary);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $beneficiary = Beneficiary::find($id);
            if(!$beneficiary)
            {
                $message = 'Beneficiary not found';
                return $this->not_found(null, $message);
            }

            $beneficiary->delete();
            return $this->success($beneficiary);
        }
        catch (\Throwable $ex)
        {
            return ResponseHandler::exceptionResponse($ex);
        }
    }
}
