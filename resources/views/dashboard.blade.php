<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row mb-3">
            <div class="col-8 col-xl-3 col-lg-4 col-md-5 col-sm-8">
                <a href="/dashboard/{{ $datePrev }}"><span class="date-navigate material-icons material-icons-outlined">navigate_before</span></a>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                <a href="/dashboard/{{ $dateNext }}"><span class="date-navigate material-icons material-icons-outlined">navigate_next</span></a>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 p-0">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 bg-white border-b border-gray-200">
                        Your diet: {{ $diet }} {{ $dietKcal }} kcal Fats: {{ $dietFat }}g ({{ $dietFatShare }}%) Proteins: {{ $dietProtein }}g ({{ $dietProteinShare }}%) Carbs: {{ $dietCarbohydrate }}g ({{ $dietCarbohydrateShare }}%)<br />
                        Proteins: {{ $protein }}g ({{ $shareProtein}}%) | Fats: {{ $fat }}g ({{ $shareFat }}%) | Carbs: {{ $carbohydrate }}g ({{ $shareCarbohydrate }}%) | Kcal: {{ $kcal }} | Preparation time: {{ $preparation_time }}min | Total time: {{ $total_time }}min
                    </div>
                </div>
            </div>
        </div>

        @if (count($meals) > 0)
            @foreach ($meals as $meal)
                <div class="row my-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-3 bg-white border-b border-gray-200">
                            <div class="row">
                                <div class="col-8">
                                    <a href="
                                    @if ($meal->modifier != 100)
                                        /recipe/{{ $meal->recipe->slug }}/{{ $meal->modifier }}
                                    @else
                                        /recipe/{{ $meal->recipe->slug }}
                                    @endif
                                    "><h2>Meal {{ $meal->meal }}</h2></a>
                                </div>
                                <div class="col-4 d-flex flex-row flex-row-reverse">
                                    @php
                                        $mealOrder = $meal->meal;
                                    @endphp
                                    <button class="btn btn-outline-secondary btn-sm change-meal" data-bs-toggle="modal" data-bs-target="#recipesModal" diet-meal="{{ $mealOrder }}" diet-date="{{ $date }}" meal-tags="{{ $mealsTags[$mealOrder] }}"><span class="material-icons material-icons-outlined inline-icon">swap_horiz</span></button>
                                </div>
                            </div>
                            <a href="
                            @if ($meal->modifier != 100)
                                /recipe/{{ $meal->recipe->slug }}/{{ $meal->modifier }}
                            @else
                                /recipe/{{ $meal->recipe->slug }}
                            @endif
                            "><h3>{{ $meal->recipe->name }}</h3></a>
                            Proteins: {{ $meal->protein }}g ({{ $meal->shareProtein }}%) | Fats: {{ $meal->fat }}g ({{ $meal->shareFat }}%) | Carbs: {{ $meal->carbohydrate }}g ({{ $meal->shareCarbohydrate }}%) | Kcal: {{ $meal->kcal }} | Time: {{ $meal->recipe->total_time }} min
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>You have no meals planned!</p>
            <p><a href="/dashboard/generate/{{ $date }}" class="btn btn-primary" name="plan" id="plan">PLAN NOW</a></p>
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
