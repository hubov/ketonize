<script type="text/javascript">
    $(document).ready(function(){
        var originals = new Array();
        for (let i = 0; i < $('.scalable').length; i++) {
            originals.push($('#scalable' + i).text());
        }
        var modifier = 100;
        var timeout;

        function updateScalables(id) {
            if (id !== undefined) {
                var newValue = Math.round(originals[id] * modifier / 100);
                $('#scalable' + id).text(newValue);
            } else {
                originals.forEach(function (item, index) {
                    $('#scalable' + index).text(Math.round(originals[index] * modifier / 100));
                });
            }
        }

        $('.scale').on('mousedown touchstart', function(event) {
            var direction = $(this).attr('direction');
            var scaleId = $(this).attr('scale');
            if (scaleId !== undefined) {
                modifier = 100;
            }
            timeout = setInterval(function(e){
                if (direction == 'up') {
                    modifier += 1;
                } else if (direction == 'down') {
                    modifier -= 1;
                }
                updateScalables(scaleId);
            }, 100);
        }).bind('mouseup mouseleave touchend', function() {
            clearInterval(timeout);
            var scaleId = $(this).attr('scale');
            if (scaleId !== undefined) {
                if ($('#scalable' + scaleId).text() != originals[scaleId]) {
                    var formData = {
                        id: $('#scalable' + scaleId).attr('ingredient-id'),
                        amount: $('#scalable' + scaleId).text(),
                        _token: '{{ csrf_token() }}'
                    }

                    $.ajax({
                        type: "POST",
                        url: "/shopping-list/update",
                        data: formData,
                        dataType: "json",
                        encode: true,
                    }).done(function (data) {
                        console.log('success');
                        originals[scaleId] = $('#scalable' + scaleId).text();
                    }) .fail(function(data) {
                        console.log('fail');
                    });
                }
            }
        });

        $('.scalable').on('change', function(event) {

        });
    });
</script>