<div class="card">
    <img src="{{ asset('storage/images/recipe/default.png') }}" class="card-img-top" alt="...">
    <div class="card-body">
        <h1 class="card-title">{{ $name }}
            @if ($admin)
                <a href="{!! url()->current() !!}/edit"><span class="material-icons material-icons-outlined">edit</span></a>
            @endif
        </h1>
        @if ($displayMacros == true)
            <x-recipe.recipe-macros :protein="$protein" :fat="$fat" :carbohydrate="$carbohydrate" :kcal="$kcal" :weightTotal="$weightTotal" :preparationTime="$preparationTime" :cookingTime="$cookingTime" />
        @endif
        <div class="card-text mt-4">
            <div class="row ingredients">
                <div class="col">
                    <div class="row">
                        <h5>Ingredients</h5>
                    </div>
                    <div class="row mx-sm-4">
                        @php
                            $scalableCount = 5;
                        @endphp
                        @foreach ($ingredients as $ingredient)
                            <div class="row">
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8 col-9">
                                    <span class="teal">●</span> {{ $ingredient->name }}
                                </div>
                                <div class="col-xl-9 col-lg-8 col-md-6 col-sm-4 col-3 bold teal">
                                    <span class="scalable bold" id="scalable{{ $scalableCount }}">{{ $ingredient->pivot->amount }}</span> {{ $ingredient->unit->symbol }}
                                </div>
                            </div>
                            @php
                                $scalableCount++;
                            @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card-text mt-4">
            <div class="row">
                <h5>Preparation</h5>
            </div>
            <div class="row mx-sm-4">
                <div class="col">
                    {!! nl2br($description) !!}
                </div>
            </div>
        </div>
    </div>
</div>
