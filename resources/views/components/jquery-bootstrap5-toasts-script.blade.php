<script type="text/javascript">
    var toastIDCounter = 0;

    (function ($) {
        $.fn.bsToast = function (options) {
                if (typeof options === "string") {
                    options = {
                    body: options
                }
            }
            var settings = $.extend({
                body: "MISSING body: <br/>$(...).bsToast({body: 'toast body text here'})<br/><strong><em>html is OK!</em></strong>",
                animation: true, // Apply a CSS fade transition to the toast
                autohide: true,	 // Auto hide the toast
                delay: 20000,	 // Delay hiding the toast (ms)
                dispose: true
            }, options);

            var $toastContainer = $("#toast-container");

            if ($toastContainer.length === 0) {
                var $toastPosition = $("<div>", {
                    "id": "toast-position",
                    "aria-live": "polite",
                    "aria-atomic": "true",
                    "style": "position: fixed; min-height: 200px;top: 20px;right: 20px;min-width: 100%;max-width: 500px;"
                });

                $toastContainer = $("<div>", {
                    "id": "toast-container",
                    "style": "position: absolute; top: 0; right: 0;"
                });

                $(document.body).append($toastPosition);
                $toastPosition.append($toastContainer)
            }

            var toastid = "toast-id-" + toastIDCounter;
            toastIDCounter++

            var $toast = $("<div>", {
                "id": toastid,
                "class": "toast bg-danger text-white",
                "style": "min-width: 300px;",
                "role": "danger",
                "aria-live": "assertive",
                "aria-atomic": true
            });

            if (settings.header && settings.header.text) {
                var $header = $("<div>", {"class": "toast-header"});
                if (settings.header.logo) {
                    $header.append(`<img src="${settings.header.logo}" class="rounded me-2" height="25" width="25" alt="logo">`)
                }
                $header.append(`<strong class="me-auto">${settings.header.text}</strong>`)
                // $header.append(`<small class="text-muted">just now</small>`)
                $header.append(`<button type="button" class="ms-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>`)
                $toast.append($header)
            }

            var $toastWrapper = $("<div>", {"class": "d-flex align-items-center"});
            var $toastBody = $("<div>", {"class": "toast-body"});
            $toastWrapper.html($toastBody)
            $toastBody.html(settings.body)
            $toastWrapper.prepend(`<span class="material-symbols-outlined m-3 fs-4">production_quantity_limits</span>`)
            $toastWrapper.append(`<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>`)
            $toast.append($toastWrapper)
            $toastContainer.append($toast)

            var toastEl = $toast[0]
            toastEl.addEventListener('hidden.bs.toast', toastEl.remove)
            var t = new bootstrap.Toast(toastEl, {delay: settings.delay});
            t.show()
        };

    }(jQuery));
</script>
