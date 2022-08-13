<form method="POST">
    @csrf
    <div>Name: <input type="text" name="name" id="name" value="{{ $name ?? '' }}"></div>
    @if ($errors->has('name'))
        <div class="error">
            {{ $errors->first('name') }}
        </div>
    @endif
    <div>Proteins: <input type="text" name="protein" id="protein" value="{{ $protein ?? '' }}"></div>
    @if ($errors->has('protein'))
        <div class="error">
            {{ $errors->first('protein') }}
        </div>
    @endif
    <div>Fats: <input type="text" name="fat" id="fat" value="{{ $fat ?? '' }}"></div>
    @if ($errors->has('fat'))
        <div class="error">
            {{ $errors->first('fat') }}
        </div>
    @endif
    <div>Carbohydrates: <input type="text" name="carbohydrate" id="carbohydrate" value="{{ $carbohydrate ?? '' }}"></div>
    @if ($errors->has('carbohydrate'))
        <div class="error">
            {{ $errors->first('carbohydrate') }}
        </div>
    @endif
    <div>Kcal: <input type="text" name="kcal" id="kcal" value="{{ $kcal ?? '' }}"></div>
    @if ($errors->has('kcal'))
        <div class="error">
            {{ $errors->first('kcal') }}
        </div>
    @endif
    <div><input type="submit" name="save" value="Save" id="save"></div>
</form>