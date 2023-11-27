@foreach ($hoso_list as $key => $hoso)
    @php
        if (auth()->check() && auth()->user()->type == 2) {
            $id_user = auth()->user()->id;
            $check_flow = DB::table('user_recruitment')
                ->where('hoso_id', $hoso->id)
                ->where('user_id', $id_user)
                ->first();
        }
    @endphp
    <tr>
        <th scope="row"><i data-add-flow="{{ $hoso->id }}" data-vitri="{{ $hoso->vi_tri }}"
                id="star_{{ $hoso->id }}"
                class="{{ isset($check_flow) && $check_flow->flow_user == 1 ? 'fas' : 'far' }} fa-star"></i>
        </th>
        <td>
            <a class="name-hoso"
                href="{{ route('hoso.detail', ['slug' => $hoso->slug, 'id' => $hoso->id]) }}">{{ $hoso->vi_tri }}</a>
            <div class="info-more">
                <span>{{ $hoso->users->name }} - Update: {{ Helper::formatDate($hoso->updated_at) }}</span>
                <span>- View: {{ $hoso->view }}</span>
            </div>
        </td>
        <td>
            @foreach ($hoso->provinces as $province)
                {{ $province->name }}
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
        @foreach ($category_noibat as $category)
            @foreach ($category->informations as $info)
                @if ($hoso->informations->contains('id', $info->id))
                    <td>{{ $info->name }}</td>
                @endif
            @endforeach
        @endforeach

    </tr>
@endforeach
