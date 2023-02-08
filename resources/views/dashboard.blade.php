<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row mb-3">
            <div class="col-8 col-xl-3 col-lg-4 col-md-5 col-sm-8">
                <a href="/dashboard/{{ $date['prev'] }}"><span class="date-navigate material-icons material-icons-outlined">navigate_before</span></a>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date['current'] }}">
                <a href="/dashboard/{{ $date['next'] }}"><span class="date-navigate material-icons material-icons-outlined">navigate_next</span></a>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 p-0">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 bg-white border-b border-gray-200">
                        Your diet: {{ $userDiet->diet->name }} {{ $userDiet->kcal }} kcal Fats: {{ $userDiet->fat }}g ({{ $userDiet->diet->fat }}%) Proteins: {{ $userDiet->protein }}g ({{ $userDiet->diet->protein }}%) Carbs: {{ $userDiet->carbohydrate }}g ({{ $userDiet->diet->carbohydrate }}%)<br />
                        @if ($dietPlan !== null)
                            Proteins: {{ $dietPlan->protein }}g ({{ $dietPlan->shareProtein}}%) | Fats: {{ $dietPlan->fat }}g ({{ $dietPlan->shareFat }}%) | Carbs: {{ $dietPlan->carbohydrate }}g ({{ $dietPlan->shareCarbohydrate }}%) | Kcal: {{ $dietPlan->kcal }} | Preparation time: {{ $dietPlan->preparationTime }}min | Total time: {{ $dietPlan->totalTime }}min
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (count($errors))
            @foreach ($errors as $error)
                <div class="alert alert-{{ $error['status'] }} d-flex align-items-center" role="alert">
                    <span class="material-icons inline-icon bi flex-shrink-0 me-2">{{  $error['symbol'] }}</span>
                    <div>
                        {{ $error['message'] }}
                    </div>
                </div>
            @endforeach
        @endif
        @if (($dietPlan) && (count($dietPlan->meals) > 0))
            @foreach ($dietPlan->meals as $meal)
                <div class="row my-3">
                    <div class="card mb-2 px-0" style="min-height: 300px">
                        <div class="row g-0">
                            <div class="col-md-4" style="position: relative;">
                                <a href="
                                    @if ($meal->modifier != 100)
                                        /recipe/{{ $meal->recipe->slug }}/{{ $meal->modifier }}
                                    @else
                                        /recipe/{{ $meal->recipe->slug }}
                                    @endif
                                    ">
                                    <div style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 10; background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(21,82,99,0.3) 80%, rgba(21,82,99,0.6) 100%);"></div>
                                </a>
                                <img src="{{ asset('storage/images/recipe/aaaasasd000141234-u9zl02.jpg') }}" class="img-fluid rounded-start">
                                <div style="position: absolute; bottom: 16px; left: 16px; z-index: 15; color: #fff">
                                    <span class="material-icons material-icons-outlined inline-icon">schedule</span><span class="mr-5"> {{ $meal->recipe->total_time }} min</span>
                                </div>
                                <div style="position: absolute; bottom: 16px; right: 16px; z-index: 15">
                                    @php
                                        $mealOrder = $meal->meal;
                                    @endphp
                                    <button class="btn btn-outline-light btn-sm change-meal" data-bs-toggle="modal" data-bs-target="#recipesModal" diet-meal="{{ $mealOrder }}" diet-date="{{ $date['current'] }}" meal-tags="{{ $mealsTags[$mealOrder] }}"><span class="material-icons material-icons-outlined inline-icon">swap_horiz</span></button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <a href="
                                    @if ($meal->modifier != 100)
                                        /recipe/{{ $meal->recipe->slug }}/{{ $meal->modifier }}
                                    @else
                                        /recipe/{{ $meal->recipe->slug }}
                                    @endif
                                    "><h2 class="card-title">Meal {{ $meal->meal }}</h2>
                                    <h3 class="card-title mb-4">{{ $meal->recipe->name }}</h3></a>
                                    <p class="card-text">
                                        @foreach ($meal->tags as $tag)
                                            <span class="badge bg-warning">{{ $tag->name }}</span>
                                        @endforeach
                                    </p>
                                    <p class="card-text">
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <span class="material-icons material-icons-outlined inline-icon teal">egg_alt</span> <small class="text-muted"><span class="d-none d-sm-inline-block">Proteins:</span> <strong>{{ $meal->protein }}g</strong></small>
                                            </div>
                                            <div class="col-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="background-color: #ffc93c; width: {{ $meal->shareProtein }}%" aria-valuenow="{{ $meal->shareProtein }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <span class="material-icons material-icons-outlined inline-icon teal">water_drop</span> <small class="text-muted"><span class="d-none d-sm-inline-block">Fats:</span> <strong>{{ $meal->fat }}g</strong></small>
                                            </div>
                                            <div class="col-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="background-color: #ffc93c; width: {{ $meal->shareFat }}%" aria-valuenow="{{ $meal->shareFat }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <span class="material-icons material-icons-outlined inline-icon teal">breakfast_dining</span> <small class="text-muted"><span class="d-none d-sm-inline-block">Carbs:</span> <strong>{{ $meal->carbohydrate }}g</strong></small>
                                            </div>
                                            <div class="col-9">
                                                <div class="progress flex-fill align-self-center">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="background-color: #ffc93c; width: {{ $meal->shareCarbohydrate }}%" aria-valuenow="{{ $meal->shareCarbohydrate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </p>
                                    <hr>
                                    <p class="card-text"><small class="text-muted">
                                            <span class="material-icons material-icons-outlined inline-icon teal">local_fire_department</span>Kcal: <strong>{{ $meal->kcal }}</strong>
                                    </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>You have no meals planned!</p>
            <p><a href="/dashboard/generate/{{ $date['current'] }}" class="btn btn-primary" name="plan" id="plan">PLAN NOW</a></p>
        @endif
    </div>
</x-app-layout>

<x-recipes-modal />

<script type="text/javascript">
    $(document).ready(function(){
        function changeDate(date) {
            $(location).attr('href','/dashboard/' + date);
        }

        $('#date').on('focusout', function(e) {
            var date = $(this).val();
            changeDate(date);
        });

        $('#date').on('keydown', function(e) {
            if (e.which == 13) {
                var date = $(this).val();
                changeDate(date);
            }
        });
    });
</script>
