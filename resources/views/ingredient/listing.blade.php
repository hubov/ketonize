<x-layout>
	<!-- <form method="POST">
		@csrf
		<div>Name: <input type="text" name="name" id="name"></div>
		@if ($errors->has('name'))
			<div class="error">
				{{ $errors->first('name') }}
			</div>
		@endif
		<div>Proteins: <input type="text" name="protein" id="protein"></div>
		@if ($errors->has('protein'))
			<div class="error">
				{{ $errors->first('protein') }}
			</div>
		@endif
		<div>Fats: <input type="text" name="fat" id="fat"></div>
		@if ($errors->has('fat'))
			<div class="error">
				{{ $errors->first('fat') }}
			</div>
		@endif
		<div>Carbohydrates: <input type="text" name="carbohydrate" id="carbohydrate"></div>
		@if ($errors->has('carbohydrate'))
			<div class="error">
				{{ $errors->first('carbohydrate') }}
			</div>
		@endif
		<div>Kcal: <input type="text" name="kcal" id="kcal"></div>
		@if ($errors->has('kcal'))
			<div class="error">
				{{ $errors->first('kcal') }}
			</div>
		@endif
		<div><input type="submit" name="add" value="Add" id="add"></div>
	</form> -->

	<x-ingredient-form />

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