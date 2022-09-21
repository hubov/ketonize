<form method="POST" id="ingredient-form" action="{{ $action ?? '' }}" class="col-12">
    @csrf
    <div class="mb-3">
        <label for="ingredient-name" class="form-label">Name</label>
        <input type="text" name="name" id="ingredient-name" class="form-control" value="{{ $name ?? '' }}">
        @if ($errors->has('name'))
            <div class="error">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="ingredient-category" class="form-label">Category</label>
        <select name="ingredient_category_id" id="ingredient-category" class="form-control">
            <option>Choose</option>
            @if (count($categories) > 0)
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        @if ($category == $cat->id)
                            selected
                        @endif
                        >{{ $cat->name }}</option>
                @endforeach
            @endif
        </select>
        @if ($errors->has('ingredient_category_id'))
            <div class="error">
                {{ $errors->first('ingredient_category_id') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="ingredient-protein" class="form-label">Proteins</label>
        <input type="text" name="protein" id="ingredient-protein" class="form-control" value="{{ $protein ?? '' }}">
        @if ($errors->has('protein'))
            <div class="error">
                {{ $errors->first('protein') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="ingredient-fat" class="form-label">Fats</label>
        <input type="text" name="fat" id="ingredient-fat" class="form-control" value="{{ $fat ?? '' }}">
        @if ($errors->has('fat'))
            <div class="error">
                {{ $errors->first('fat') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="ingredient-carbohydrate" class="form-label">Carbohydrates</label>
        <input type="text" name="carbohydrate" id="ingredient-carbohydrate" class="form-control" value="{{ $carbohydrate ?? '' }}">
        @if ($errors->has('carbohydrate'))
            <div class="error">
                {{ $errors->first('carbohydrate') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="ingredient-kcal" class="form-label">Kcal</label>
        <input type="text" name="kcal" id="ingredient-kcal" class="form-control" value="{{ $kcal ?? '' }}">
        @if ($errors->has('kcal'))
            <div class="error">
                {{ $errors->first('kcal') }}
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label for="ingredient-unit_id" class="form-label">Measure unit</label>
        <select name="unit_id" id="ingredient-unit_id" class="form-control">
        <option>Choose</option>
        @foreach ($units as $u)
            <option value="{{ $u->id }}" 
            @if ($unit_id == $u->id)
                selected
            @endif
            >{{ $u->name }} ({{ $u->symbol }})</option>
        @endforeach
        </select>
        @if ($errors->has('unit'))
            <div class="error">
                {{ $errors->first('unit') }}
            </div>
        @endif
    </div>
    <input type="hidden" id="ingredient-rowId" />
    <div><input type="submit" name="save" value="Save" id="ingredient-save"></div>
</form>

<script>
    $(document).ready(function(){
        
    });
</script>