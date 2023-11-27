<script>
    $(document).ready(function() {
        var _token = $('meta[name="csrf-token"]').attr('content');
        if ($('.btn-add-cart').length) {
            $(document).on('click', '.btn-add-cart', function(event) {
                event.preventDefault();
                var id = $(this).data('id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': _token,
                    },
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        id: id,
                    },
                    beforeSend: function() {
                        $(".loadings").show();
                    },
                    success: function(data) {
                        // console.log(data.content);
                        if (data.status > 0) {
                            $(".loadings").fadeOut(300, function() {
                                Successnotification(data.msg);
                                getCartCount();
                            });
                        } else {
                            if (confirm("Vui lòng đăng nhập NTD để tiếp tục") == true) {
                                location.href = "{{ route('employer.login') }}";
                            } else {
                                $('.loadings').hide();
                                return false;
                            }
                        }
                    }
                });
            })
        }

        $(document).on('click', '#btn_cart_modal', function(event) {
            event.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': _token,
                },
                url: "{{ route('cart.get.modal') }}",
                type: "POST",
                success: function(data) {
                    if (data.length > 0) {
                        $('#cart_content_modal').html(data)
                    }
                    $('#cart_modal').modal('show');
                }
            });
        });

        $(document).on('click', '[data-delete-id]', function(event) {
            event.preventDefault();
            var rowId = $(this).attr('data-delete-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': _token,
                },
                url: "/cart/delete/" + rowId,
                type: "POST",
                success: function(data) {
                    if (data.status > 0) {
                        $('#item_' + rowId).hide(400);
                        Successnotification(data.msg);
                        getCartCount();
                    } else {
                        Errornotification("Có lỗi xảy ra!");
                    }
                },
                error: function() {
                    Errornotification("Có lỗi xảy ra!");
                }
            });
        });

        $(document).on('blur', '[data-update-id]', function(event) {
            event.preventDefault();
            var rowId = $(this).attr('data-update-id');
            var qty = $(this).val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': _token,
                },
                url: "{{route('cart.update')}}",
                type: "POST",
                data:{
                    rowId: rowId,
                    qty:qty
                },
                success: function(data) {
                    if (data.status == 1) {
                        Successnotification(data.msg);
                        $('#total_'+rowId).text(data.price);
                        getCartCount();
                    }else if(data.status == 2){
                        $('#item_' + rowId).hide(400);
                        getCartCount();
                    } else {
                        Errornotification(data.msg);
                    }
                },
                error: function() {
                    Errornotification("Có lỗi xảy ra!");
                }
            });
        });


        function getCartCount() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': _token,
                },
                url: "{{ route('cart.get.data') }}",
                type: "POST",
                success: function(data) {
                    $('.count-cart').text(data.count);
                    $('.total_modal').text(data.price);
                    $('.price_modal').text(data.total)
                }
            });
        }

        $('#add_coupon').click(function (e) { 
            e.preventDefault();
            var code = $('input[name="coupon"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': _token,
                },
                type: "POST",
                url: "{{route('coupon.add')}}",
                data: {
                    code:code
                },
                // dataType: "dataType",
                success: function (response) {
                    if(response.msg != ''){
                        Errornotification(response.msg);
                    }else{
                        $('.discount').html(response.discount);
                        getCartCount();
                    }
                    console.log(response);
                }
            });
        });
    });
</script>
