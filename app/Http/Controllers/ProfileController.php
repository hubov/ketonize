<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\ProfileCreateOrUpdateInterface;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    protected $profileCreateOrUpdate;
    protected $profileRepository;
    protected $userRepository;
    protected $user;
    protected $profile;
    protected $userDietController;

    public function __construct(ProfileCreateOrUpdateInterface $profileCreateOrUpdate, ProfileRepositoryInterface $profileRepository, UserRepositoryInterface $userRepository, User $user, UserDietController $userDietController)
    {
        $this->profileCreateOrUpdate = $profileCreateOrUpdate;
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
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
        $this->profileCreateOrUpdate->perform(
            Auth()->user()->id,
            $request->input()
        );

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
        $this->user = $this->userRepository->get(Auth()->user()->id);

        return View::make('profile.edit', [
            'profile' => $this->profileRepository->getForUser(Auth()->user()->id),
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
        $this->profileCreateOrUpdate->perform(
            Auth()->user()->id,
            $request->input()
        );

        return response()->json(TRUE);
    }
}
