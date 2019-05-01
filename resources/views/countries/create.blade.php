@extends('laravel-events-calendar::countries.layout')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>@lang('views.add_new_country')</h2>
            </div>
        </div>
    </div>

   @include('partials.forms.error-management', [
      'style' => 'alert-danger',
    ])

    <form action="{{ route('countries.store') }}" method="POST">
        @csrf

         <div class="row">
            <div class="col-12">
                @include('partials.forms.input', [
                      'title' => __('general.name'),
                      'name' => 'name',
                      'placeholder' => '',
                      'value' => old('name'),
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('partials.forms.input', [
                      'title' => __('views.country_code'),
                      'name' => 'code',
                      'value' => old('code'),
                      'required' => true,
                ])
            </div>

            <div class="col-12">
                @include('partials.forms.select', [
                      'title' => __('general.continent'),
                      'name' => 'continent_id',
                      'placeholder' => __('general.select_continent'),
                      'records' => $continents,
                      'liveSearch' => 'false',
                      'mobileNativeMenu' => true,
                      'seleted' => old('continent_id'),
                      'required' => true,
                ])
            </div>
        </div>

        @include('partials.forms.buttons-back-submit', [
            'route' => 'countries.index'  
        ])

    </form>


@endsection