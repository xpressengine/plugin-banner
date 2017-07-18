<form action="{{ route('banner::group.store') }}" method="POST">
<div class="xe-modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="xe-modal-title" id="modalLabel">새 배너 생성</h4>
</div>
<div class="xe-modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        {{ uio('formText', ['name'=>'title', 'label'=>'제목', 'description'=>'타 배너와 구분할 수 있는 제목을 지정하세요.']) }}
        {{ uio('skinSelect', ['target' => 'widget/banner@widget', 'name'=>'skin']) }}

</div>
<div class="xe-modal-footer">
    <button type="button" class="xe-btn xe-btn-secondary" data-dismiss="xe-modal">{{ xe_trans('xe::cancel') }}</button>
    <button type="submit" class="xe-btn xe-btn-primary xe-btn-submit">{{ xe_trans('xe::add') }}</button>
</div>
</form>
