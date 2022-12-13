<x-app-layout>
	<div class="container">
        <div class="card">
            <x-recipe.recipe-preview :name="$name" :image="$image" :admin="$admin" :ingredients="$ingredients" :description="$description" :protein="$protein" :fat="$fat" :carbohydrate="$carbohydrate" :kcal="$kcal" :weightTotal="$weightTotal" :preparationTime="$preparationTime" :cookingTime="$cookingTime" :displayMacros="$displayMacros" />
        </div>
	</div>
</x-app-layout>

<x-scalables-script />
