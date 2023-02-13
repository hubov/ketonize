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
                <div class="meal-plan-summary shadow-sm sm:rounded-lg">
                    <div class="p-3 border-b border-gray-200">
                        <div class="row m-0">
                            <div class="col-md-3 p-3">
                                <small>your personal diet plan</small><br />
                                <h3 class="mb-3">{{ $userDiet->diet->name }}</h3>
                                <div class="row">
                                    <span>
                                        <strong>{{ $userDiet->kcal }}</strong>
                                        <small>kcal</small>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <strong>{{ count($userDiet->meals) }}</strong>
                                        <small>meals</small>
                                    </span>
                                </div>
                                <div class="row">
                                    <small class="yellow">per day</small>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row pb-4">
                                    <div class="col-3 text-center">
                                        <canvas id="kcalChart"></canvas>
                                        <span class="material-icons material-icons-outlined inline-icon yellow">local_fire_department</span><br />
                                        <small>kcal</small>
                                    </div>
                                    <div class="col-3 text-center">
                                        <canvas id="proteinChart"></canvas>
                                        <span class="material-symbols-outlined inline-icon yellow">egg_alt</span><br />
                                        <small>protein</small>
                                    </div>
                                    <div class="col-3 text-center">
                                        <canvas id="fatChart"></canvas>
                                        <span class="material-symbols-outlined inline-icon yellow">water_drop</span><br />
                                        <small>fat</small>
                                    </div>
                                    <div class="col-3 text-center">
                                        <canvas id="carbohydrateChart"></canvas>
                                        <span class="material-symbols-outlined inline-icon yellow">breakfast_dining</span><br />
                                        <small>carbs</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1 separator d-none d-md-inline-block">
                                <div class="vr"></div>
                            </div>
                            <div class="col-1 separator d-md-none d-inline-block w-100 pb-4">
                                <hr>
                            </div>
                            <div class="col-md-3 times">
                                <div class="row d-flex justify-content-between text-center h-100 align-items-center">
                                    <div class="col">
                                        <div class="row">
                                            <span class="material-symbols-outlined">surgical</span>
                                        </div>
                                        <div class="row">
                                            <span class="py-2"><strong>{{ $dietPlan->preparationTime }}</strong> min</span><br />
                                            <small>preparation</small>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <span class="material-symbols-outlined">oven_gen</span>
                                        </div>
                                        <div class="row">
                                            <span class="py-2"><strong>{{ $dietPlan->cookingTime }}</strong> min</span><br />
                                            <small>cooking</small>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <span class="material-icons material-icons-outlined">schedule</span>
                                        </div>
                                        <div class="row">
                                            <span class="py-2"><strong>{{ $dietPlan->totalTime }}</strong> min</span><br />
                                            <small>total</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <div class="card mb-2 px-0 recipe-card">
                        <div class="row g-0">
                            <div class="col-md-4 img-container">
                                <a href="
                                    @if ($meal->modifier != 100)
                                        /recipe/{{ $meal->recipe->slug }}/{{ $meal->modifier }}
                                    @else
                                        /recipe/{{ $meal->recipe->slug }}
                                    @endif
                                    ">
                                    <div class="img-overlay"></div>
                                </a>
                                <img src="{{ asset($meal->thumbnail) }}" class="img-fluid">
                                <div class="overlay-content-bl white d-flex align-items-center">
                                    <span class="material-icons material-icons-outlined inline-icon">schedule</span><div class="ps-1"> {{ $meal->recipe->total_time }} min</div>
                                </div>
                                <div class="overlay-content-br">
                                    @php
                                        $mealOrder = $meal->meal;
                                    @endphp
                                    <button class="btn btn-sm change-meal" data-bs-toggle="modal" data-bs-target="#recipesModal" diet-meal="{{ $mealOrder }}" diet-date="{{ $date['current'] }}" meal-tags="{{ $mealsTags[$mealOrder] }}"><span class="material-icons material-icons-outlined inline-icon">swap_horiz</span></button>
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
                                    "><h2 class="card-title">{{ $mealsNames[$meal->meal] ?? $mealsNames[4] }}</h2>
                                    <h3 class="card-title mb-3">{{ $meal->recipe->name }}</h3></a>
                                    <p class="card-text">
                                        @foreach ($meal->tags as $tag)
                                            <span class="badge bg-yellow">{{ $tag->name }}</span>
                                        @endforeach
                                    </p>
                                    <p class="card-text">
                                        <div class="row mb-2">
                                            <div class="col-3 d-flex justify-content-between">
                                                <div class="d-inline flex-grow-1"><span class="material-symbols-outlined inline-icon">egg_alt</span> <span class="d-none d-sm-inline-block label">proteins</span></div><div class="d-inline"><strong>{{ $meal->protein }}g</strong></div><div class="d-inline"></div>
                                            </div>
                                            <div class="col-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped bg-yellow" role="progressbar" style="width: {{ $meal->shareProtein }}%" aria-valuenow="{{ $meal->shareProtein }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3 d-flex justify-content-between">
                                                <div class="d-inline flex-grow-1"><span class="material-symbols-outlined inline-icon">water_drop</span> <span class="d-none d-sm-inline-block label">fats</span></div><div class="d-inline"><strong>{{ $meal->fat }}g</strong></div><div class="d-inline"></div>
                                            </div>
                                            <div class="col-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped bg-yellow" role="progressbar" style="width: {{ $meal->shareFat }}%" aria-valuenow="{{ $meal->shareFat }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3 d-flex justify-content-between">
                                                <div class="d-inline flex-grow-1"><span class="material-symbols-outlined inline-icon">breakfast_dining</span> <span class="d-none d-sm-inline-block label">carbs</span></div><div class="d-inline"><strong>{{ $meal->carbohydrate }}g</strong></div><div class="d-inline"></div>
                                            </div>
                                            <div class="col-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped bg-yellow" role="progressbar" style="width: {{ $meal->shareCarbohydrate }}%" aria-valuenow="{{ $meal->shareCarbohydrate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </p>
                                    <hr>
                                    <p class="card-text">
                                        <span class="material-icons material-icons-outlined inline-icon" style="color: #dc3545">local_fire_department</span> <span class="label">kcal:</span> <strong>{{ $meal->kcal }}</strong>
                                    </p>
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











