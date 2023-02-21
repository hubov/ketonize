<x-app-layout>
    <div class="row justify-content-md-center mt-3">
        <div class="col-xxl-6 col-xl-8 col-lg-10 shopping-list-menu">
            <div class="row align-middle mb-3">
                <div class="col">
                    <button class="btn btn-primary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#generateList" aria-expanded="false" aria-controls="generateList">
                        <span class="material-symbols-outlined inline-icon">list</span><span class="d-none d-md-inline"> New list</span>
                    </button>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <span class="material-symbols-outlined inline-icon">playlist_add</span><span class="d-none d-md-inline"> Add item</span>
                    </button>
                </div>
                <div class="col position-relative">
                    <span class="position-absolute end-0 top-50 translate-middle-y">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault"><span class="material-symbols-outlined inline-icon teal">sync</span></label>
                        </div>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="collapse p-0" id="generateList">
                    <div class="card card-body mb-4">
                        <form method="POST">
                            <div class="row">
                                @csrf
                                <div class="col my-2">
                                    <label for="date_from" class="form-label">Start date</label>
                                    <input type="date" class="form-control w-100" id="date_from" name="date_from" value="{{ $date_from }}">
                                </div>
                                <div class="col my-2">
                                    <label for="date_to" class="form-label">End date</label>
                                    <input type="date" class="form-control w-100" id="date_to" name="date_to" value="{{ $date_to }}">
                                </div>
                                <div class="col my-2 d-flex align-items-end">
                                    <input type="submit" class="btn btn-primary w-100" id="generate" value="Generate New List">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-xxl-6 col-xl-8 col-lg-10 p-4 border bg-white">
            @if (count($list) > 0)
                <h1>Shopping list</h1>
                <table class="table table-borderless">
                    <tbody id="shoppingList">
                @php
                    $scalablesCount = 0;
                @endphp
                @foreach ($categories as $categoryName => $category)
                    @if (isset($list[$category->id]))
                        <tr class="table-category" cat-id="{{ $category->id }}">
                    @else
                        <tr class="table-category" cat-id="{{ $category->id }}" style="display: none">
                    @endif
                        <td colspan="3">
                            <div>
                                <b>{{ $categoryName }}</b>
                            </div>
                        </td>
                    </tr>
                    @if (isset($list[$category->id]))
                        @foreach ($list[$category->id] as $element)
                            <x-shopping-list-row :$element :$scalablesCount :categoryId="$category->id" />
                            @php
                                $scalablesCount++;
                            @endphp
                        @endforeach
                    @endif
                @endforeach
                </tbody>
                @if (count($trashed) > 0)
                    <tbody id="trashedShoppingList">
                        @foreach ($trashed as $trashedList)
                            @foreach ($trashedList as $categoryId => $element)
                                <x-shopping-list-row :$element :$scalablesCount :categoryId="$element->ingredient->ingredient_category_id" />
                                @php
                                    $scalablesCount++;
                                @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                @endif
                </table>
            @else
                <div class="row mt-4">
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <span class="material-icons material-icons-outlined bi flex-shrink-0 me-2">task_alt</span>
                        <div>
                            Your shopping list is empty! Well done!
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-scalables-script />

<x-jquery-bootstrap5-toasts-script />
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
                if (data === false) {
                    $(document).bsToast("This product has been removed by someone else already!")
                }
           		var catId = el.attr('cat-id');
                $('#trashedShoppingList').prepend(row);
                row.find('.scale').hide();
                console.log($('#shoppingList .remover[cat-id=' + catId + ']').length);
                if ($('#shoppingList .remover[cat-id=' + catId + ']').length == 0) {
                	$('#shoppingList tr[cat-id=' + catId + ']').toggle();
                }
            }) .fail(function(data) {
                console.log('fail');
            });
    	});

        $('.redo').on('click', function() {
            var el = $(this);
            var row = $(this).closest('tr');
            var recoveredId = row.find('.scalable').attr('ingredient-id');
            var formData = {
                id: recoveredId,
                _token: '{{ csrf_token() }}'
            }
            // $.ajax({
            //     type: "DELETE",
            //     url: "/shopping-list/delete",
            //     data: formData,
            //     dataType: "json",
            //     encode: true,
            // }).done(function (data) {
                console.log('recover');
                var catId = el.attr('cat-id');
                console.log($('#shoppingList .remover[cat-id=' + catId + ']').length);
                if ($('#shoppingList .remover[cat-id=' + catId + ']').length == 0) {
                    $('#shoppingList tr[cat-id=' + catId + ']').toggle();
                }
                $('#shoppingList tr[cat-id=' + catId + ']').after(row);
            // }) .fail(function(data) {
            //     console.log('fail');
            // });
        });
    });

    $('#shoppingList').on('click', '.scalable_steering', function() {
        $(this).parent().find('.scale').toggle(300);
    });
</script>
