<x-app-layout>
	<x-ingredient-form :units="$units" :categories="$categories" />
	<div class="row">
		<div class="col">
			<h4>BULK upload</h4>
			<form method="POST" action="/ingredients/bulk" enctype="multipart/form-data">
				<div class="mb-3">
					<label for="formFile" class="form-label">Ingredients list file (*.csv)</label>
					<input class="form-control" type="file" name="bulk_upload" id="bulk_upload">
					<div id="emailHelp" class="form-text">REQUIRED FORMAT: Name, Unit, Category, Kcal, Fat, Fatty acids saturated, Fatty acids monosaturated, Fatty acids polysaturated, Cholesterol, Carbohydrates available, Sugars, Starch, Dietary fibres, Protein, Salt, Vitamin A, Vitamin B1, Vitamin B2, Vitamin B6, Vitamin B12, Niacin, Folate, Panthotenic acid, Vitamin C, Vitamin D, Vitamin E, Potassium, Sodium, Calcium, Magnesium, Phosphorus, Iron, Zinc, Selenium</div>
				</div>
				<div>
					@csrf
					<input type="submit" class="btn btn-primary" name="upload" value="UPLOAD">
				</div>
			</form>
		</div>
	</div>

	@if (count($ingredients) > 0)
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Proteins</th>
					<th>Fats</th>
					<th>Carbohydrates</th>
					<th>Calories (kcal)</th>
					<th></th>
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