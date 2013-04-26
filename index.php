<?php
define('SEARCHCODE','<div>Suche: </div><input type="text" data-provide="typeahead" autocomplete="off" id="bzhc"></input><a> </a><div class="btn btn-danger" onclick="search(document.getElementById(\'bzhc\').value)"> Suchen</div>');
if(stristr($_SERVER["HTTP_USER_AGENT"],'bot')){exit();}

class MainApp{
	static function doHTTPRequest($url,$user_agent=""){
	return file_get_contents($url);		
	}
	static function paramsuitable($param){
		return (!is_array($param) && !empty($param));
		
		}
	static function parse_xml_data($data){
		$xml= simplexml_load_string($data);
		$current_flights=array();
		//$childs=$xml->children();
		for ($i=0;$i<count($xml->Flight);$i++){
			
			$current_flights[]= $xml->Flight[$i];
			
			}
		return $current_flights;
		}	

	static function search_for_data($data,$value,$type=array()/*The data type(in XML,actually elements) for example FROM_TO*/){
		$results=array();
		foreach($data as $k=>$v){
			//$rref=array();
			$hint=false;
			foreach($type as $tk=>$tv){
				//print $data[$k]->{$tv};
				//$rref[$tv]=$data[$k]->{$tv};
				if(stristr($data[$k]->{$tv},$value)){
					$hint=true;
					
					}
				
				}
			//$results[]=$rref;
			if($hint){$results[]=$data[$k];}
			}
			return $results;
		}
		//in url d for descending
		
		static function getorg_reg($ayt_reg){
		$str=str_replace('TC','TC-',$ayt_reg);
		$str=str_replace('D','D-',$str);	
	return $str;		
			}
static function generate_table_row($incode){
	return "<tr><td>{$incode}</td></tr>";
	}
	
	static function generate_lookup_html($flight_data){
		
	$html= '<h6>'.$flight_data->{'AIRLINE_NAME'}.'</h6> Flug '.$flight_data->{'FL_NUMBER'};
	$html.=(($flight_data->{'FROM_TO'}!='')? 'über '.$flight_data->{'FROM_TO'}:'');
	$html.='<br/>';
	$html.='<h5>Registrierung: '.MainApp::getorg_reg($flight_data->{'Registration'}).'</h5>';
	$html.=' Flugzeugtyp: '.$flight_data->{'AircraftCode'};
	$html.='<br/>';
	$html.='<a href="http://www.planepictures.net/netsearch4.cgi?stype=reg&srng=1&srch='.MainApp::getorg_reg($flight_data->{'Registration'}).'">Bilder bei planepics&#62;&#62;</a> ';
	$html.='<a href="http://flightradar24.com/data/airplanes/'.MainApp::getorg_reg($flight_data->{'Registration'}).'">Wo das Flugzeug so geflogen ist @flightradar&#62;&#62;</a>';
	$html.='<a href="http://www.airfleets.net/recherche/index.php?file=rechregis&key='.MainApp::getorg_reg($flight_data->{'Registration'}).'">   Infos @airfleets&#62;&#62;</a>';

	return $html;
		}
		
		static function dataforeach($org_data=array()){
			$return="";
			foreach($org_data as $k=>$v){
	$return.=self::generate_table_row(self::generate_lookup_html($org_data[$k]));
	
	}
	if(empty($return)){$return="<tr><td>Keine Flüge</tr></td>";}
			return $return;
			}	
};
/*
$fulldata='<?xml version="1.0" encoding="utf-8"?><Flights><Flight>
<ID>60717721</ID>
<AR_DP>D</AR_DP>
<AIRLINE>FHY</AIRLINE>
<FL_NUMBER>FHY 373</FL_NUMBER>
<AIRLINE_NAME>FREEBIRD</AIRLINE_NAME>
<SCH_TIME_SHORT>05:00</SCH_TIME_SHORT>
<EST_TIME_SHORT/>
<SCH_TIME>07.04.2013 05:00</SCH_TIME>
<EST_TIME/>
<ORG_DEST>Saarbruecken</ORG_DEST>
<FROM_TO/>
<CHECKIN_BELTS>256-258</CHECKIN_BELTS>
<GATE>64</GATE>
<INT_DOM>I</INT_DOM>
<TERMINAL>T2</TERMINAL>
<REMARK/>
<FlightCode>FHY</FlightCode>
<AirlineCode>FHY</AirlineCode>
<FlightStatus>SKD</FlightStatus>
<AircraftCode>A3202</AircraftCode>
<ImagePath>FHY.PNG</ImagePath>
<Registration>TCFBO</Registration>
<TRANSFER_ID>2744</TRANSFER_ID>
<PP/>
</Flight><Flight><ORG_DEST>Saarbruecken</ORG_DEST><ID>60557768</ID></Flight><Flight><ORG_DEST>Saa2rbruecken</ORG_DEST><ID>CX</ID></Flight><Flight><ID>25</ID></Flight></Flights>';*/
$fulldata=file_get_contents('http://www.icfairports.com/mobile/flights.aspx?flightType=I&arrdep=D&lang=DE');
$data=MainApp::parse_xml_data($fulldata);

if(isset($_GET["mode"])&&MainApp::paramsuitable($_GET["mode"])){
	if($_GET["mode"]==="getall"){
		
		
		print SEARCHCODE.MainApp::dataforeach($data);exit();
			
		
		}
	if($_GET["mode"]==="search"){
		if(isset($_GET["mode"])&&MainApp::paramsuitable($_GET["searchstr"])){
			$lookupsearchstr=MainApp::search_for_data($data,$_GET["searchstr"],array('ORG_DEST','FROM_TO','Registration','AircraftCode','AIRLINE_NAME','FL_NUMBER'));	
			
			print SEARCHCODE.MainApp::dataforeach($lookupsearchstr);
			exit();
			}
				
		}	
	
	}
$lookupscn=MainApp::search_for_data($data,'Saarbruecken',array('ORG_DEST','FROM_TO'));
$lookupzqw=MainApp::search_for_data($data,'Zweibruecken',array('ORG_DEST','FROM_TO'));
/*
$scn_tables="";$zqw_tables="";
foreach($lookupscn as $k=>$v){
	$scn_tables.=MainApp::generate_table_row(MainApp::generate_lookup_html($lookupscn[$k]));
	
	}
foreach($lookupzqw as $k=>$v){
	$zqw_tables.=MainApp::generate_table_row(MainApp::generate_lookup_html($lookupzqw[$k]));
	
	}
	
*/
$scn_tables=MainApp::dataforeach($lookupscn);
$zqw_tables=MainApp::dataforeach($lookupzqw);	
//$scn_tables=MainApp::generate_table_row('Sun Express');
//$zqw_tables=MainApp::generate_table_row('TUIjet');
//print $data[1]->ID

//var_dump($lookupscn);
require "template.php";







?>