<div id="recipesModal" class="modal"  aria-labelledby="recipesModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change recipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#">
                    <div class="row mb-3">
                        <div class="col-4">
                            <input type="text" class="form-control" id="search-query" name="search-query" placeholder="Recipe name or ingredient">
                        </div>
                        <div class="col-3">
                            <select class="form-select" id="meal-filter" size="1" aria-label="Meal">
                                <option value="0" selected>No filter</option>
                                <option value="1">Śniadanie</option>
                                <option value="2">Obiad</option>
                                <option value="3">Kolacja</option>
                                <option value="4">Przekąska</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <input type="submit" class="btn btn-primary" id="search-btn" value="Search">
                        </div>

                    </div>
                </form>
                <div id="recipes-found"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    recipesModal = new bootstrap.Modal(document.getElementById('recipesModal'));
    var recipeUpdateDate;
    var recipeUpdateMeal;

    $('.change-meal').on('click', function() {
        $('#search-query').val('');
        recipeUpdateDate = $(this).attr('diet-date');
        recipeUpdateMeal = $(this).attr('diet-meal');

        searchRecipe('', $(this).attr('meal-tags'));
    });

    $('#search-btn').on('click', function(e) {
        searchRecipe($('#search-query').val(), $('#meal-filter').val());
        e.preventDefault();
    });

    function searchRecipe(searchQuery, tags) {
        $.ajax({
            type: "POST",
            url: "/recipes/search",
            data: {'searchFilter': {'tags': tags, 'query': searchQuery}, '_token': '{{ csrf_token() }}'},
            dataType: "json",
            encode: true,
        }).done(function (data) {
            $('#meal-filter').val(tags).change();
            var html = '<table class="table"><tr><td>Recipe</td><td>Protein</td><td>Fat</td><td>Carbohydrate</td><td>Preparation time</td><td>Total time</td><td></td></tr>';
            if (data.length > 0) {
                data.forEach(function (item) {
                    html += '<tr><td><a href="#" class="openPreview" data-bs-target="#recipePreview" data-bs-toggle="modal" data-slug="'+ item.slug + '">' + item.name + '</a></td><td>' + item.protein_ratio + '%</td><td>' + item.fat_ratio + '%</td><td>' + item.carbohydrate_ratio + '%</td><td>' + item.preparation_time + 'min</td><td>' + item.total_time + 'min</td><td><button class="btn btn-success change-recipe" slug="' + item.slug + '">Choose</button></td></tr>';
                });
                html += '</table>';
            } else {
                html += '</table>';
                html += '<div class="alert alert-warning text-center my-1">No results</div>';
            }
            $('#recipes-found').html(html);
        });
    }

    $('#recipes-found').on('click', '.change-recipe', function() {
        var slug = $(this).attr('slug');

        $.ajax({
            type: "POST",
            url: "/diet/update",
            data: {'date': recipeUpdateDate, 'meal': recipeUpdateMeal, 'slug': slug, '_token': '{{ csrf_token() }}'},
            dataType: "json",
            encode: true,
        }).done(function (data) {
            location.reload();
        });
    });
</script>

<x-recipe-modal-preview />
