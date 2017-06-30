<div class="form-group">
    <label class="">배너 선택</label>
    <div class="input-group">
        <select class="form-control __xe_select_group" name="group_id">
            @foreach($groups as $group)
                <option @if($group->id === array_get($args, 'group_id')) selected="selected" @endif value="{{ $group->id }}" data-url="{{ $group->edit_url }}">{{ $group->title }}</option>
            @endforeach
        </select>
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="window.open($('.__xe_select_group option:selected').data('url'), 'bannerEditor', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');return false">편집</button>
        </span>
    </div>
    <p class="help-block"></p>
</div>
