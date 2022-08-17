<form method="POST" class="col-3">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $name ?? '' }}">
        @if ($errors->has('name'))
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="text" name="image" id="image" class="form-control" value="{{ $image ?? '' }}">
        @if ($errors->has('image'))
            <div class="invalid-feedback">
                {{ $errors->first('image') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="protein" class="form-label">Proteins</label>
        <input type="text" name="protein" id="protein" class="form-control" disabled value="{{ $protein ?? '' }}">
        @if ($errors->has('protein'))
            <div class="invalid-feedback">
                {{ $errors->first('protein') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="fat" class="form-label">Fats</label>
        <input type="text" name="fat" id="fat" class="form-control" disabled value="{{ $fat ?? '' }}">
        @if ($errors->has('fat'))
            <div class="invalid-feedback">
                {{ $errors->first('fat') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="carbohydrate" class="form-label">Carbohydrates</label>
        <input type="text" name="carbohydrate" id="carbohydrate" class="form-control" disabled value="{{ $carbohydrate ?? '' }}">
        @if ($errors->has('carbohydrate'))
            <div class="invalid-feedback">
                {{ $errors->first('carbohydrate') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="kcal">Kcal</label>
        <input type="text" name="kcal" id="kcal" class="form-control" disabled value="{{ $kcal ?? '' }}">
        @if ($errors->has('kcal'))
            <div class="invalid-feedback">
                {{ $errors->first('kcal') }}
            </div>
        @endif
    </div>
    <div class="mb-3">Ingredients: </div>
    @if ($errors->has('ingredients'))
        <div class="invalid-feedback">
            {{ $errors->first('ingredients') }}
        </div>
    @endif
    <div><input type="button" class="btn btn-secondary" value="Add ingredient" name="addIngredient" id="addIngredient"></div>
    <div id="rows"></div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control" id="description"></textarea>
        @if ($errors->has('description'))
            <div class="error">
                {{ $errors->first('description') }}
            </div>
        @endif
    </div>
    <div><input type="submit" name="save" class="btn btn-primary" value="Save" id="save"></div>
</form>

<style>
    
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var typeahead = $.fn.typeahead;
        var ingredients = [];
        var route = "{{ url('ingredient-autocomplete') }}";
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            success: function (json) {
                let arrayLength = json.length;
                for (let i = 0; i < arrayLength; i++) {
                    ingredients.push(json[i]);
                }
                return ingredients;
            }
        });

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
                    minLength: 1
                },
                {
                    name: 'ingredients',
                    displayKey: 'name',
                    source: ingredients,
                    templates: function(data) {
                        return '<div>' + data + '</div>';
                    }
                });
                $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                    console.log($(this).prop('id'));
                    console.log('#' + $(this).prop('id') + '_id');
                    $('#' + $(this).prop('id') + '_id').val(suggestion.id);
                });
            });
        }

        var ingredientsCount = 0;

        $("#addIngredient").click(function () {
            var html = '';
            html += '<div class="row mb-3 inputFormRow">';
            html += '<div class="input-group"><input type="text" name="name[]" id="ingredient' + ingredientsCount + '" class="form-control typeahead" placeholder="Name" autocomplete="off">';
            html += '<input type="text" name="quantity[]" class="form-control" placeholder="Quantity">';
            html += '<span class="input-group-text" id="basic-addon1"></span>';
            html += '<button id="removeRow" type="button" class="btn btn-danger"><i class="bi-x-lg"></i></button>';
            html += '</div><input type="hidden" name="ids[]" id="ingredient' + ingredientsCount + '_id" value=""></div>';

            $('.typeahead').typeahead('destroy','NoCached')
            $('#rows').append(html);
            ingredientsCount++;
            typeaheadInitialize();
        });

        $(document).on('click', '#removeRow', function () {
            $(this).closest('.inputFormRow').remove();
        });
    });
</script>