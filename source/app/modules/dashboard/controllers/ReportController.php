<?php

namespace App\Modules\Dashboard\Controllers;

use Auth,
    DB,
    Input,
    Operator,
    Shipping,
    Sms,
    Statesms,
    Session,
    Typeentry,
    URL,
    User,
    View;

class ReportController extends \BaseController {

    public function showDetail() {
        $operator = Operator::where('flagAct', '=', 1)->lists('name', 'id');
        $operator = array_add($operator, 0, 'Todos');
        ksort($operator);
        $typeentry = Typeentry::lists('name', 'id');
        $typeentry = array_add($typeentry, 0, 'Todos');
        ksort($typeentry);
        $statesms = Statesms::lists('name', 'id');
        $statesms = array_add($statesms, 0, 'Todos');
        ksort($statesms);
        $user = User::where('id', Auth::user()->id)->lists('name', 'id');
        $user = array_add($user, 0, 'Todos');
        ksort($user);
        return View::make('dashboard::report.detail', compact('operator', 'typeentry', 'statesms', 'user'));
    }

    public function showConsolidated() {
        $operator = Operator::where('flagAct', '=', 1)->lists('name', 'id');
        $operator = array_add($operator, 0, 'Todos');
        ksort($operator);
        $typeentry = Typeentry::lists('name', 'id');
        $typeentry = array_add($typeentry, 0, 'Todos');
        ksort($typeentry);
        $user = User::where('id', Auth::user()->id)->lists('name', 'id');
        $user = array_add($user, 0, 'Todos');
        ksort($user);
        return View::make('dashboard::report.consolidated', compact('operator', 'user', 'typeentry'));
    }

    public function listDetail() {
        $iDisplayStart = Input::get('iDisplayStart', 0);
        $iDisplayLength = Input::get('iDisplayLength', 10);
        $sEcho = Input::get('sEcho', 1);
        $sSearch = Input::get('sSearch', null);
        $params = array(
            'from' => Input::get('sSearch_0', null),
            'to' => Input::get('sSearch_6', null),
            'phone' => Input::get('sSearch_1', null),
            'statesms' => Input::get('sSearch_2', null),
            'operator' => Input::get('sSearch_3', null),
            'typeentry' => Input::get('sSearch_4', null),
            'iduser' => (Input::has('sSearch_5')) ? Input::get('sSearch_5', null) : Auth::user()->id,
        );
        $data = Shipping::leftjoin('typeEntry', 'typeEntry.id', '=', 'shippings.typeEntry')
                ->select(
                        DB::raw('(SELECT count(s.idstatesms) FROM sms s WHERE s.idstatesms = 4 and idshipping = shippings.id) as despachado'),
                        DB::raw('(SELECT count(idstatesms) FROM sms WHERE idstatesms = 5 and idshipping = shippings.id) as confirmado'), 
                        DB::raw('(SELECT count(idstatesms) FROM sms WHERE idstatesms = 3 and idshipping = shippings.id) as fallido'), 
                        DB::raw('(SELECT count(idstatesms) FROM sms WHERE idstatesms = 2 and idshipping = shippings.id) as rechazado'), 
                        DB::raw('(SELECT count(idstatesms) FROM sms WHERE idstatesms = 1 and idshipping = shippings.id) as pendiente'), 
                        DB::raw('(SELECT count(idstatesms) FROM sms where idshipping = shippings.id) as total'), 'shippings.created_at', 'shippings.id'
                )
                ->where(function($query) use ($params) {
            if (!empty($params['typeentry']))
                $query->where('typeEntry.id', '=', $params['typeentry']);
            if (!empty($params['iduser']))
                $query->where('shippings.idUser', '=', $params['iduser']);
            if (!empty($params['from']) && !empty($params['to'])) {
                $query->where(function($q)use($params) {
                    $q->where("shippings.created_at", '>=', date('Y-m-d H:i:s', strtotime($params['from'])));
                    $q->where("shippings.created_at", '<=', date('Y-m-d H:i:s', strtotime($params['to'] . '23:00:00')));
                });
            }
        })
        ;
        $old = $data->orderBy('shippings.created_at', 'desc');
        $pag = $data->paginate($iDisplayLength);
        $data = $old->skip($iDisplayStart)->take($iDisplayLength)->get();
        $rptas = array();
        foreach ($data as $value) {
            $rptas[] = array(
                0 => isset($value->created_at) ? '<a href="' . URL::route('reportconsolidateddetail', array('id' => $value->id)) . '"><b>' . $value->created_at->toDateTimeString() . '</b></a>' :
                        '<a href="' . URL::route('reportconsolidateddetail', array('id' => $value->id)) . '"><b>0000-00-00 00:00:00</b></a>',
                1 => $value->despachado,
                2 => $value->fallido,
                3 => $value->pendiente,
                4 => $value->total,
                5 => $value->id,
                6 => $value->id,
            );
        }
        $rpta = array(
            'aaData' => $rptas,
            'sEcho' => $sEcho,
            'iTotalRecords' => $pag->getTotal(),
            'iTotalDisplayRecords' => $pag->getTotal(),
        );

        return $rpta;
    }

