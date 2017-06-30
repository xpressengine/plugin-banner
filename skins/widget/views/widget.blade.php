        <ul class="list-unstyled">
            @foreach($items as $item)
                <li class="media">
                    <div class="media-left">
                        <a href="{{ url($item->link) }}" target="{{ $item->link_target }}">
                            <img src="{{ $item->image_url }}" alt="" width="100" height="75">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{ $item->title }}</h4>
                        {!!  nl2br($item->content) !!}
                    </div>
                </li>
            @endforeach
        </ul>
