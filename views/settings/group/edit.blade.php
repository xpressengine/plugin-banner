<div class="widget-wrap __xe_bannerEditor" data-id="{{ $group->id }}" data-update-url="{{ route('banner::group.update', ['group_id' => $group->id]) }}">
    <header>
        <h1>
            <i class="xi-xpressengine"></i>
            XE Banner - {{ $group->title }}
        </h1>
    </header>
    <div class="widget-snb">
        <div class="snb-section">
            <button type="submit" class="xe-btn xe-btn-primary __xe_add_item_btn" data-url="{{ route('banner::item.store', ['group_id' => $group->id]) }}">아이템추가</button>
        </div>
        <div class="snb-section">
            <ul class="__xe_item_list list-unstyled">
                @foreach($items as $item)
                    <li data-id="{{ $item->id }}" data-edit-url="{{ $item->edit_url }}" data-delete-url="{{ $item->delete_url }}">
                        <div class="alert alert-dismissible {{ $item->is_visible ? 'alert-info' : 'alert-warning' }}" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span class="selected" style="display: none;"><i class="xi-label"></i></span>
                            <img src="{{ $item->image_url }}" alt="" height="30px">
                            <span class="title">{{ $item->title }}</span>
                            @if($item->status !== 'show') <span><i class="xi-eye-off"></i></span> @endif
                        </div>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>
    <div class="widget-container">
        <div class="widget-content">
            <div class="__xe_item_editor">
                <p class="lead">select item</p>
            </div>
        </div>
    </div>
</div>


<script>

    $(function($) {
        window.bannerEditor = $('.__xe_bannerEditor').bannerEditor({});
    });

</script>
