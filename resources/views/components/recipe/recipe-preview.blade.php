<div class="row g-0" style="background-color: #155263; color: #fff">
    <div class="col-md-8 img-container">
        <div class="img-overlay"></div>
        <img src="{{ asset($image) }}" class="img-fluid rounded-start" id="recipeImage">
        <div class="overlay-content-bl"><h1 class="card-title white" id="recipeName">{{ $name }}
                @if ($admin)
                    <a href="{!! url()->current() !!}/edit" class="white"><span class="material-icons material-icons-outlined">edit</span></a>
                @endif
            </h1></div>
    </div>
    <div class="col-md-4">
        <div class="card-body" style="height: 100%">
            @if ($displayMacros == true)
                <x-recipe.recipe-macros :protein="$protein" :fat="$fat" :carbohydrate="$carbohydrate" :kcal="$kcal" :weightTotal="$weightTotal" :preparationTime="$preparationTime" :cookingTime="$cookingTime" :totalTime="$totalTime" :tags="$tags" />
            @endif
        </div>
    </div>
</div>
</div>
<div class="mt-0 mx-3 p-4 p-md-5 bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="row">
        <div class="col-md-4">
            <div class="row ingredients">
                <div class="col">
                    <div class="row">
                        <h5 class="mb-3" style="font-weight: bold">Ingredients</h5>
                    </div>
                    <div class="row" id="recipeIngredients">
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
        <div class="col-1 d-none d-md-block">
            <div class="vr" style="height: 50%; margin-top: 100%; margin-left: 50%"></div>
        </div>
        <div class="col-md-7">
            <div class="card-text">
                <div class="row">
                    <h5 class="mb-3" style="font-weight: bold">Preparation</h5>
                </div>
                <div class="row mx-sm-4">
                    <div class="col" id="recipeDescription" style="color: #495057">
                        @if ($description)
                            {!! nl2br($description) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.ingredient-list-element').on('click', function () {
        var checkbox = $(this).find('input[type=checkbox]');

        if ($(this).hasClass('list-element-used')) {
            $(this).removeClass('list-element-used');
            checkbox.prop("checked", false).trigger("change");
        } else {
            $(this).addClass('list-element-used');
            checkbox.prop("checked", true).trigger("change");
        }
    });
</script>
