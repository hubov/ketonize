<img src="{{ asset($image) }}" id="recipeImage" class="card-img-top" alt="...">
<div class="card-body">
    <h1 class="card-title" id="recipeName">{{ $name }}
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
                <div class="row mx-sm-4" id="recipeIngredients">
                    @php
                        $scalableCount = 5;
                    @endphp
                    @if ($ingredients)
                        @foreach ($ingredients as $ingredient)
                            <x-ingredients-list-element :name="$ingredient->name" :scalableCount="$scalableCount" :amount="$ingredient->pivot->amount" :symbol="$ingredient->unit->symbol" />
                            @php
                                $scalableCount++;
                            @endphp
                        @endforeach
                    @endif
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
            <div class="col" id="recipeDescription">
                @if ($description)
                    {!! nl2br($description) !!}
                @endif
            </div>
        </div>
    </div>
</div>
