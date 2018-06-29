<form action="{{ route('banner::item.update', ['group_id'=>$item->group_id, 'item_id'=>$item->id]) }}"
      data-submit="xe-ajax" data-callback="bannerEditor.itemSaved" method="post" enctype="multipart/form-data"
>
    {{ method_field('put') }}
    {{ uio('formSelect', ['name'=>'status', 'label'=>'노출여부', 'options' => ['show'=>'노출', 'hidden'=>'미노출'], 'value'=>$item->status]) }}

    <div class="form-group">
        <label for="">링크</label>
        <div class="input-group">
            <input type="text" class="form-control" name="link" value="{{ $item->link }}">
            <span class="input-group-addon">
                <label><input type="checkbox" name="link_target" value="_blank" @if($item->link_target === '_blank') checked="checked" @endif > 새창</label>
            </span>
        </div>
    </div>

    {{ uio('formText', ['name'=>'title', 'label'=>'제목', 'value'=>$item->title]) }}
    {{ uio('formTextarea', ['name'=>'content', 'label'=>'내용', 'value'=>e($item->content)]) }}

    {{ uio('formImage', ['name'=>'image', 'label'=>'이미지 ('.$item->getImageSize('width').'x'.$item->getImageSize('height').')', 'value'=>$item->image ]) }}

    <div class="form-group">
        <label for="">원본 사이즈로 업로드</label>
        <div class="well">
            <div class="">
                <label><input name="original_image_size" type="checkbox" value="1"> 원본 사이즈로 업로드 (배너 이미지를 원본 사이즈로 업로드 하려면 체크하세요.)</label>
            </div>
        </div>
    </div>

    <label for="">타이머 지정</label>
    <div class="well">
        <div class="">
            <label><input name="use_timer" class="__xe_use_timer" type="checkbox" value="1" @if($item->use_timer) checked="checked" @endif> 타이머 사용</label>
        </div>
        <div class="__xe_timer_setting" @if(!$item->use_timer) style="display: none;" @endif>
            <hr>
            <div class="row">
                <div class="col-xs-12"><label for="">노출 시작 일시</label></div>
                <div class="col-md-6">
                    {{ uio('formText', ['name'=>'started_at_date', 'type'=>'date', 'value'=>($item->started_at ? $item->started_at->format('Y-m-d') : date('Y-m-d'))]) }}
                </div>
                <div class="col-md-6">
                    {{ uio('formText', ['name'=>'started_at_time', 'type'=>'time', 'value'=>($item->started_at ? $item->started_at->format('H:i') : $item->created_at->format('H:i'))]) }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12"><label for="">노출 종료 일시</label></div>
                <div class="col-md-6">
                    {{ uio('formText', ['name'=>'ended_at_date', 'type'=>'date', 'value'=>($item->ended_at ? $item->ended_at->format('Y-m-d') : '2038-01-19')]) }}
                </div>
                <div class="col-md-6">
                    {{ uio('formText', ['name'=>'ended_at_time', 'type'=>'time', 'value'=>($item->ended_at ? $item->ended_at->format('H:i') : '00:00')]) }}
                </div>
            </div>
        </div>

    </div>

    @if($form = $skin->renderBannerSetting($item->etc))
        <div class="panel panel-default">
            <div class="panel-heading">스킨설정</div>
            <div class="panel-body">
                {!! $form !!}
            </div>
        </div>
    @endif

    <button type="submit" class="xe-btn xe-btn-primary" onclick="bannerEditor.lock();">저장</button>
</form>
