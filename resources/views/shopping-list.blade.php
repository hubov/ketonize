<x-app-layout>
    <div class="row justify-content-md-center mt-3">
        <div class="col-xxl-6 col-xl-8 col-lg-10 shopping-list-menu">
            <div class="row align-middle mb-3">
                <div class="col">
                    <button class="btn btn-primary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#generateList" aria-expanded="false" aria-controls="generateList">
                        <span class="material-symbols-outlined inline-icon">list</span><span class="d-none d-md-inline"> New list</span>
                    </button>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addItem" aria-expanded="false" aria-controls="addItem">
                        <span class="material-symbols-outlined inline-icon">playlist_add</span><span class="d-none d-md-inline"> Add item</span>
                    </button>
                </div>
                <div class="col position-relative">
                    <span class="position-absolute end-0 top-50 translate-middle-y">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="syncSwitch">
                            <label class="form-check-label" for="syncSwitch"><span class="material-symbols-outlined inline-icon teal">sync</span></label>
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
                <div class="collapse p-0" id="addItem">
                    <form method="POST" id="addItemForm" action="/shopping-list/add">
                        <div class="card card-body mb-4">
                            <div class="row">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control flex-grow-3 typeahead tt-hint" id="item_name" name="item_name" placeholder="item name">
                                    <input type="text" class="form-control flex-grow-2" id="amount" name="amount" placeholder="amount">
                                    <select class="form-select text-center flex-grow-1" id="unit" name="unit">
                                        @if (count($units) > 0)
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->symbol }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col my-2 d-flex align-items-center">
                                <input type="submit" class="btn btn-primary" id="add" value="Add item">
                            </div>
                        </div>
                    </form>
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
                    <tr><td colspan="3"><hr class="w-100"></td></tr>
                </tbody>
                <tbody id="trashedShoppingList">
                @if (count($trashed) > 0)
                        @foreach ($trashed as $trashedList)
                            @foreach ($trashedList as $categoryId => $element)
                                <x-shopping-list-row :$element :$scalablesCount :categoryId="$element->itemable->ingredient_category_id" />
                                @php
                                    $scalablesCount++;
                                @endphp
                            @endforeach
                        @endforeach
                @endif
                    </tbody>
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
    <div id="connectionToast" class="toast align-items-center text-bg-danger border-0 bottom-0 end-0 m-3 position-fixed" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body w-100">
                <span class="material-symbols-outlined inline-icon me-1">signal_disconnected</span>
                <span>Cannot connect to server...</span>
                <div class="h-100 d-flex align-items-center float-end">
                    <div class="spinner-border text-light spinner-border-sm" role="status" style="margin: 0 auto">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-scalables-script />

<x-jquery-bootstrap5-toasts-script />

<table class="template" style="display: none">
    <x-shopping-list-row />
</table>

