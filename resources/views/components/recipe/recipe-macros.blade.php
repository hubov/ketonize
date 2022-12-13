<div class="card-text">
    <div class="row macros">
        <div class="row my-3">
            <div class="col">
                <span class="scalable bold" id="scalable1">{{ $protein }}</span> g<br />
                <span class="label">proteins</span>
            </div>
            <div class="vr"></div>
            <div class="col">
                <span class="scalable bold" id="scalable2">{{ $fat }}</span> g<br />
                <span class="label">fats</span>
            </div>
            <div class="vr"></div>
            <div class="col">
                <span class="scalable bold" id="scalable3">{{ $carbohydrate }}</span> g<br />
                <span class="label">carbohydrates</span>
            </div>
            <div class="vr"></div>
            <div class="col">
                <span class="bold">{{ $preparationTime }}</span> min<br />
                <span class="label">preparation</span>
            </div>
            <div class="vr"></div>
            <div class="col">
                <span class="bold">{{ $cookingTime }}</span> min<br />
                <span class="label">cooking</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <span class="material-icons material-icons-outlined inline-icon scale teal" direction="down">remove</span> <span class="scalable bold" id="scalable0">{{ $kcal }}</span> kcal <span class="material-icons material-icons-outlined inline-icon scale teal" direction="up">add</span><br />
                <span class="label">calories</span>
            </div>
            <div class="col">
                <span class="material-icons material-icons-outlined inline-icon scale teal" direction="down">remove</span> <span class="scalable bold" id="scalable4">{{ $weightTotal }}</span> g <span class="material-icons material-icons-outlined inline-icon scale teal" direction="up">add</span><br />
                <span class="label">amount</span>
            </div>
        </div>
    </div>
</div>
<hr>
