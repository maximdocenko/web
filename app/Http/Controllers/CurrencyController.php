<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Currency;

class CurrencyController extends Controller
{

    public function __construct() {
        $this->url = 'https://www.cbr.ru/scripts/';
        $this->date_req1 = '01/11/2022';
        $this->date_req2 = '07/12/2022';
        // С 1 ноября по 7 декабря данные в базе
    }

    public function data($url) {
        $xml = simplexml_load_string(
            file_get_contents($url), "SimpleXMLElement", LIBXML_NOCDATA
        );
        $json = json_encode($xml);
        return json_decode($json,true);
    }

    public function fetch() {
        $data = $this->data($this->url."XML_daily.asp");
        $equal = [];
        foreach($data['Valute'] as $item) {
            $equal[$item['@attributes']['ID']]['NumCode'] = $item['NumCode'];
            $equal[$item['@attributes']['ID']]['CharCode'] = $item['CharCode'];
            $equal[$item['@attributes']['ID']]['Name'] = $item['Name'];
            $xml_dymamic = $this->data($this->url."XML_dynamic.asp?date_req1=".$this->date_req1."&date_req2=".$this->date_req2."&VAL_NM_RQ=".$item['@attributes']['ID']);
            $i=0;
            foreach($xml_dymamic['Record'] as $dymamic) {
                $equal[$item['@attributes']['ID']]['data'][$i]['Date'] = new Carbon($dymamic['@attributes']['Date']);
                $equal[$item['@attributes']['ID']]['data'][$i]['Value'] = $dymamic['Value'];
                $i++;
            }
        }

        foreach($equal as $key => $item) {

            foreach($item['data'] as $value) {
                Currency::create([
                    'valuteID' => $key,
                    'numCode' => $item['NumCode'],
                    'сharCode' => $item['CharCode'],
                    'name' => $item['Name'],
                    'value' => $value['Value'],
                    'date' => $value['Date']
                ]);
            }

        }
    }

    public function index() {
        
        //$this->fetch();

        $data = Currency::select('valuteID', 'name')->distinct()->get();

        $output = [];

        if(request()->valuteID && request()->date_req1 && request()->date_req2) {
            $output = Currency::where("valuteID", request()->valuteID)
            ->whereBetween("date", [request()->date_req1, request()->date_req2])->get();
        }

        return view("index", ['data' => $data, 'output' => $output]);
    }
}
