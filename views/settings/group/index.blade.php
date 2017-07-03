@section('page_title')
    <h2><a href="{{ route('banner::group.index') }}"><i class="xi-arrow-left"></i>배너 그룹</a></h2>
@stop

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">
                            생성된 배너그룹 목록
                        </h3>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('banner::group.create') }}" class="xe-btn xe-btn-primary" data-toggle="xe-page-modal">새 그룹 생성</a>
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
                            <tr>
                                <td>{{ $group->title }}</td>
                                <td>{{ $group->item_count }}</td>
                                <td>{{ $group->createdAt->format('Y.m.d H:i:s') }}</td>
                                <td>
                                    <a class="xe-btn xe-btn-xs xe-btn-default"
                                       onclick="window.open(this.href, 'bannerEditor', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');return false"
                                       href="{{ route('banner::group.edit', ['group_id' => $group->id]) }}">편집</a>
                                    <a class="xe-btn xe-btn-default xe-btn-xs" role="button" data-toggle="collapse" href="#widget-code-{{ $group->id }}">
                                        위젯코드
                                    </a>
                                </td>
                            </tr>
                            <tr id="widget-code-{{ $group->id }}" class="collapse">
                                <td colspan="4" class="">
                                    <div class="well">{{ $group->widget_code }}</div>
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
