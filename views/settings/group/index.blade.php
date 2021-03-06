@section('page_title')
    <h2><a href="{{ route('banner::group.index') }}"><i class="xi-arrow-left"></i>배너 목록</a></h2>
@stop

<div class="container-fluid container-fluid--part">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">
                                생성된 배너 목록
                            </h3>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('banner::group.create') }}" class="xe-btn xe-btn-primary" data-toggle="xe-page-modal">새 배너 생성</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">제목</th>
                                <th scope="col">아이템수</th>
                                <th scope="col">생성일</th>
                                <th scope="col">관리</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr class="__group-item">
                                    <td>{{ $group->title }}</td>
                                    <td>{{ $group->count }}</td>
                                    <td>{{ $group->created_at->format('Y.m.d H:i:s') }}</td>
                                    <td>
                                        <a class="xe-btn xe-btn-xs xe-btn-default" onclick="window.open(this.href, 'bannerEditor', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');return false" href="{{ route('banner::group.edit', ['group_id' => $group->id]) }}">아이템 관리</a>
                                        <a class="xe-btn xe-btn-default xe-btn-xs" role="button" data-toggle="collapse" href="#widget-code-{{ $group->id }}">위젯코드</a>
                                        <a class="xe-btn xe-btn-default xe-btn-xs" href="{{ route('banner::group.update', ['group_id' => $group->id]) }}" role="button" data-toggle="xe-page-modal">설정</a>
                                        <a class="xe-btn xe-btn-danger xe-btn-xs __group-delete-button" data-group-id="{{ $group->id }}" data-delete-url="{{ route('banner::group.delete', ['group_id' => $group->id]) }}" style="color: #ffffff">삭제</a>
                                    </td>
                                </tr>
                                <tr id="widget-code-{{ $group->id }}" class="collapse">
                                    <td colspan="4" class="">
                                        <div class="well">{{ $group->getWidgetCode() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.__group-delete-button').click(function () {
            if (confirm('배너를 정말로 삭제하시겠습니까?\n사용 중인 배너를 삭제하면 사이트에 문제가 생길 수 있습니다.') === true) {
                var $this = $(this)
                var url = $this.data('delete-url')
                var groupId = $this.data('group-id')
                
                XE.delete(url)
                    .then(function (res) {
                        if (res.data.success === true) {
                            $this.closest('.__group-item').remove()
                            $('#widget-code-' + groupId).remove()
                            XE.toast('success', '배너 삭제가 완료됐습니다.')
                        }
                    })
            }
        })
    })
</script>
