
@foreach ($cart_content as $key => $item)
<li class="items even" id="item_{{$item->rowId}}">
    <div class="infoWrap">
        <div class="cartSection">
            <img src="" alt="" class="itemImg" />
            <p class="itemNumber">#QUE-007544-002</p>
            <h3>{{$item->name}}</h3>

            <p> <input id="qty_{{$item->rowId}}" data-update-id="{{$item->rowId}}" type="text" class="qty" placeholder="SL" value="{{$item->qty}}" /> x {{ Helper::formatCurrency($item->price) }}</p>

            <p class="stockStatus"> In Stock</p>
        </div>


        <div class="prodTotal cartSection">
            <p id="total_{{$item->rowId}}">{{ Helper::formatCurrency($item->total) }}</p>
        </div>
        <div class="cartSection removeWrap">
            <a href="" id="btn_delete_{{$item->rowId}}" data-delete-id="{{$item->rowId}}" class="remove">x</a>
        </div>
    </div>
</li>
@endforeach