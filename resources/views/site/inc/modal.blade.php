<!--  Cart Modal -->
@php
    $total = str_replace('.','',Cart::total());
    $price = str_replace('.','',Cart::subTotal());
@endphp
 <div class="modal fade" id="cart_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="wrap cf">
                    <h1 class="projTitle">Job NT<span>-Less</span> Shopping Cart</h1>
                    <div class="heading cf">
                        <h1>My Cart</h1>
                        <a href="#" class="continue">Continue Shopping</a>
                    </div>
                    <div class="cart">
                        <!--    <ul class="tableHead">
                        <li class="prodHeader">Product</li>
                        <li>Quantity</li>
                        <li>Total</li>
                         <li>Remove</li>
                      </ul>-->
                        <ul class="cartWrap" id="cart_content_modal">

                            <!--<li class="items even">Item 2</li>-->

                        </ul>
                    </div>

                    <div class="promoCode"><label for="promo">Have A Promo Code?</label><input
                            type="text" name="coupon" placholder="Enter Code" />
                        <a href="" class="btn" id="add_coupon"></a>
                    </div>

                    <div class="subtotal cf">
                        <ul>
                            <li class="totalRow"><span class="label">Subtotal</span><span
                                    class="value price_modal">{{Helper::formatCurrency($price)}}</span></li>

                            <li class="totalRow"><span class="label">Discount</span><span
                                    class="value discount" id="">$0.00</span></li>

                            <li class="totalRow"><span class="label">Tax</span><span
                                    class="value">$0.00</span></li>
                            <li class="totalRow final"><span class="label">Total</span><span
                                    class="value total_modal" id="">{{Helper::formatCurrency($total)}}</span></li>
                            <li class="totalRow"><a href="{{route('checkout.index')}}" class="btn continue">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
        </div>
    </div>
</div>
<!-- End Cart Modal  -->