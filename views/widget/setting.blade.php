<div class="form-group">
    <label class="">배너 선택</label>
    <select class="form-control __xe_select_group __xe_widget_banner_select_group" name="group_id">
        <option value="">선택하세요</option>
        @foreach($groups as $group)
            <option @if($group->id === array_get($args, 'group_id')) selected="selected" @endif value="{{ $group->id }}" data-url="{{ route('banner::group.edit', ['group_id' => $group->id]) }}">{{ $group->title }}</option>
        @endforeach
    </select>
    <p class="help-block"></p>

    <div>
        <a class="xe-btn xe-btn-secondary banner_group_setting" >배너 관리</a>
        <a class="xe-btn xe-btn-primary" href="{{ route('banner::group.index') }}" target="_blank">배너 관리 페이지 이동</a>
        {{--<a class="xe-btn xe-btn-primary" href="{{ route('banner::group.create') }}" data-toggle="xe-page-modal">배너 추가</a>--}}
    </div>
</div>

<div class="form-group">
    <label>랜덤</label>
    <select name="random" class="form-control">
        <option value="activated" @if(array_get($args, 'random') == 'activated') selected="selected" @endif >사용</option>
        <option value="deactivated" @if(array_get($args, 'random') == 'deactivated') selected="selected" @endif >사용안함</option>
    </select>
</div>

<script>
    $(function () {
        $('.__xe_widget_banner_select_group').on('change', function (e) {
            var bannerGroupId = getSelectedBannerGroupId();
        });

        $('.banner_group_setting').on('click', function (e) {
            var bannerGroupId = getSelectedBannerGroupId();
            if (bannerGroupId == '') {
                alert('선택된 배너가 없습니다.');
            } else {
                var href = $('.__xe_widget_banner_select_group').find('option[value="' + bannerGroupId + '"]').data('url');
                window.open(href, 'bannerEditor', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');
                return;
            }
        });

        function getSelectedBannerGroupId()
        {
            var bannerGroupId = $('.__xe_widget_banner_select_group').find('option:selected').val();
            if (bannerGroupId == undefined) {
                bannerGroupId == '';
            }

            return bannerGroupId;
        }

    });
</script>