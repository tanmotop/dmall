<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools">
            {!! $form->renderHeaderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    @if($form->hasRows())
        {!! $form->open() !!}
    @else
        {!! $form->open(['class' => "form-horizontal"]) !!}
    @endif

    {!! $form->open(['class' => "form-horizontal"]) !!}
        <div class="box-body">

            @if(!$tabObj->isEmpty())
                @include('admin::form.tab', compact('tabObj'))
            @else
                <div class="fields-group">

                    @if($form->hasRows())
                        @foreach($form->getRows() as $row)
                            {!! $row->render() !!}
                        @endforeach
                    @else
                        @foreach($form->fields() as $field)
                            {!! $field->render() !!}
                        @endforeach
                    @endif


                </div>
            @endif

        </div>
        <!-- /.box-body -->
        <div class="box-footer">

            @if( ! $form->isMode(\Encore\Admin\Form\Builder::MODE_VIEW)  || ! $form->option('enableSubmit'))
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @endif
            <div class="{{$width['label']}}">

            </div>
            <div class="{{$width['field']}}">

                {!! $form->submitButton() !!}

                {!! $form->resetButton() !!}

            </div>

        </div>

        @foreach($form->getHiddenFields() as $hiddenField)
            {!! $hiddenField->render() !!}
        @endforeach

        <!-- /.box-footer -->
    {!! $form->close() !!}
</div>
<script type="text/javascript">
    // 只针对商品编辑
    $('input[data-up=1]').each(function(i, item) {
        var $parent = $(this).parents('.has-many-attrs-form');
        var arr = $.parseJSON($(this).val())
        for (var i in arr) {
            $parent.find('input.' + i).val(arr[i])
        }
    })
</script>

