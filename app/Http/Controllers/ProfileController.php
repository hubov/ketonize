<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('profile.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProfileRequest $request)
    {
        $user = Auth::user();

        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->height = $request->height;
        $profile->weight = $request->weight;
        $profile->target_weight = $request->target_weight;
        $profile->gender = $request->gender;
        $profile->diet_target = $request->diet_target;
        $profile->basic_activity = $request->basic_activity;
        $profile->sport_activity = $request->sport_activity;
        $profile->birthday = $request->birthday;
        $profile->save();

        (new UserDietController($profile))->create($request->diet_type, $request->meals_count);

        return response()->json(TRUE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $user = Auth::user();

        $profile = $user->profile;

        return View::make('profile.edit', [
            'profile' => $profile,
            'meals_count' => $user->userDiet->meals_count
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $profile = $user->profile;
        $profile->height = $request->height;
        $profile->weight = $request->weight;
        $profile->target_weight = $request->target_weight;
        $profile->gender = $request->gender;
        $profile->diet_target = $request->diet_target;
        $profile->basic_activity = $request->basic_activity;
        $profile->sport_activity = $request->sport_activity;
        $profile->birthday = $request->birthday;
        $profile->save();

        (new UserDietController($profile))->update($request->diet_type, $request->meals_count);

        return response()->json(TRUE);
    }
}
