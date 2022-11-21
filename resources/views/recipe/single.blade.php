<x-app-layout>
	<div class="container">
        <x-recipe.recipe-preview :name="$name" :image="$image" :admin="$admin" :ingredients="$ingredients" :description="$description" :protein="$protein" :fat="$fat" :carbohydrate="$carbohydrate" :kcal="$kcal" :weightTotal="$weightTotal" :preparationTime="$preparationTime" :cookingTime="$cookingTime" :displayMacros="$displayMacros" />
	</div>
</x-app-layout>

<x-scalables-script />
