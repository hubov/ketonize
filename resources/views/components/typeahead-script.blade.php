<script src="{{ asset('typeahead.bundle.min.js') }}" ></script>
<script>
    function typeaheadConfig() {
        var typeahead = $.fn.typeahead;

        var ingredients = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: route + '?name=%QUERY',
                wildcard: '%QUERY'
            }
        });

        $('.typeahead').typeahead({
                hint: true,
                highlight: true,
                autoselect: true,
                minLength: 1
            },
            {
                name: 'ingredients',
                displayKey: 'name',
                limit: 10,
                source: ingredients,
                templates: function (data) {
                    return '<p">' + data + '</p>';
                }
            });
    }
</script>
