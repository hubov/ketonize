<x-app-layout>
	<div class="container">
		<form method="POST" class="mt-3">
		@csrf
		<div class="row">
			<div class="col">
				<label for="date_from" class="form-label">Start date</label>
				<input type="date" class="form-control" id="date_from" name="date_from" value="{{ $date_from }}">
			</div>
			<div class="col">
				<label for="date_to" class="form-label">End date</label>
				<input type="date" class="form-control" id="date_to" name="date_to" value="{{ $date_to }}">
			</div>
			<div class="col">
				<input type="submit" class="btn btn-primary" id="generate" value="Generate New List">
			</div>
		</div>
		</form>
		<div class="row justify-content-md-center">
			<div class="col-xxl-6 col-xl-8 col-lg-10">
				@if (count($list) > 0)
					<table class="table table-striped mt-4">
						<thead>
							<tr>
								<th scope="col">Ingredient</th>
								<th scope="col">Amount</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
					@php
						$categoriesCount = 0;
						$scalablesCount = 0;
					@endphp
			        @foreach ($list as $group => $elements)
			        	@php
			        		$categoriesCount++;
			        	@endphp
			        	<tr class="table-primary" cat-id="{{ $categoriesCount }}">
			        		<td colspan="3">
			        			<b>{{ $group }}</b>
			        		</td>
			        	</tr>
			        	@foreach ($elements as $element)
			        		<tr>
			        			<td scope="row">{{ $element->ingredient->name }}</td>
			        			<td>
			        				<span class="material-icons material-icons-outlined inline-icon scale" direction="down" scale="{{ $scalablesCount }}">
			        					remove
			        				</span>
			        				<span id="scalable{{ $scalablesCount }}" class="scalable" ingredient-id="{{ $element->id }}">
			        					{{ $element->amount }}
			        				</span>
			        				{{ $element->ingredient->unit->symbol }}
			        				<span class="material-icons material-icons-outlined inline-icon scale" direction="up" scale="{{ $scalablesCount }}">
			        					add
			        				</span>
			        			</td>
			        			<td><span class="material-icons material-icons-outlined text-danger inline-icon remover" cat-id="{{ $categoriesCount }}">clear</span></td>
			        		</tr>
			        		@php
								$scalablesCount++;
							@endphp
						@endforeach
					@endforeach
						</tbody>
					</table>
			    @else
			        You have no shopping list yet!
			    @endif
			</div>
		</div>
	</div>
</x-app-layout>

<x-scalables-script />

<script type="text/javascript">
    $(document).ready(function(){
    	$('.remover').on('click', function () {
    		var el = $(this);
    		var row = $(this).closest('tr');
    		var removedId = row.find('.scalable').attr('ingredient-id');
    		var formData = {
                id: removedId,
                _token: '{{ csrf_token() }}'
            }

            $.ajax({
                type: "DELETE",
                url: "/shopping-list/delete",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                console.log('success');
           		var catId = el.attr('cat-id');
                row.remove();
                if ($('.remover[cat-id=' + catId + ']').length == 0) {
                	$('tr[cat-id=' + catId + ']').remove();
                }
            }) .fail(function(data) {
                console.log('fail');
            });
    	});
    });
</script>
