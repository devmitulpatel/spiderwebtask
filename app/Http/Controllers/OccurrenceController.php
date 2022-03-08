<?php

namespace App\Http\Controllers;

use App\Models\Occurrence;
use App\Http\Requests\StoreOccurrenceRequest;
use App\Http\Requests\UpdateOccurrenceRequest;
use Illuminate\Http\Response;

class OccurrenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOccurrenceRequest $request
     * @return Response
     */
    public function store(StoreOccurrenceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Occurrence $occurrence
     * @return Response
     */
    public function show(Occurrence $occurrence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Occurrence $occurrence
     * @return Response
     */
    public function edit(Occurrence $occurrence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOccurrenceRequest $request
     * @param Occurrence $occurrence
     * @return Response
     */
    public function update(UpdateOccurrenceRequest $request, Occurrence $occurrence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Occurrence $occurrence
     * @return Response
     */
    public function destroy(Occurrence $occurrence)
    {
        //
    }
}
