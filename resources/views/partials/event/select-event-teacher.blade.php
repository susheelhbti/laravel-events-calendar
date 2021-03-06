@section('javascript-document-ready')
    @parent

    {{-- Update the multiple teachers hidden input when a teacher is selected in the bootstrap select --}}
    $('#teacher').change(function(){
        $('#multiple_teachers').val($('#teacher').val());
     });

     {{-- Select the teachers saved when the edit view is open  --}}
     var teachersSelected = $('#multiple_teachers').val();
     if (teachersSelected){
         var teachersSelectedArray = teachersSelected.split(',');
         $('#teacher').selectpicker('val', teachersSelectedArray);
     }

@stop

<div class="row">
    <div class="col-12">
        <div class="form-group" >
            <label>@lang('laravel-events-calendar::general.teachers')</label>
            <select id="teacher" name="selected_teachers" class="selectpicker multiselect" multiple data-live-search="true" title="@lang('laravel-events-calendar::general.choose')">
                @foreach ($teachers as $value => $teacher)
                    <option value="{{$value}}">{!! $teacher !!}</option>
                @endforeach
            </select>
            <input type="hidden" name="multiple_teachers" id="multiple_teachers" value="{{$multiple_teachers}}" />
        </div>
    </div>
    <div class="col-12 mb-3">
        <button type="button" data-toggle="modal" class="btn btn-primary float-right" data-remote="{{ route('teachers.modal') }}" data-target=".modalFrame"><i class="fa fas fa-plus-circle "></i> @lang('laravel-events-calendar::teacher.create_new_teacher')</button>
    </div>
</div>

@include('laravel-events-calendar::partials.modal-frame')
