<x-layout>
	<div class="container">
		<div class="row">
			<div class="col">
				<h1>{{ $name }} <a href="{!! url()->current() !!}/edit"><i class="bi-palette"></i></a></h1>
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
				<i class="bi-dash-lg scaleDown"></i> <span class="scalable" id="scalable0">{{ $kcal }}</span> kcal <i class="bi-plus-lg scaleUp"></i><br />
				<span class="label">calories</span>
			</div>
			<div class="col">
				<i class="bi-dash-lg scaleDown"></i> <span class="scalable" id="scalable4">{{ $weightTotal }}</span> g <i class="bi-plus-lg scaleUp"></i><br />
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
								<span class="scalable" id="scalable{{ $scalableCount }}">{{ $ingredient->pivot->amount }}</span> {{ $ingredient->unit->symbol }}
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
				{{ $description }}
			</div>
		</div>
	</div>
</x-layout>

<script type="text/javascript">
	$(document).ready(function(){
		var originals = new Array();
		for (let i = 0; i < $('.scalable').length; i++) {
			originals.push($('#scalable' + i).text());
		}
		var modifier = 100;
		var timeout;

		function updateScalables() {
			originals.forEach(function (item, index) {
				$('#scalable' + index).text(Math.round(originals[index] * modifier / 100));
			});
		}

		$('.scaleUp').on('mousedown touchstart', function() {
			timeout = setInterval(function(e){
				modifier += 1;
				updateScalables();
			}, 100);
		}).bind('mouseup mouseleave touchend', function() {
			clearInterval(timeout);
		});

		$('.scaleDown').on('mousedown touchstart', function() {
			timeout = setInterval(function(e){
				modifier -= 1;
				updateScalables();
			}, 100);
		}).bind('mouseup mouseleave touchend', function() {
			clearInterval(timeout);
		});
	});
</script>