<div class="modal" id="recipePreview" aria-hidden="true" aria-labelledby="recipePreviewLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recipePreviewLabel">Recipe preview</h5>
                <button type="button" class="btn-close" data-bs-target="#recipesModal" data-bs-toggle="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <x-recipe.recipe-preview />
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    recipePreview = new bootstrap.Modal(document.getElementById('recipePreview'));

    var lastName;
    var lastAmount;
    var lastSymbol;
    $('#recipeIngredients').on('change', function() {
        var lastIngredient = $(this).children().last();
        lastIngredient.find('.ingredient-name').html(lastName);
        lastIngredient.find('.ingredient-amount').html(lastAmount);
        lastIngredient.find('.ingredient-symbol').html(lastSymbol);
    });

    $('#recipes-found').on('click', '.openPreview', function() {
        var slug = $(this).data('slug');

        $.ajax({
            type: "POST",
            url: "/recipe/search",
            data: {'slug': slug, '_token': '{{ csrf_token() }}'},
            dataType: "json",
            encode: true,
        }).done(function (data) {
            $('#recipeIngredients').html('');
            var listElem = '<x-ingredients-list-element />';
            $('#recipeImage').attr('src', data.image);
            $('#recipeName').html(data.name);

            data.ingredients.forEach(function (item) {
                lastName = item.name;
                lastAmount = item.pivot.amount;
                lastSymbol = item.unit.symbol;
                $('#recipeIngredients').append(listElem).trigger('change');
            });

            // $('#recipeIngredients').html(ingredients);
            $('#recipeDescription').html(data.description.replace (/\n/g, "<br />"));
        });
    });
</script>
