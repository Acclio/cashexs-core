<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

use App\Enums\IdentificationTypes;
use App\Models\UserIdentification;

class UserIdentificationController extends ServiceController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Display a listing of identification types.
     *
     * @return \Illuminate\Http\Response
     */
    public function types()
    {
        try {
            $types = IdentificationTypes::asSelectArray();
            $id_types = [];
            foreach ($types as $key => $value) {
                $type=[
                    'name'=> $key,
                    'value'=> $value
                ];
                array_push($id_types, $type);
            }
            return $this->success($id_types);
        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Display a listing of identification types.
     *
     * @return \Illuminate\Http\Response
     */
    public function genders()
    {
        try {
            $genders = Gender::asSelectArray();
            $gender_types = [];
            foreach ($genders as $key => $value) {
                $type=[
                    'name'=> $key,
                    'value'=> $value
                ];
                array_push($gender_types, $type);
            }
            $response['genders'] = $gender_types;
            return $this->success($response);
        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserIdentification  $userIdentification
     * @return \Illuminate\Http\Response
     */
    public function show(UserIdentification $userIdentification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserIdentification  $userIdentification
     * @return \Illuminate\Http\Response
     */
    public function edit(UserIdentification $userIdentification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserIdentification  $userIdentification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserIdentification $userIdentification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserIdentification  $userIdentification
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserIdentification $userIdentification)
    {
        //
    }
}
