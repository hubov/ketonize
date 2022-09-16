<x-app-layout>
	<div class="container">
		<div class="row">
			<div class="col">
				<h1>{{ $name }}
					@if ($admin)
						<a href="{!! url()->current() !!}/edit"><span class="material-icons material-icons-outlined">edit</span></a>
					@endif
				</h1>
			</div>
		</div>
		<div class="row macros">
			<div class="col">
				<span class="scalable" id="scalable1">{{ $protein }}</span> g<br />
				<span class="label">proteins</span>
			</div>
			<div class="col">
				<span class="scalable" id="scalable2">{{ $fat }}</span> g<br />
				<span class="label">fats</span>
			</div>
			<div class="col">
				<span class="scalable" id="scalable3">{{ $carbohydrate }}</span> g<br />
				<span class="label">carbohydrates</span>
			</div>
			<div class="col">
				<span class="material-icons material-icons-outlined inline-icon scale" direction="down">remove</span> <span class="scalable" id="scalable0">{{ $kcal }}</span> kcal <span class="material-icons material-icons-outlined inline-icon scale" direction="up">add</span><br />
				<span class="label">calories</span>
			</div>
			<div class="col">
				<span class="material-icons material-icons-outlined inline-icon scale" direction="down">remove</span> <span class="scalable" id="scalable4">{{ $weightTotal }}</span>g <span class="material-icons material-icons-outlined inline-icon scale" direction="up">add</span><br />
				<span class="label">amount</span>
			</div>
		</div>
		<div class="row ingredients">
			<div class="col-6">
				<div class="row">
					<strong>Ingredients</strong>
				</div>
				<div class="row">
					@php
					    $scalableCount = 5;
					@endphp
					@foreach ($ingredients as $ingredient)
						<div class="row">
							<div class="col">
								{{ $ingredient->name }}
							</div>
							<div class="col">
								<span class="scalable" id="scalable{{ $scalableCount }}">{{ $ingredient->pivot->amount }}</span>{{ $ingredient->unit->symbol }}
							</div>
						</div>
						@php
					    	$scalableCount++;
						@endphp
					@endforeach
				</div>
			</div>
		</div>
		<div class="row">
			<strong>Preparation</strong>
		</div>
		<div class="row">
			<div class="col">
				{!! nl2br($description) !!}
			</div>
		</div>
	</div>
</x-app-layout>

<x-scalables-script />