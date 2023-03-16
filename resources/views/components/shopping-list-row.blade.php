<tr ingredient-id="{{ $element->id ?? '' }}">
    <td>{{ $element->itemable->name ?? '' }}</td>
    <td class="bold text-center">
        <p class="material-symbols-outlined inline-icon scale w-100 m-3 teal" direction="up" scale="{{ $scalablesCount ?? '' }}" style="display: none">
            add_box
        </p>
        <div class="scalable_steering">
            <span id="scalable{{ $scalablesCount ?? '' }}" class="scalable" ingredient-id="{{ $element->id ?? '' }}">
                {{ $element->amount ?? '' }}
            </span>
            {{ $element->itemable->unit->symbol ?? '' }}
            <span class="material-symbols-outlined inline-icon">
            edit
            </span>
        </div>
        <p class="material-symbols-outlined inline-icon scale text-center w-100 m-3 teal" direction="down" scale="{{ $scalablesCount ?? '' }}" style="display: none">
            indeterminate_check_box
        </p>
    </td>
    <td class="text-center">
        <span class="material-icons material-icons-outlined text-danger inline-icon remover clickable" cat-id="{{ $categoryId ?? '' }}" ingredient-id="{{ $element->id ?? '' }}">clear</span>
        <span class="material-symbols-outlined inline-icon teal redo me-4 clickable" cat-id="{{ $categoryId ?? '' }}" ingredient-id="{{ $element->id ?? '' }}">redo</span>
        <span class="material-symbols-outlined inline-icon text-danger destroy clickable" cat-id="{{ $categoryId ?? '' }}" ingredient-id="{{ $element->id ?? '' }}">delete_forever</span>
    </td>
</tr>
