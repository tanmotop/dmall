@foreach($list as $k => $v)
<div class="form-group ">

    <label for="{{$k}}" class="col-sm-2 control-label">{{$labels[$k]}}</label>

    <div class="col-sm-8">

        <div class="input-group">
            <input style="width: 100px; text-align: center;" id="{{$k}}" name="{{$k}}" value="{{$v}}" class="form-control {{$k}}" placeholder="0" type="text">
        </div>

    </div>
</div>
@endforeach