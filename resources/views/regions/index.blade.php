@extends('laravel-events-calendar::regions.layout')

@section('javascript-document-ready')
    @parent
    {{--  Clear filters on click reset button --}}
        $("#resetButton").click(function(){
            $("input[name=keywords]").val("");
            $('form.searchForm').submit();
        });
@stop

@section('content')
    <div class="container max-w-md px-0">
        <div class="row">
            <div class="col-12 col-sm-6">
                <h3>@lang('laravel-events-calendar::region.regions_management')</h3>
            </div>
            <div class="col-12 col-sm-6 mt-4 mt-sm-0 text-right">
                <a class="btn btn-success create-new" href="{{ route('regions.create') }}"><i class="fa fas fa-plus-circle"></i> @lang('laravel-events-calendar::region.create_new_region')</a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        {{-- Search form --}}
        <form class="searchForm mt-3" action="{{ route('regions.index') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-12 col-sm-7 pr-sm-2">
                    @include('laravel-form-partials::input', [
                        'name' => 'keywords',
                        'placeholder' => __('laravel-events-calendar::region.search_by_region_name'),
                        'value' => $searchKeywords
                    ])
                </div>
                <div class="col-12 col-sm-5 mt-2 mt-sm-0">
                    <input type="submit" value="@lang('laravel-events-calendar::general.search')" class="btn btn-primary float-right ml-2" style="white-space: normal;">
                    <a id="resetButton" class="btn btn-outline-primary float-right" href="#">@lang('laravel-events-calendar::general.reset')</a>
                </div>
            </div>
        </form>
        
        {{-- List of regions --}}
        <div class="regionsList my-4">
            @foreach ($regions as $region)
                <div class="row bg-white shadow-1 rounded mb-3 pb-2 pt-3 mx-1">
                    <div class="col-12 py-1 title">
                        <h5 class="darkest-gray">{{ $region->name }}</h5>
                    </div>
                    <div class="col-12 mb-4">        
                        <i data-toggle="tooltip" data-placement="top" title="" class="far fa-barcode-alt mr-1 dark-gray" data-original-title="@lang('laravel-events-calendar::general.code')"></i>
                        {{ $region->code }} 
                        
                        <i data-toggle="tooltip" data-placement="top" title="" class="far fa-globe-americas mr-1 ml-4 dark-gray" data-original-title="@lang('laravel-events-calendar::general.country')"></i>
                        @if($region->country_id){{ $countries[$region->country_id] }}@endif
                    </div>
                    
                    <div class="col-12 pb-2 action">
                        <form action="{{ route('regions.destroy',$region->id) }}" method="POST">
                            <a class="btn btn-primary float-right" href="{{ route('regions.edit',$region->id) }}">@lang('laravel-events-calendar::general.edit')</a>
                            
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-link pl-0">@lang('laravel-events-calendar::general.delete')</button>
                        </form>
                    </div>
                </div>
                
                
            @endforeach    
        </div>

        {!! $regions->links() !!}
    </div>

@endsection