<script src="
https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js
"></script>
<script>
    var macroCenter = {
        'id': 'center_macro_value',
        beforeDraw: function(chart) {
            var width = chart.width,
                height = chart.height,
                ctx = chart.ctx,
                value = chart.data.datasets[0].data[0];

            ctx.restore();
            var fontSize = (height / 90).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.textBaseline = "middle";

            var text = value + "g",
                textX = Math.round((width - ctx.measureText(text).width) / 2),
                textY = height / 1.25;

            ctx.fillStyle = '#fff';
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    };

    var kcalCenter = {
        'id': 'center_kcal_value',
        beforeDraw: function(chart) {
            var width = chart.width,
                height = chart.height,
                ctx = chart.ctx,
                value = chart.data.datasets[0].data[0];

            ctx.restore();
            var fontSize = (height / 90).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.textBaseline = "middle";

            var text = value,
                textX = Math.round((width - ctx.measureText(text).width) / 2),
                textY = height / 1.25;

            ctx.fillStyle = '#fff';
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    };

    var options = {
        rotation: 270,
        circumference: 180,
        responsive: true,
        legend: {
            display: false
        },
        elements: {
            arc: {
                borderWidth: 0
            }
        },
        cutout: '75%',
        events: []
    };

    var kcalOptions = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $dietPlan->kcal }}, {{ $userDiet->kcal }}],
                backgroundColor: ['#ffc93c', '#2d6170']
            }]
        },
        options: options,
        plugins: [kcalCenter]
    }
    var kcalCtx = document.getElementById('kcalChart').getContext('2d');
    new Chart(kcalCtx, kcalOptions);

    var proteinOptions = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $dietPlan->protein }}, {{ $userDiet->protein }}],
                backgroundColor: ['#ffc93c', '#2d6170']
            }]
        },
        options: options,
        plugins: [macroCenter]
    }
    var proteinCtx = document.getElementById('proteinChart').getContext('2d');
    new Chart(proteinCtx, proteinOptions);

    var fatOptions = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $dietPlan->fat }}, {{ $userDiet->fat }}],
                backgroundColor: ['#ffc93c', '#2d6170']
            }]
        },
        options: options,
        plugins: [macroCenter]
    }
    var fatCtx = document.getElementById('fatChart').getContext('2d');
    new Chart(fatCtx, fatOptions);

    var carbohydrateOptions = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $dietPlan->carbohydrate }}, {{ $userDiet->carbohydrate }}],
                backgroundColor: ['#ffc93c', '#2d6170']
            }]
        },
        options: options,
        plugins: [macroCenter]
    }
    var carbohydrateCtx = document.getElementById('carbohydrateChart').getContext('2d');
    new Chart(carbohydrateCtx, carbohydrateOptions);
</script>
