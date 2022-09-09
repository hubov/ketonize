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
                        Your diet: ketogenic vegan 2200 kcal Fats: 70-80% Proteins: 10-15% Carbs: 5-10%<br />
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
                            <h2>Meal {{ $meal->meal }}</h2>
                            <a href="/recipe/{{ $meal->recipe->slug }}"><h3>{{ $meal->recipe->name }}</h3></a>
                            Proteins: {{ $meal->recipe->protein }}g ({{ $meal->shareProtein }}%) | Fats: {{ $meal->recipe->fat }}g ({{ $meal->shareFat }}%) | Carbs: {{ $meal->recipe->carbohydrate }}g ({{ $meal->shareCarbohydrate }}%) | Kcal: {{ $meal->recipe->kcal }} | Time: {{ $meal->recipe->total_time }} min
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            You have no meals planned!
        @endif
    </div>
</x-app-layout>

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