<x-layout>
	<div class="container">
		<div class="row">
			<div class="col">
				<h1>{{ $name }}</h1>
			</div>
		</div>
		<div class="row macros">
			<div class="col">
				{{ $protein }} g<br />
				<span class="label">proteins</span>
			</div>
			<div class="col">
				{{ $fat }} g<br />
				<span class="label">fats</span>
			</div>
			<div class="col">
				{{ $carbohydrate }} g<br />
				<span class="label">carbohydrates</span>
			</div>
			<div class="col">
				<i class="bi-dash-lg"></i> {{ $kcal }} kcal <i class="bi-plus-lg"></i><br />
				<span class="label">calories</span>
			</div>
		</div>
		<div class="row ingredients">
			<div class="col-6">
				<div class="row">
					<strong>Ingredients</strong>
				</div>
				<div class="row">
					@foreach ($ingredients as $ingredient)
						<div class="row">
							<div class="col">
								{{ $ingredient->name }}
							</div>
							<div class="col">
								{{ $ingredient->pivot->amount }} {{ $ingredient->unit->symbol }}
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
		<div class="row">
			<strong>Preparation</strong>
		</div>
		<div class="row">
			<div class="col">
				{{ $description }}
			</div>
		</div>
	</div>
</x-layout>