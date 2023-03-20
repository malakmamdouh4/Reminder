<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountriesResource;
use App\Http\Resources\HomeResource;
use App\Models\Country;
use App\Models\Section;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiTrait;
    public function home(){
       $lang = $request->header('lang') ?? 'ar';

       $data = [];
       $sections = Section::get();
       $data['sections'] = HomeResource::collection($sections);
       return $this->successReturn('',$data);
    }

    public function countries(Request $request)
    {
        $lang = $request->header('lang') ?? 'ar';

        $data = [];
        $countries = Country::get();
        $data['countries'] = CountriesResource::collection($countries);

        return $this->successReturn('',$data);
    }

}
