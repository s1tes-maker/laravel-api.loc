<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Site,Click};
use Carbon\Carbon;

class ApiHandleController extends Controller {

    public function handleClickInfo( Request $request ) {

		$referer=  $request->server('HTTP_REFERER');
		
		$site= Site::select('id')->where('url',$referer)->first();

		//если url нет, то вносим в базу данных
		if(!$site) {

			$site_new= new Site;
			$site_new->url= $referer;
			$site_new->save();
			$site= $site_new;
		}

		//сохраняем клик
		if($site) {

			$click= new Click;
			$click->x= $request->input('X');
			$click->y= $request->input('Y');
			$click->site_id= $site->id;
			$click->save();
		}

		return response()->json(['json_response'=>''],200);

	}	
    
    public function getSitesList() {

    	$sites= Site::select('id','url')->get();
    	
    	//для вывода времени
    	$clicks= Site::find(1)->clicks;
    	
		return response()->json(['json_response'=>$sites],200);
	
	}

	public function makeClicksInfo(Request $request) {
		//$request->input('siteId')
		$clicks= Site::find($request->input('siteId'))->clicks;
		$mas_clicks= [];
		$mas_months= [
			1=>'январь',
			2=>'февраль',
			3=>'март',
			4=>'апрель',
			5=>'май',
			6=>'июнь',
			7=>'июль',
			8=>'август',
			9=>'сентябрь',
			10=>'октябрь',
			11=>'ноябрь',
			12=>'декабрь'
		]; 

		foreach ($clicks as $click) {

			$year= $click->created_at->year;
			$month= $mas_months[$click->created_at->month];
			$day= $click->created_at->day;
			$hour= $click->created_at->hour;

			if(!isset($mas_clicks [$year]))
				$mas_clicks[$year]= [];

			if(!isset($mas_clicks [$year][$month]))
				$mas_clicks [$year][$month]= [];

			if(!isset($mas_clicks [$year][$month][$day]))
				$mas_clicks [$year][$month][$day]= [];

			if(!isset($mas_clicks [$year][$month][$day][$hour]))

				$mas_clicks [$year]
							[$month]
							[$day]
						   	[$hour] = 0;

			$mas_clicks [$click->created_at->year]
							[$month]
							[$day]
						   	[$hour]++;

		}//foreach

		return response()->json(['json_response'=>$mas_clicks],200);
	}
}
