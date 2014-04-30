<?php

namespace App\Modules\Dashboard\Controllers;

use Auth,
    Excel,
    Group,
    GroupCustomer,
    Input,
    Shipping,
    Redirect,
    Session,
    Validator,
    Mail,
    View;

class DashboardController extends \BaseController {

    public function getHome() {
        return View::make('dashboard::dashboard.index');
    }

    public function sendOne() {
        return View::make('dashboard::dashboard.sendone');
    }

    public function sendOneAgend() {
        return View::make('dashboard::dashboard.sendoneagend');
    }

  
    public function saveOne() {
        $data = array(
            'name' => 'Shiping' . rand(0, 999),
            'idcountry' => 1,
            'typeEntry' => 1,
            'idtypesend' => Input::get('typesend', 1),
            'dateAgend' => Input::get('dateAgend', null),
        );
        $shipping = new Shipping();
        $response = $shipping->saveData($data);
        $route = (Input::get('typesend', 1) == 1) ? 'sendone' : 'sendoneagend';
        if ($response["state"] == 1) {
            $sms = array(
                array(Input::get('phone'), Input::get('message'), Input::get('dateAgend', null)),
            );
            $dataSms = array(
                'idshipping' => $response['id'],
                'idUser' => Auth::user()->id,
                'typesend' => Input::get('typesend', 1),
                'idcompany' => Auth::user()->idcompany,
                'codCountry' => '+51',
            );
            if (count($sms) > 0 && $this->sendSms($sms, $dataSms))
                return Redirect::route($route)->with("msg", $response["msg"]);
            else
                return Redirect::route($route)->withErrors('Msj no enviados');
        } else
            return Redirect::route($route)->withErrors($response["msg"]);
    }

    public function sendGroup() {
        $group = Group::where('flagAct', '=', 1)->where('groups.iduser', '=', Auth::user()->id)->lists('name', 'id');
        return View::make('dashboard::dashboard.sendgroup', compact('group'));
    }

    public function sendGroupAgend() {
        $group = Group::where('flagAct', '=', 1)->where('groups.iduser', '=', Auth::user()->id)->lists('name', 'id');
        return View::make('dashboard::dashboard.sendgroupagend', compact('group'));
    }

    public function saveGroup() {
        $data = array(
            'name' => 'ShipingGroup' . rand(0, 999),
            'idcountry' => 1,
            'typeEntry' => 1,
            'idtypesend' => Input::get('typesend', 1),
            'dateAgend' => Input::get('dateAgend', null),
        );
        $shipping = new Shipping();
        $response = $shipping->saveData($data);
        $route = (Input::get('typesend', 1) == 1) ? 'sendgroup' : 'sendgroupagend';
        if ($response["state"] == 1) {
            $groupCustomer = new GroupCustomer;
            $dataGC = $groupCustomer->allData(array('idgroup' => Input::get('idgroup', 1)));
//                    var_dump($dataGC->toArray());Exit;
            $sms = array();
            foreach ($dataGC as $key => $value) {
                $sms[] = array($value->phone, Input::get('message'), Input::get('dateAgend', null));
            }
            $dataSms = array(
                'idshipping' => $response['id'],
                'idUser' => Auth::user()->id,
                'typesend' => Input::get('typesend', 1),
                'idcompany' => Auth::user()->idcompany,
                'codCountry' => '+51',
            );
            if (count($sms) > 0 && $this->sendSms($sms, $dataSms))
                return Redirect::route($route)->with("msg", 'Sus mensajes han sido enviado correctamente');
            else
                return Redirect::route($route)->withErrors('Msj no enviados');
        } else
            return Redirect::route($route)->withErrors($response["msg"]);
    }

    public function sendFile() {
//        
        $command = Session::get('command', '');
        return View::make('dashboard::dashboard.sendfile', array('command' => $command));
    }

