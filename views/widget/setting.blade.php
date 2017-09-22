<div class="form-group">
    <label class="">배너 선택</label>
    <select class="form-control __xe_select_group" name="group_id">
        @foreach($groups as $group)
            <option @if($group->id === array_get($args, 'group_id')) selected="selected" @endif value="{{ $group->id }}" data-url="{{ route('banner::group.edit', ['group_id' => $group->id]) }}">{{ $group->title }}</option>
        @endforeach
    </select>
    </div>
    <p class="help-block"></p>
</div>
