<x-app-layout>
    <div class="card recipe-card">
        <x-recipe.recipe-preview :name="$name" :image="$image" :admin="$admin" :ingredients="$ingredients" :description="$description" :protein="$protein" :fat="$fat" :carbohydrate="$carbohydrate" :kcal="$kcal" :weightTotal="$weightTotal" :preparationTime="$preparationTime" :cookingTime="$cookingTime" :totalTime="$totalTime" :tags="$tags" :displayMacros="$displayMacros" />
    </div>
</x-app-layout>

<x-scalables-script />