    public function saveFile() {



        $rules = array(
            'file' => 'mimes:txt,csv',
        );
        $message = array(
            'mime' => 'Suba el tipo de archivo correcto'
        );
        $validation = Validator::make(Input::all(), $rules, $message);
        $response = array(
            'state' => 0,
            'msj' => 'Datos invalidos',
        );
        $file = Input::file('file');



        if ($validation->passes() && Input::hasFile('file')) {
            $fileName = 'user_' . Auth::user()->id . '_date_' . date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();

//                    $extension = $file->getClientOriginalExtension();
            $upload = $file->move(storage_path('files'), $fileName);
            if ($upload) {
//                $dataCSV = Excel::load(storage_path('files') . '/' . $fileName)
//                        ->setDelimiter(';')->select(/* array(2) */)
//                        ->toArray();

                $data = array(
                    'name' => 'ShipingFile' . rand(0, 999),
                    'typeEntry' => 3,
                    'idcountry' => 1,
//                            'idtypesend' => 3, 
                );



                //  if (is_numeric($dataCSV[0][1])) {
                $shipping = new Shipping();
                $response = $shipping->saveData($data);
                $datas = array('id' => $response['id'], 'file_shipping' => $fileName, 'flagAct' => 0, 'flag_agend' => 1, 'typesend' => Input::get('typesend', 1));
                $shipping->editData($datas);
                // $ruta = APPLICATION_PATH;
//                echo shell_exec('dir');Exit;
                //echo "C:\\xampp\php\php.exe -f C:\xampp\htdocs\sms\cron\Read.php -- -a '".$response['id']. "' -b '".Auth::user()->id. "' -c '".Input::get('typesend', 1). "' -d '".Auth::user()->idcompany. "' -e '".$fileName;exit;
//                $command = "php -f $ruta/Read.php -- -a ".$response['id']. " -b ".Auth::user()->id. " -c ".Input::get('typesend', 1). " -d ".Auth::user()->idcompany. " -e ".$fileName;
//                Session::put('command', $command);
                //   exec(" C:\\xampp\\php\\php.exe -f $ruta/Read.php -- -a ".$response['id']. " -b ".Auth::user()->id. " -c ".Input::get('typesend', 1). " -d ".Auth::user()->idcompany. " -e ".$fileName );
                return Redirect::route('sendfile')->with("msg", $response["msg"]);

                //$valores = new \
                // $valores->readFileExcel();
//   $sendSms = new \SmartWayRepository(new $Method);
                //  $reponse = $sendSms->send($response['id'],$fileName,Input::get('typesend', 1));
//                    if ($response['state'] == 1) {
//                        $sms = array();
//                        foreach ($dataCSV as $key => $value) {
//                            $num = isset($value[1]) ? $value[1] : null;
//                            $msj = isset($value[2]) ? $value[2] : null;
//                            $fec = isset($value[3]) ? $value[3] : null;
//                        $sms[] = array($num,$msj,$fec);
//                        }
//                        //var_dump($sms);exit;
//                        $dataSms = array(
//                            'idshipping' => $response['id'],
//                            'idUser' => Auth::user()->id,
//                            'typesend' => Input::get('typesend', 1),
//                            'idcompany' => Auth::user()->idcompany,
//                            'codCountry' => '+51',
//                        );
//                        if (count($sms) > 0 && $this->sendSms($sms, $dataSms))
//                            return Redirect::route('sendfile')->with("msg", $response["msg"]);
//                        else
//                            return Redirect::route('sendfile')->withErrors('Msj no enviado');
//                    }
//                } else {
//                    return Redirect::route('sendfile')->with("errorResult", 'Error en el formato de separación de los campos (Celular error , Mensaje error)');
//                }
            } else {
                $response = array(
                    'state' => 0,
                    'msj' => 'Error, archivo no guardado',
                );
            }
        } else {
            return Redirect::route('sendfile')->with("errorResult", 'Formato de Archivo inválido');
        }
//                return ($response);exit;
        if ($response["state"] == 1)
            return Redirect::route('sendfile')->with("msg", $response["msj"])->with('errorResult', '(Celular OK , Mensaje OK)');
        else
            return Redirect::route('sendfile')->withErrors($response["msj"]);
    }

    private function sendSms($data, $params) {
        $payMethod = "EasyPhoneService";
        $sendSms = new \SmartWayRepository(new $payMethod);
        $reponse = $sendSms->send($data, $params);
        return $reponse;
    }

}
