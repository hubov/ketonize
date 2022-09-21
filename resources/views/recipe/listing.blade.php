<x-app-layout>
	<x-recipe-form :categories="$categories" :units="$units" :tagsList="$tagsList" />

	@if (count($recipes) > 0)
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Proteins</th>
					<th>Fats</th>
					<th>Carbohydrates</th>
					<th>Calories (kcal)</th>
				</tr>
			</thead>
			<tbody>
		@foreach ($recipes as $recipe)
				<tr>
					<td>
						<a href="/recipe/{{ $recipe->slug }}">{{ $recipe->name }}</a>
						@if (count($recipe->tags) > 0)
							@foreach ($recipe->tags as $tag)
								<span class="badge bg-primary">{{ $tag->name }}</span> 
							@endforeach
						@endif
					</td>
					<td>{{ $recipe->protein }}</td>
					<td>{{ $recipe->fat }}</td>
					<td>{{ $recipe->carbohydrate }}</td>
					<td>{{ $recipe->kcal }}</td>
				</tr>
		@endforeach
			</tbody>
		</table>
	@else
		The are no recipes!
	@endif
</x-app-layout>