<script type="module">
    let EchoConnector = null;
    const syncSwitch = document.getElementById('syncSwitch');
    const connectionToastEl = document.getElementById("connectionToast");
    const connectionToast = new bootstrap.Toast(connectionToastEl, {
        autohide: false,
    });
    let websocket = null;

    function startEcho() {
        EchoConnector = new Echo({
            broadcaster: 'pusher',
            key: window.ws.key,
            wsHost: window.ws.host,
            wsPort: window.ws.wsPort,
            wssPort: window.ws.wssPort,
            forceTLS: window.ws.forceTLS,
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
            cluster: 'eu'
        });
    }

    function connectWebSocket() {
        if (websocket === null) {
            startEcho();
            websocket = EchoConnector.private(`shoppinglist.{{ Auth::user()->id }}`)
                .listen('ShoppingList\\ItemTrashed', (e) => {
                    removeRow(e.shoppingList.id, e.shoppingList.itemable.ingredient_category_id, false);
                })
                .listen('ShoppingList\\ItemAdded', (e) => {
                    createRow(e.shoppingList);
                })
                .listen('ShoppingList\\ItemRestored', (e) => {
                    recoverRow(e.shoppingList.id, e.shoppingList.itemable.ingredient_category_id, false);
                })
                .listen('ShoppingList\\ItemUpdated', (e) => {
                    updateRow(e.shoppingList);
                });
            EchoConnector.connector.pusher.connection.bind('state_change', function(states) {
                if (states.current != 'connected') {
                    connectionToast.show();
                } else {
                    connectionToast.hide();
                }
            });
            $(document).bsToast({body: "Shopping list sync on!", bgColor: 'bg-success', icon: 'cloud_sync'});

            setTimeout(checkStatus, 5000);
        }
    }

    function disconnectWebSocket() {
        if (websocket !== null) {
            websocket = null;
            EchoConnector.disconnect();
            $(document).bsToast({body: "Shopping list sync off!", bgColor: 'bg-warning', icon: 'cloud_off'});
            connectionToast.hide();
        }
    }

    syncSwitch.addEventListener('change', () => {
        if (syncSwitch.checked) {
            connectWebSocket();
        } else {
            disconnectWebSocket();
        }
    });

    function checkStatus() {
        if (EchoConnector.connector.pusher.connection.state != 'connected') {
            connectionToast.show();
        }
    }
</script>

