<script>
    //setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 2000;  //time in ms, 2 seconds for example

    $(document).on('change', '#service_id', function (e) {

        e.preventDefault();
        var serviceId = $('#service_id option:selected').val();
        if (serviceId == '' || serviceId == null) {
            return false;
        } else {
            getProducts(serviceId, null)
        }

    });

    function getProducts(serviceId) {
        console.log('ppp',serviceId)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('ajax.getProductDetails') }}",
            method: 'POST',
            cache: false,
            data: {
                service_id: serviceId
            },
            success: function (response) {
                if (response.status == true) {
                    if (response.data) {
                        $('#service_price_div').show();
                        $('#service_description_div').show();
                        $('#service_price').val(response.data.price);
                        $('#service_description').val(response.data.description);

                        //claculate price after
                        var $input = $('#percent');

                        //on keyup, start the countdown
                        $input.on('keyup', function () {
                            clearTimeout(typingTimer);
                            typingTimer = setTimeout(doneTyping(response.data.price,$('#percent').val()), doneTypingInterval);
                        });

                        //on keydown, clear the countdown
                        $input.on('keydown', function () {
                            clearTimeout(typingTimer);
                        });
                    }
                } else {
                    toastr.error(response.error)
                }
            },
        });
    }

    //user is "finished typing," do something
    function doneTyping (price_before,percent) {
        var price_after=(price_before - ( price_before * percent / 100 )).toFixed(2)
        $('#price_after').val(price_after)
    }
    @if($edit)
    var serviceId = $('#service_id').val();
    getProducts(serviceId);
    @endif

</script>
