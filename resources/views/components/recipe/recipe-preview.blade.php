<div class="row g-0">
    <div class="col-md-8 img-container">
        <div class="img-overlay"></div>
        <img src="{{ asset($image) }}" class="img-fluid" id="recipeImage">
        <div class="overlay-content-bl"><h1 class="card-title white" id="recipeName">{{ $name }}
                @if ($admin)
                    <a href="{!! url()->current() !!}/edit" class="white"><span class="material-icons material-icons-outlined">edit</span></a>
                @endif
            </h1></div>
    </div>
    <div class="col-md-4">
        <div class="card-body h-100">
            @if ($displayMacros == true)
                <x-recipe.recipe-macros :protein="$protein" :fat="$fat" :carbohydrate="$carbohydrate" :kcal="$kcal" :weightTotal="$weightTotal" :preparationTime="$preparationTime" :cookingTime="$cookingTime" :totalTime="$totalTime" :tags="$tags" />
            @endif
        </div>
    </div>
</div>
</div>
<div class="mt-0 mx-3 p-4 p-md-5 bg-white overflow-hidden shadow-sm sm:rounded-lg recipe-paper">
    <div class="row">
        <div class="col-md-4">
            <div class="row ingredients">
                <div class="col">
                    <div class="row">
                        <h5 class="mb-3 fw-bold">Ingredients</h5>
                    </div>
                    <div class="row ingredients-list" id="recipeIngredients">
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
        <div class="col-1 d-none d-md-block separator">
            <div class="vr"></div>
        </div>
        <div class="col-1 d-md-none w-100 py-4">
            <hr>
        </div>
        <div class="col-md-7">
            <div class="card-text">
                <div class="row">
                    <h5 class="mb-3 fw-bold">Preparation</h5>
                </div>
                <div class="row mx-sm-4">
                    <div class="col recipe-description" id="recipeDescription">
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
