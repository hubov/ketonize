<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    protected $user;
    protected $profile;
    protected $userDietController;

    public function __construct(User $user, UserDietController $userDietController)
    {
        $this->user = $user;
        $this->userDietController = $userDietController;
    }
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
        $this->user = $this->user->find(Auth()->user()->id);

        $profile = $this->user->profile->create([
            'user_id' => $this->user->id,
            'height' => $request->height,
            'weight' => $request->weight,
            'target_weight' => $request->target_weight,
            'gender' => $request->gender,
            'diet_target' => $request->diet_target,
            'basic_activity' => $request->basic_activity,
            'sport_activity' => $request->sport_activity,
            'birthday' => $request->birthday
        ]);

        $this->userDietController->create($request->diet_type, $request->meals_count);

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
        $this->user = $this->user->find(Auth()->user()->id);

        return View::make('profile.edit', [
            'profile' => $this->user->profile,
            'meals_count' => $this->user->userDiet->meals_count
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
        $this->user = $this->user->find(Auth()->user()->id);

        $this->user->profile->fill([
            'height' => $request->height,
            'weight' => $request->weight,
            'target_weight' => $request->target_weight,
            'gender' => $request->gender,
            'diet_target' => $request->diet_target,
            'basic_activity' => $request->basic_activity,
            'sport_activity' => $request->sport_activity,
            'birthday' => $request->birthday
        ]);
        $this->user->profile->save();

        $this->userDietController->update($request->diet_type, $request->meals_count);

        return response()->json(TRUE);
    }
}
