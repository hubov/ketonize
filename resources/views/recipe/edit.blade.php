<x-layout>
	<x-recipe-form :name="$recipe->name" :units="$units" :image="$recipe->image" :protein="$recipe->protein" :fat="$recipe->fat" :carbohydrate="$recipe->carbohydrate" :kcal="$recipe->kcal" :ingredients="$recipe->ingredients" :description="$recipe->description" :preparationTime="$recipe->preparation_time"  :cookingTime="$recipe->cooking_time" />
</x-layout>