<script type="text/javascript">
    function toggleCategoryRow(categoryId) {
        if ($('#shoppingList .remover[cat-id=' + categoryId + ']').length == 0) {
            $('#shoppingList tr[cat-id=' + categoryId + ']').toggle();
        }
    }

    function removeRow(ingredientId, categoryId, broadcast = true) {
        var row = $('tr[ingredient-id=' + ingredientId + ']');

        if (broadcast === true) {
            var formData = {
                id: ingredientId,
                _token: '{{ csrf_token() }}'
            }

            $.ajax({
                type: "DELETE",
                url: "/shopping-list/trash",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                if (data === false) {
                    $(document).bsToast("This product has been removed by someone else already!")
                }
            }).fail(function (data) {
                console.log('fail');
            });
        }
        $('#trashedShoppingList').prepend(row);
        row.find('.scale').hide();
        toggleCategoryRow(categoryId);
    }

    function recoverRow(ingredientId, categoryId, broadcast = true) {
        var row = $('tr[ingredient-id=' + ingredientId + ']');

        if (broadcast === true) {
            var formData = {
                id: ingredientId,
                _token: '{{ csrf_token() }}'
            }

            $.ajax({
                type: "POST",
                url: "/shopping-list/restore",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {

            }).fail(function (data) {
                console.log('fail');
            });
        }
        toggleCategoryRow(categoryId);
        $('#shoppingList tr[cat-id=' + categoryId + ']').after(row);
    }

    function destroyRow(ingredientId, broadcast = true) {
        var row = $('tr[ingredient-id=' + ingredientId + ']');

        if (broadcast === true) {
            var formData = {
                id: ingredientId,
                _token: '{{ csrf_token() }}'
            }

            $.ajax({
                type: "DELETE",
                url: "/shopping-list/delete",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {

            }).fail(function (data) {
                console.log('fail');
            });
        }
        row.remove();
    }

    function createRow(item) {
        var scalablesCount = $('.scalable').length;

        var newRow = $("table.template tr").clone();
        newRow.find("p[scale]").attr('scale', scalablesCount);
        newRow.find("span.scalable").attr('id', 'scalable' + scalablesCount);
        newRow.find("td:first").text(item.itemable.name);
        newRow.find("span.scalable").text(item.amount);
        newRow.find("span.scalable").after(' ' + $("#unit option[value=" + item.itemable.unit_id + "]").text());
        newRow.find(".remover").attr('cat-id', item.itemable.ingredient_category_id);
        newRow.find(".redo").attr('cat-id', item.itemable.ingredient_category_id);
        newRow.find(".destroy").attr('cat-id', item.itemable.ingredient_category_id);
        toggleCategoryRow(item.itemable.ingredient_category_id);
        $('#shoppingList tr[cat-id=' + item.itemable.ingredient_category_id + ']').after(newRow);

        return newRow;
    }

    function updateRow(item) {
        var row = $("tr[ingredient-id=" + item.id + "]");
        if (ifTrashedRow(row)) {
            var catId = row.find('.clickable:first').attr('cat-id');
            $('#shoppingList tr[cat-id=' + catId + ']').after(row);
        }
        row.find("span.scalable").text(item.amount);
    }

    function ifTrashedRow(row) {
        return row.parents('#trashedShoppingList').length ? true : false;
    }

    function getRowAmount(ingredientId) {
        return parseInt($("tr[ingredient-id=" + ingredientId + "]").find("span.scalable").text());
    }

    function existingIngredient(name) {
        var id = null;
        $("td").filter(function() {
            return $(this).text() === name;
        }).each(function() {
            id = $(this).parent().attr("ingredient-id");
            return false;
        });
        return id;
    }

    $(document).ready(function(){
        $('#addItemForm').on('submit', function(event) {
            event.preventDefault();
            var itemName = $(this).find('input#item_name').val();
            var itemAmount = parseInt($(this).find('input#amount').val());
            var itemUnit = $(this).find('select#unit').val();

            $(this).find('input#item_name').val('');
            $(this).find('input#amount').val('');
            $(this).find('select#unit').val(1);

            var item = {
                id: null,
                itemable: {
                    name: itemName,
                    ingredient_category_id: 1000,
                    unit_id: itemUnit
                },
                amount: itemAmount
            }

            var formData = {
                item_name: itemName,
                amount: itemAmount,
                unit: itemUnit,
                _token: '{{ csrf_token() }}'
            }

            var ingredientId = existingIngredient(itemName);

            if (ingredientId) {
                item.id = ingredientId;
                if (!ifTrashedRow($('tr[ingredient-id=' + ingredientId + ']'))) {
                    item.amount += getRowAmount(item.id);
                }
                updateRow(item);

                $.ajax({
                    type: "POST",
                    url: "/shopping-list/add",
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    console.log('success');
                }).fail(function (data) {
                    console.log('fail');
                });
            } else {
                var newRow = createRow(item);

                $.ajax({
                    type: "POST",
                    url: "/shopping-list/add",
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    toggleCategoryRow(data.itemable.ingredient_category_id);
                    $('#shoppingList tr[cat-id=' + data.itemable.ingredient_category_id + ']').after(newRow);
                    newRow.find('.clickable').attr('cat-id', data.itemable.ingredient_category_id);

                    newRow.attr('ingredient-id', data.id);
                    newRow.find('span.scalable').attr('ingredient-id', data.id);
                    newRow.find('.clickable').attr('ingredient-id', data.id);

                    console.log('success');
                }).fail(function (data) {
                    console.log('fail');
                });
            }
        });

    	$('table').on('click', '.remover', function () {
    		var el = $(this);
            var catId = el.attr('cat-id');
            var removedId = el.attr('ingredient-id');
            removeRow(removedId, catId);
    	});

        $('table').on('click', '.redo', function() {
            var el = $(this);
            var catId = el.attr('cat-id');
            var removedId = el.attr('ingredient-id');
            recoverRow(removedId, catId);
        });

        $('table').on('click', '.destroy', function () {
            var el = $(this);
            var catId = el.attr('cat-id');
            var removedId = el.attr('ingredient-id');
            destroyRow(removedId, catId);
        });
    });

    $('#shoppingList').on('click', '.scalable_steering', function() {
        $(this).parent().find('.scale').toggle(300);
    });
</script>

<x-typeahead-script />

<script>
    var route = "{{ url('ingredient-autocomplete') }}";

    typeaheadConfig();

    $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
        $('#unit').val(suggestion.unit_id);
        $('#amount').focus();
    });
</script>
