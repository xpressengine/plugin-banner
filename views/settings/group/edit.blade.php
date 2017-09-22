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
                    @include('banner::views.settings.group.item', compact('item'))
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
