<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div id="{{$id}}" style="width: 100%; height: 100%;">
            <p>{!! old($column, $value) !!}</p>
        </div>
        <input type="hidden" name="{{$column}}" value="{{ old($column, $value) }}" />
        @include('admin::form.help-block')
    </div>
</div>