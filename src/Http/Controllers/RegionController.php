<?php

namespace DavideCasiraghi\LaravelEventsCalendar\Http\Controllers;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DavideCasiraghi\LaravelEventsCalendar\Models\Region;
use DavideCasiraghi\LaravelEventsCalendar\Models\Country;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RegionController extends Controller
{
    /* Restrict the access to this resource just to logged in users except show view */
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countries = Country::getCountries(null);

        $searchKeywords = $request->input('keywords');
        if ($searchKeywords) {
            $regions = Region::
                        select('region_translations.region_id AS id', 'name', 'timezone', 'locale', 'country_id')
                        ->join('region_translations', 'regions.id', '=', 'region_translations.region_id')
                        ->where('name', 'like', '%'.$searchKeywords.'%')
                        ->where('locale', 'en')
                        ->orderBy('name')
                        ->paginate(20);
        } else {
            $regions = Region::
                        select('region_translations.region_id AS id', 'name', 'timezone', 'locale', 'country_id')
                        ->join('region_translations', 'regions.id', '=', 'region_translations.region_id')
                        ->where('locale', 'en')
                        ->orderBy('name')
                        ->paginate(20);
        }

        // Countries available for translations
        $countriesAvailableForTranslations = LaravelLocalization::getSupportedLocales();

        return view('laravel-events-calendar::regions.index', compact('regions'))
            ->with('i', (request()->input('page', 1) - 1) * 20)
            ->with('countriesAvailableForTranslations', $countriesAvailableForTranslations)
            ->with('searchKeywords', $searchKeywords)
            ->with('countries', $countries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::getCountries(null);

        return view('laravel-events-calendar::regions.create')
                ->with('countries', $countries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form datas
        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'country_id' => 'required',
                'timezone' => 'required',
            ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $region = new Region();

        $this->saveOnDb($request, $region);

        return redirect()->route('regions.index')
                        ->with('success', __('laravel-events-calendar::messages.region_added_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \DavideCasiraghi\LaravelEventsCalendar\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        return view('laravel-events-calendar::regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \DavideCasiraghi\LaravelEventsCalendar\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        $countries = Country::getCountries(null);

        return view('laravel-events-calendar::regions.edit', compact('region'))
                    ->with('countries', $countries);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \DavideCasiraghi\LaravelEventsCalendar\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        request()->validate([
            'name' => 'required',
            'country_id' => 'required',
            'timezone' => 'required',
        ]);

        $this->saveOnDb($request, $region);

        return redirect()->route('regions.index')
                        ->with('success', __('laravel-events-calendar::messages.region_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \DavideCasiraghi\LaravelEventsCalendar\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('regions.index')
                        ->with('success', __('laravel-events-calendar::messages.region_deleted_successfully'));
    }

    // **********************************************************************

    /**
     * Return the single event region datas by cat id.
     *
     * @param  int $cat_id
     * @return \DavideCasiraghi\LaravelEventsCalendar\Models\Region
     */
    /*public function eventregiondata($cat_id)
    {
        $ret = DB::table('regions')->where('id', $cat_id)->first();
        //dump($ret);

        return $ret;
    }*/

    // **********************************************************************

    /**
     * Save/Update the record on DB.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \DavideCasiraghi\LaravelEventsCalendar\Models\Region $region
     * @return void
     */
    public function saveOnDb($request, $region)
    {
        $region->name = $request->get('name');
        $region->country_id = $request->get('country_id');
        $region->timezone = $request->get('timezone');
        $region->slug = Str::slug($region->name, '-');

        $region->save();
    }
}