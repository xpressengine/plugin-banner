<li data-id="{{ $item->id }}" data-edit-url="{{ route('banner::item.edit', ['group_id' => $item->group_id, 'item_id' => $item->id]) }}" data-delete-url="{{ route('banner::item.delete', ['group_id' => $item->group_id, 'item_id' => $item->id]) }}">
    <div class="alert alert-dismissible {{ $item->isVisible() ? 'alert-info' : 'alert-warning' }}" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="selected" style="display: none;"><i class="xi-label"></i></span>
        <img src="{{ $item->imageUrl() }}" alt="" height="30px">
        <span class="title">{{ $item->title }}</span>
        @if($item->status !== 'show') <span><i class="xi-eye-off"></i></span> @endif
    </div>
</li>