    public function listConsolidated() {
        $iDisplayStart = Input::get('iDisplayStart', 0);
        $iDisplayLength = Input::get('iDisplayLength', 10);
        $sEcho = Input::get('sEcho', 1);
        $sSearch = Input::get('sSearch', null);
        $params = array(
            'from' => Input::get('sSearch_0', null),
            'to' => Input::get('sSearch_1', null),
            'typeentry' => Input::get('sSearch_3', null),
            'iduser' => (Input::has('sSearch_4')) ? Input::get('sSearch_4', null) : Auth::user()->id,
        );
        $data = Shipping::leftjoin('typeEntry', 'typeEntry.id', '=', 'shippings.typeEntry')
                ->select(
                        'shippings.created_at', 'shippings.id', 'shippings.dispatched as despachado', 'shippings.pending as pendiente', 'shippings.rejected as rechazado', 'shippings.total as total'
                )
                ->where(function($query) use ($params) {
            if (!empty($params['typeentry']))
                $query->where('typeEntry.id', '=', $params['typeentry']);
            if (!empty($params['iduser']))
                $query->where('shippings.idUser', '=', $params['iduser']);
            if (!empty($params['from']) && !empty($params['to'])) {
                $query->where(function($q)use($params) {
                    $q->where("shippings.created_at", '>=', date('Y-m-d H:i:s', strtotime($params['from'])));
                    $q->where("shippings.created_at", '<=', date('Y-m-d H:i:s', strtotime($params['to'] . '23:00:00')));
                });
            }
        });
        $old = $data->orderBy('shippings.created_at', 'desc');
        $pag = $data->paginate($iDisplayLength);
        $data = $old->skip($iDisplayStart)->take($iDisplayLength)->get();
        $rptas = array();
        foreach ($data as $value) {
            $rptas[] = array(
                0 => isset($value->created_at) ? '<a href="' . URL::route('reportconsolidateddetail', array('id' => $value->id)) . '"><b>' . $value->created_at->toDateTimeString() . '</b></a>' :
                        '<a href="' . URL::route('reportconsolidateddetail', array('id' => $value->id)) . '"><b>0000-00-00 00:00:00</b></a>',
                1 => $value->despachado,
                2 => $value->rechazado,
                3 => $value->pendiente,
                4 => $value->total,
            );
        }
        $rpta = array(
            'aaData' => $rptas,
            'sEcho' => $sEcho,
            'iTotalRecords' => $pag->getTotal(),
            'iTotalDisplayRecords' => $pag->getTotal(),
        );

        return $rpta;
    }

    public function showConsolidatedDetail($id) {
        Session::put('idconsolidateddetail', $id);
        $urlexcel = URL::route('reportconsolidateddetailexcel', array('id' => $id));
        return View::make('dashboard::report.consolidateddetail', array('urlexcel' => $urlexcel));
    }

    public function showConsolidatedDetailExcel($id) {
        $data = Sms::leftjoin('operators', 'operators.id', '=', 'sms.idoperator')
                        ->leftjoin('stateSms', 'stateSms.id', '=', 'sms.idstatesms')
                        ->leftjoin('shippings', 'shippings.id', '=', 'sms.idshipping')
                        ->leftjoin('typeEntry', 'typeEntry.id', '=', 'shippings.typeEntry')
                        ->leftjoin('typesends', 'typesends.id', '=', 'sms.idtypesend')
                        ->leftjoin('users', 'users.id', '=', 'sms.idUser')
                        ->select('sms.id', 'sms.dateCreate', 'sms.phone','sms.comment'
                                , 'stateSms.name as statename', 'operators.name as oname', 'typeEntry.name as entryname', 'sms.message', 'typesends.name as tsname', 'users.name as uname'
                        )
                        ->where('idshipping', $id)
                        ->orderBy('sms.dateCreate', 'desc')->get();
        return View::make('dashboard::report.consolidateddetailexcel', compact('data'));
    }

    public function listConsolidatedDetail() {
        $iDisplayStart = Input::get('iDisplayStart', 0);
        $iDisplayLength = Input::get('iDisplayLength', 10);
        $sEcho = Input::get('sEcho', 1);
        $sSearch = Input::get('sSearch', null);
//                var_dump($params);exit;
        $data = Sms::leftjoin('operators', 'operators.id', '=', 'sms.idoperator')
                ->leftjoin('stateSms', 'stateSms.id', '=', 'sms.idstatesms')
                ->leftjoin('shippings', 'shippings.id', '=', 'sms.idshipping')
                ->leftjoin('typeEntry', 'typeEntry.id', '=', 'shippings.typeEntry')
                ->leftjoin('typesends', 'typesends.id', '=', 'sms.idtypesend')
                ->leftjoin('users', 'users.id', '=', 'sms.idUser')
                ->select('sms.id', 'sms.dateCreate', 'sms.phone','sms.comment'
                        , 'stateSms.name as statename', 'operators.name as oname', 'typeEntry.name as entryname', 'sms.message', 'typesends.name as tsname', 'users.name as uname'
                )
//                        ->where('sms.flagAct', '=', 1)
//                        ->where('sms.idUser', '=', Auth::user()->id)
                ->where('idshipping', Session::get('idconsolidateddetail', 0));
        $old = $data->orderBy('sms.dateCreate', 'desc');
        $pag = $data->paginate($iDisplayLength);
        $data = $old->skip($iDisplayStart)->take($iDisplayLength)->get();
        $rptas = array();
//                var_dump($data);exit;
        foreach ($data as $value) {
            $rptas[] = array(
                0 => $value->phone,
                1 => $value->oname,
                2 => $value->statename,
                3 => $value->dateCreate/* ->toDateTimeString() */,
                4 => $value->tsname,
                5 => $value->entryname,
                6 => $value->uname,
                7 => $value->message,
                8 => $value->comment,
            );
        }
//                $totalTable = Sms::where('idUser', Auth::user()->id)->/*where('flagAct', 1)->*/count();
        $rpta = array(
            'aaData' => $rptas,
            'sEcho' => $sEcho,
            'iTotalRecords' => $pag->getTotal(),
            'iTotalDisplayRecords' => $pag->getTotal(),
        );
        return $rpta;
    }

}
