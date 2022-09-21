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
				<tr id="row{{ $ingredient->id }}">
					<td><a href="/ingredient/{{ $ingredient->id }}">{{ $ingredient->name }}</a></td>
					<td>{{ $ingredient->protein }}</td>
					<td>{{ $ingredient->fat }}</td>
					<td>{{ $ingredient->carbohydrate }}</td>
					<td>{{ $ingredient->kcal }}</td>
					<td><span class="material-icons material-icons-outlined text-danger remover" delete-id="{{ $ingredient->id }}">delete</span></td>
				</tr>
		@endforeach
			</tbody>
		</table>
	@else
		The are no ingredients!
	@endif

<div id="ingredientModal" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ingredient present in recipes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	ingredientModal = new bootstrap.Modal(document.getElementById('ingredientModal'));

	$(document).ready(function() {
		$('span.remover').on('click', function() {
			var deleteId = $(this).attr('delete-id');
			console.log(deleteId);
			formData = {
				'_token': '{{ csrf_token() }}'
			}
			$.ajax({
				type: "POST",
            	url: "/ingredient/" + deleteId + "/delete",
            	data: formData,
            	dataType: "json",
            	encode: true,
            }).done(function (data) {
            	$('#row' + deleteId).remove();
            }) .fail(function(data) {
            	$('#ingredientModal div.modal-body').text('');
            	var recipes = data.responseJSON.recipes;
            	recipes.forEach(function(recipe) {
            		$('#ingredientModal div.modal-body').append('<p><a href="/recipe/' + recipe + '/edit">' + recipe + '</a></p>');
            	});
            	ingredientModal.show();
			});
		});
	});
</script>
</x-app-layout>