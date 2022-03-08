<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Meeting;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Form');
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
     * @param StoreMeetingRequest $request
     * @return Response
     */
    public function store(StoreMeetingRequest $request)
    {
        return $request->presist();
    }

    /**
     * Display the specified resource.
     *
     * @param Meeting $meeting
     * @return Response
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Meeting $meeting
     * @return Response
     */
    public function edit(Meeting $meeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMeetingRequest $request
     * @param Meeting $meeting
     * @return Response
     */
    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meeting $meeting
     * @return Response
     */
    public function destroy(Meeting $meeting)
    {
        //
    }

    public function listeners(Request $request)
    {
        $input=$request->only('query');

        $query=implode('%',['',$input['query'],'']);

        return UserResource::collection(
            User::with(['roles'])
                ->orWhere('email','like',$query)
                ->orWhere('name','like',$query)
                ->whereHas('roles',function (Builder $query){
             $query->where('slug','!=',Str::slug('Speaker'));
            })

                ->paginate()
        );
    }
}
