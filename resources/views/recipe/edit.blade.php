<x-app-layout>
	<div class="row mb-5">
		<a href="/recipes">Recipes</a>
	</div>
	<x-recipe-form method="PUT" :name="$recipe->name" :units="$units" :categories="$categories" :image="$recipe->image" :protein="$recipe->protein" :fat="$recipe->fat" :carbohydrate="$recipe->carbohydrate" :kcal="$recipe->kcal" :ingredients="$recipe->ingredients" :description="$recipe->description" :tagsList="$tagsList" :tags="$recipe->tagsIds()" :preparationTime="$recipe->preparation_time"  :cookingTime="$recipe->cooking_time" />
</x-app-layout>
