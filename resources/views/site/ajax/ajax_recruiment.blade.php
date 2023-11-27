@foreach ($recruiment_list as $item)
    <div class="job-item p-4 mb-4">
        <div class="row g-4">
            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                <img class="flex-shrink-0 img-fluid border rounded" src="/upload/{{ $item->Employers->photo }}"
                    alt="" style="width: 80px; height: 80px;">
                <div class="text-start ps-4">
                    <a href="{{ route('recruitment.job.detail', ['slug' => $item->slug, 'id' => $item->id]) }}"
                        title="{{ $item->vi_tri }}">
                        <h5 class="mb-3">{{ $item->vi_tri }}</h5>
                    </a>
                    <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>
                        @foreach ($item->provinces as $province)
                            {{ $province->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </span>
                    @foreach ($category_noibat as $category)
                        @foreach ($category->informations as $info)
                            @if ($item->informations->contains('id', $info->id))
                                <span class="text-truncate me-3"><i
                                        class="fas fa-dot-circle text-primary me-2"></i>{{ $info->name }}</span>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
            <div
                class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                <div class="d-flex mb-3">
                    @php
                        if (auth()->check() && auth()->user()->type == 1) {
                            $id_user = auth()->user()->id;
                            $check_wishlist = DB::table('user_recruitment')
                                ->where('recruitment_id', $item->id)
                                ->where('user_id', $id_user)
                                ->first();
                        }
                    @endphp
                    <a class="{{ isset($check_wishlist) && $check_wishlist->wishlist == 1 ? 'fas' : 'far' }} btn btn-light btn-square me-3"
                        data-employer-name="{{ $item->Employers->company_name }}"
                        data-add-wishlist="{{ $item->id }}" data-vitri="{{ $item->vi_tri }}" href=""
                        id="btn_wishlist_{{ $item->id }}">
                        <i id="icon_wishlist_{{ $item->id }}"
                            class="{{ isset($check_wishlist) && $check_wishlist->wishlist == 1 ? 'fas' : 'far' }} fa-heart text-primary"></i></a>
                    <a class="btn btn-primary" href="">Apply Now</a>
                </div>
                <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Date Line:
                    {{ $item->han_nop }}</small>
            </div>
        </div>
    </div>
@endforeach
