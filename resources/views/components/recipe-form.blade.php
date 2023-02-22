
<div class="card col-12 col-sm-12 col-md-8 col-lg-6">
    <div class="card-body">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <span class="material-icons inline-icon bi flex-shrink-0 me-2">warning</span>
                    <div>
                        {{ $error }}
                    </div>
                </div>
            @endforeach
        @endif

        <form method="POST" enctype="multipart/form-data">
            @csrf
            @method($method)
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="recipe[name]" id="recipe-name" class="form-control" value="{{ $name ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="recipe[image]" id="recipe-image" class="form-control" value="{{ $image ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="protein" class="form-label">Proteins</label>
                <input type="text" name="protein" id="recipe-protein" class="form-control" readonly="readonly" value="{{ $protein ?? '0' }}">
            </div>
            <div class="mb-3">
                <label for="fat" class="form-label">Fats</label>
                <input type="text" name="fat" id="recipe-fat" class="form-control" readonly="readonly" value="{{ $fat ?? '0' }}">
            </div>
            <div class="mb-3">
                <label for="carbohydrate" class="form-label">Carbohydrates</label>
                <input type="text" name="carbohydrate" id="recipe-carbohydrate" class="form-control" readonly="readonly" value="{{ $carbohydrate ?? '0' }}">
            </div>
            <div class="mb-3">
                <label for="kcal">Kcal</label>
                <input type="text" name="kcal" id="recipe-kcal" class="form-control" readonly="readonly" value="{{ $kcal ?? '0' }}">
            </div>
            <div class="mb-3">Ingredients: </div>
            <div id="rows"></div>
            <div><input type="button" class="btn btn-secondary" value="Add ingredient" name="addIngredient" id="addIngredient"></div>
            <hr>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="recipe[description]" class="form-control" id="description" rows="5">{{ $description ?? '' }}</textarea>
            </div>
            <div class="mb-3">
                <label for="tags">Tags</label>
                <select name="tags[]" multiple class="form-select" aria-label="multiple select">
                    @foreach ($tagsList as $tag)
                        <option value="{{ $tag->id }}"
                            @if (isset($tags[$tag->id]))
                                selected
                            @endif
                        >{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="my-3">
                <label for="preparation_time">Preparation time</label>
                <input type="text" name="recipe[preparation_time]" id="recipe-preparation_time" class="form-control" value="{{ $preparationTime ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="cooking_time">Cooking time</label>
                <input type="text" name="recipe[cooking_time]" id="recipe-cooking_time" class="form-control" value="{{ $cookingTime ?? '' }}">
            </div>
            <div class="mt-3"><input type="submit" name="save" class="btn btn-primary" value="Save" id="save"></div>
        </form>

        <div id="ingredientModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New ingredient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><x-ingredient-form :categories="$categories" :units="$units" /></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('typeahead.bundle.min.js') }}" ></script>
<script type="text/javascript">
    ingredientModal = new bootstrap.Modal(document.getElementById('ingredientModal'));

    $(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
        return event.key != "Enter";
    });

    $(document).ready(function(){
        var typeahead = $.fn.typeahead;
        var ingredients = [];
        var ingredientsCount = 0;
        var ingredientsArray = new Array();
        var proteins = new Array();
        var fats = new Array();
        var carbohydrates = new Array();
        var kcal = new Array();
        var route = "{{ url('ingredient-autocomplete') }}";
        var ingredientFormValidation = ['name', 'protein', 'fat', 'carbohydrate', 'kcal', 'unit_id'];

        @if (isset($ingredients))
            @php
                $i = 0;
            @endphp
            @foreach ($ingredients as $ingredient)
                addIngredientRow('{{ $ingredient->name }}', {{ $ingredient->pivot->amount }}, '{{ $ingredient->unit->symbol }}', {{ $ingredient->id }});
                setIngredientsArray({{ $i }}, {{ $ingredient->protein }}, {{ $ingredient->fat }}, {{ $ingredient->carbohydrate }}, {{ $ingredient->kcal }});
                setResultArray({{ $i }}, {{ $ingredient->pivot->amount }}, {{ $ingredient->protein }}, {{ $ingredient->fat }}, {{ $ingredient->carbohydrate }}, {{ $ingredient->kcal }});
            @php
                $i++;
            @endphp
            @endforeach
            typeaheadInitialize();
            calculateMacro();
        @endif

        substringMatcher = function (strs) {
            return function findMatches(q, cb) {
                let matches, substringRegex;
                matches = [];
                substrRegex = new RegExp(q, 'i');
                $.each(strs, function (i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });
                cb(matches);
            };
        };

        function setIngredientsArray(id, protein, fat, carbohydrate, kcal) {
            if (typeof ingredientsArray[id] == 'undefined') {
                ingredientsArray[id] = new Array();
            }
            ingredientsArray[id][0] = protein;
            ingredientsArray[id][1] = fat;
            ingredientsArray[id][2] = carbohydrate;
            ingredientsArray[id][3] = kcal;
        }

        function setResultArray(id, quantity, protein, fat, carbohydrate, calories) {
            proteins[id] = protein * quantity / 100;
            fats[id] = fat * quantity / 100;
            carbohydrates[id] = carbohydrate * quantity / 100;
            kcal[id] = calories * quantity / 100;
        }

        function typeaheadInitialize(){
            $(document).ready(function() {
                var ingredients = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: route + '?name=%QUERY',
                        wildcard: '%QUERY'
                    }
                });

                $('.typeahead').typeahead({
                        hint: true,
                        highlight: true,
                        autoselect: true,
                        minLength: 1
                    },
                    {
                        name: 'ingredients',
                        displayKey: 'name',
                        limit: 10,
                        source: ingredients,
                        templates: function(data) {
                            return '<p">' + data + '</p>';
                        }
                    });
                $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                    var id = $(this).prop('id');
                    var idNum = id.substring(10);
                    $('#' + id + '_id').val(suggestion.id);
                    $('#' + id + '_unit').text(suggestion.unit);
                    $('#ingredient_q_' + idNum).val('');
                    setIngredientsArray(idNum, suggestion.protein, suggestion.fat, suggestion.carbohydrate, suggestion.kcal);
                    calculateMacro();
                });
                $('.typeahead').change(function() {
                    var id = $(this).prop('id');
                    if (($('#' + id + '_id').val() == 0) || ($('#' + id + '_id').val() == '')) {
                        $('#ingredient-name').val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
                        $('#ingredient-rowId').val(id.substring(10));
                        ingredientModal.show();
                    }
                });
                $('.quantity').on('input', function() {
                    var id = $(this).prop('id').substring(13);

                    proteins[id] = ingredientsArray[id][0]/100 * $(this).val();
                    fats[id] = ingredientsArray[id][1]/100 * $(this).val();
                    carbohydrates[id] = ingredientsArray[id][2]/100 * $(this).val();
                    kcal[id] = Math.round(ingredientsArray[id][3]/100 * $(this).val());

                    calculateMacro();
                });
                $(document).on('click', '.removeRow', function () {
                    var id = $(this).prop('id').substring(13);
                    setIngredientsArray(id, 0, 0, 0, 0);
                    if (typeof ingredientsArray[id] !== 'undefined') {
                        proteins[id] = 0;
                        fats[id] = 0;
                        carbohydrates[id] = 0;
                        kcal[id] = 0;
                    }
                    calculateMacro();
                    $(this).closest('.inputFormRow').remove();
                });
            });
        }

        function calculateMacro() {
            var sum = 0;
            for (var i=proteins.length; i--;) {
                sum+=proteins[i];
            }
            $('#recipe-protein').val(Math.round(sum * 10) / 10);
            var sum = 0;
            for (var i=fats.length; i--;) {
                sum+=fats[i];
            }
            $('#recipe-fat').val(Math.round(sum * 10) / 10);
            var sum = 0;
            for (var i=carbohydrates.length; i--;) {
                sum+=carbohydrates[i];
            }
            $('#recipe-carbohydrate').val(Math.round(sum * 10) / 10);
            var sum = 0;
            for (var i=kcal.length; i--;) {
                sum+=kcal[i];
            }
            $('#recipe-kcal').val(Math.round(sum));
        }

        function addIngredientRow(name = '', quantity = '', unit = '', id = '') {
            var html = '';
            html += '<div class="row mb-3 inputFormRow">';
            html += '<div class="input-group">';
            html += '<input type="text" id="ingredient' + ingredientsCount + '" class="form-control typeahead flex-grow-2" placeholder="Name" autocomplete="off" value="' + name + '">';
            html += '<input type="text" name="ingredients[' + ingredientsCount + '][quantity]" id="ingredient_q_' + ingredientsCount + '" class="form-control quantity flex-grow-1" placeholder="Quantity" value="' + quantity + '">';
            html += '<span class="input-group-text" id="ingredient' + ingredientsCount + '_unit">' + unit + '</span>';
            html += '<button type="button" id="ingredient_r_' + ingredientsCount + '" class="btn btn-danger removeRow"><span class="material-icons material-icons-outline inline-icon" style="font-size: 1.2em">close</span></button>';
            html += '</div><input type="hidden" name="ingredients[' + ingredientsCount + '][id]" id="ingredient' + ingredientsCount + '_id" value="' + id + '"></div>';

            $('.typeahead').typeahead('destroy','NoCached')
            $('#rows').append(html);

            ingredientsCount++;
        }

        $("#addIngredient").click(function () {
            addIngredientRow();
            typeaheadInitialize();
        });

        $("#ingredient-form").submit(function (event) {
            var formData = {
                name: $('#ingredient-name').val(),
                ingredient_category_id: $('#ingredient-category').val(),
                protein: $('#ingredient-protein').val(),
                fat: $('#ingredient-fat').val(),
                carbohydrate: $('#ingredient-carbohydrate').val(),
                kcal: $('#ingredient-kcal').val(),
                unit_id: $('#ingredient-unit_id').val(),
                _token: $('#ingredient-form input[name=_token]').val()
            }

            $.ajax({
                type: "POST",
                url: "/ingredient/new",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                console.log('success');
                var rowId = $('#ingredient-rowId').val();
                var unitSelected = $('#ingredient-unit_id option:selected').text();
                $('#ingredient' + rowId + '_id').val(data.id);
                $('#ingredient' + rowId).val($('#ingredient-name').val());
                $('#ingredient' + rowId + '_unit').html(unitSelected.substring((unitSelected.indexOf('(') + 1), unitSelected.indexOf(')')));
                setIngredientsArray(rowId, $('#ingredient-protein').val(), $('#ingredient-fat').val(), $('#ingredient-carbohydrate').val(), $('#ingredient-kcal').val());
                $('#ingredient-form').trigger('reset');
                ingredientModal.hide();
                $('#ingredient_q_' + rowId).focus();
            }) .fail(function(data) {
                console.log('fail');
                if (data.responseJSON.errors != undefined) {
                    ingredientFormValidation.forEach(function(validate) {
                        if (data.responseJSON.errors[validate] != undefined) {
                            $('#ingredient-' + validate).removeClass('is-valid').addClass('is-invalid');
                        } else {
                            $('#ingredient-' + validate).removeClass('is-invalid').addClass('is-valid');
                        }
                    });
                }

            });

            event.preventDefault();
        });
    });
</script>
