<div class="card-text macros h-100">
    <div class="row h-100 d-flex justify-content-between">
        <div class="row p-0 mb-3">
            <div class="col-4">
                <canvas id="proteinChart"></canvas>
                <div class="visually-hidden">
                    <span class="scalable bold" id="scalable1">{{ $protein }}</span> g<br />
                </div>
                <span class="label">proteins</span>
            </div>
            <div class="col-4">
                <canvas id="fatChart"></canvas>
                <div class="visually-hidden">
                    <span class="scalable bold" id="scalable2">{{ $fat }}</span> g<br />
                </div>
                <span class="label">fats</span>
            </div>
            <div class="col-4">
                <canvas id="carbsChart"></canvas>
                <div class="visually-hidden">
                    <span class="scalable bold" id="scalable3">{{ $carbohydrate }}</span> g<br />
                </div>
                <span class="label">carbohydrates</span>
            </div>
        </div>
        <hr>
        <div class="row p-0 mb-3">
            <div class="col">
                <div class="row">
                    <span class="material-symbols-outlined">surgical</span>
                </div>
                <div class="row">
                    <span class="py-2"><strong>{{ $preparationTime }}</strong> min</span><br />
                    <span class="label">preparation</span>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="material-symbols-outlined">oven_gen</span>
                </div>
                <div class="row">
                        <span class="py-2"><strong>{{ $cookingTime }}</strong> min</span><br />
                    <span class="label">cooking</span>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="material-icons material-icons-outlined">schedule</span>
                </div>
                <div class="row">
                    <span class="py-2"><strong>{{ $totalTime }}</strong> min</span><br />
                    <span class="label">total</span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-3 px-0">
            <div class="col ps-0 pe-1">
                <div class="card">
                        <div class="row mb-2">
                            <span class="material-icons material-icons-outlined yellow">local_fire_department</span>
                        </div>
                        <div class="row">
                            <span class="p-0">
                                <i class="material-icons material-icons-outlined inline-icon scale" direction="down">remove</i> <strong class="scalable" id="scalable0">{{ $kcal }}</strong> kcal <i class="material-icons material-icons-outlined inline-icon scale" direction="up">add</i>
                            </span>
                            <br />
                            <span class="label">calories</span>
                        </div>
                </div>
            </div>
            <div class="col ps-1 pe-0">
                <div class="card">
                    <div class="row mb-2">
                        <span class="material-symbols-outlined yellow">scale</span>
                    </div>
                    <div class="row">
                        <span class="py-0">
                            <i class="material-icons material-icons-outlined inline-icon scale" direction="down">remove</i> <strong class="scalable" id="scalable4">{{ $weightTotal }}</strong> g <i class="material-icons material-icons-outlined inline-icon scale" direction="up">add</i>
                        </span>
                        <br />
                        <span class="label">amount</span>
                    </div>
                </div>
            </div>
        </div>
        @if (count($tags))
            <hr>
            <div class="row">
                <span>
                    @foreach($tags as $tag)
                        <span class="badge">{{ $tag->name }}</span>
                    @endforeach
                </span>
            </div>
        @endif
    </div>
</div>

<script src="
https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js
"></script>
<script>
    var macroCenter = {
        'id': 'center_macro_value',
        beforeDraw: function(chart) {
            var width = chart.width,
                height = chart.height,
                ctx = chart.ctx,
                value = chart.data.datasets[0].data[0];

            ctx.restore();
            var fontSize = (height / 70).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.textBaseline = "middle";

            var text = value + "g",
                textX = Math.round((width - ctx.measureText(text).width) / 2),
                textY = height / 1.85;

            ctx.fillStyle = '#fff';
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    };

    Chart.register(macroCenter);

    var options = {
        responsive: true,
        legend: {
            display: false
        },
        elements: {
            arc: {
                borderWidth: 0
            }
        },
        cutout: '75%',
        events: [],
        plugins: [macroCenter]
    };

    var protein = {{ $protein }};
    var fat = {{ $fat }};
    var carbs = {{ $carbohydrate }};
    var macroSum = protein + fat + carbs;

    var dataProtein = {
        datasets: [
            {
                data: [protein, macroSum-protein],
                backgroundColor: ['#ffc93c', '#155263']
            }
        ]
    };

    var proteinChart = new Chart(document.getElementById('proteinChart'), {
        type: 'doughnut',
        data: dataProtein,
        options: options
    });

    var dataFat = {
        datasets: [
            {
                data: [fat, macroSum-fat],
                backgroundColor: ['#ffc93c', '#155263']
            }
        ]
    };

    var fatChart = new Chart(document.getElementById('fatChart'), {
        type: 'doughnut',
        data: dataFat,
        options: options
    });

    var dataCarbs = {
        datasets: [
            {
                data: [carbs, macroSum-carbs],
                backgroundColor: ['#ffc93c', '#155263']
            }
        ]
    };

    var carbsChart = new Chart(document.getElementById('carbsChart'), {
        type: 'doughnut',
        data: dataCarbs,
        options: options
    });

    setInterval(updateCharts, 100);

    function updateCharts()
    {
        updateMacroChart(proteinChart, $('#scalable1').text(), macrosSum());
        updateMacroChart(fatChart, $('#scalable2').text(), macrosSum());
        updateMacroChart(carbsChart, $('#scalable3').text(), macrosSum());
    }

    function macrosSum() {
        return parseInt($('#scalable1').text()) + parseInt($('#scalable2').text()) + parseInt($('#scalable3').text());
    }

    function updateMacroChart(chart, value, sum)
    {
        chart.data.datasets[0].data[0] = value;
        chart.data.datasets[0].data[1] = sum;
        chart.update();
    }
</script>
