<x-layout>
	<x-ingredient-form :units="$units" />

	@if (count($ingredients) > 0)
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
		@foreach ($ingredients as $ingredient)
				<tr>
					<td><a href="/ingredient/{{ $ingredient->id }}">{{ $ingredient->name }}</a></td>
					<td>{{ $ingredient->protein }}</td>
					<td>{{ $ingredient->fat }}</td>
					<td>{{ $ingredient->carbohydrate }}</td>
					<td>{{ $ingredient->kcal }}</td>
				</tr>
		@endforeach
			</tbody>
		</table>
	@else
		The are no ingredients!
	@endif
</x-layout>