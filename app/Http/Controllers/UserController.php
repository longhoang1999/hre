<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\WordType;
use App\Models\Hre;
use App\Models\VN;
use Yajra\Datatables\Datatables;
use DB;



class UserController extends Controller
{
    // treo
    public function create()
    {
        return view("create");
    }
    public function postCreate(Request $req)
    {
        $this->validate($req,[
            'username'=>'required',
            'password'=>'required|min:8|max:32',
        ],[
            'username.required' => 'Bạn phải nhập Username',
            'password.required' => 'Bạn phải nhập Password',
            'password.min' => 'Password nhập quá ngắn',
            'password.max' => 'Password nhập quá dài',
        ]);
        $check = User::where("username",$req->username)->first();
        if(!$check)
        {
            $user = new User();
            $user->username = $req->username;
            $user->password = Hash::make($req->password);
            $user->save();
            return redirect()->route("user.login");
        }
        else {
            return back()->with("error","Tài khoản đã tồn tại");
        } 
    }

    public function login(Request $req) {
        if(Auth::check())
            return redirect()->route("user.demo");
        else
            return view("login");

    }
    public function postLogin(Request $req)
    {
        $this->validate($req,[
            'username'=>'required',
            'password'=>'required|min:8|max:32',
        ],[
            'username.required' => 'Bạn phải nhập Username',
            'password.required' => 'Bạn phải nhập Password',
            'password.min' => 'Password nhập quá ngắn',
            'password.max' => 'Password nhập quá dài',
        ]);
        Auth::attempt(['username'=>$req->username,'password'=>$req->password]);
        if(Auth::check())
            return redirect()->route("user.demo");
        else
            return back()->with("error","Nhập sai tài khoản hoặc mật khẩu");
    }

    public function demo(Request $req) {
        return view("demo/demo");
    }
    public function search(Request $req)
    {
        if($req->status == "true")
        {
            $findHre = Hre::where("plaintext","like","%".$req->key."%")->inRandomOrder()->limit(10)->get();
            $data = array();
            foreach($findHre as $value)
            {
                $findVn = VN::where("id_hre",$value->id)->first();
                $tmp = (object) array('hre' => $value->plaintext,'vn' => $findVn->plaintext);
                array_push($data,$tmp);
            }
            return [$status = "true" , $data];
        }
        else
        {
            $findVN = VN::where("plaintext","like","%".$req->key."%")->inRandomOrder()->limit(10)->get();
            $data = array();
            foreach($findVN as $value)
            {
                $findHre = Hre::where("id",$value->id_hre)->first();
                $tmp = (object) array('hre' => $findHre->plaintext,'vn' => $value->plaintext);
                array_push($data,$tmp);
            }
            return [$status = "false" , $data];
        }
    }
    public function makeWord() {
        $wordtype = WordType::get();
        return view("demo/makeword",['wordtype' => $wordtype]);
    }
    public function createWord(Request $req)
    {
        $this->validate($req,[
            'hre'=>'required',
            'vnMain'=>'required',
        ]);
        $checkWord = DB::select('select * from `hre` where binary  plaintext = "'.$req->hre.'"');
        if(count($checkWord) == 0)
        {
            // hre
            $hre = new Hre();
            $hre->plaintext = $req->hre;
            $hre->id_user = Auth::user()->id;
            $hre->save();
            // vn
            $vn = new VN();
            $vn->plaintext = $req->vnMain;
            $vn->id_wordtype = $req->typeMain;
            $vn->id_hre = $hre->id;
            $vn->save();

            // vn2
            if($req->vn2 != "")
            {
                $vn2 = new VN();
                $vn2->plaintext = $req->vn2;
                $vn2->id_wordtype = $req->type2;
                $vn2->id_hre = $hre->id;
                $vn2->save();
            }
            // vn3
            if($req->vn3 != "")
            {
                $vn3 = new VN();
                $vn3->plaintext = $req->vn3;
                $vn3->id_wordtype = $req->type3;
                $vn3->id_hre = $hre->id;
                $vn3->save();
            }
            return $mes = "Bạn đã thêm từ thành công!";
        }
        else
            return $mes = "Từ đã tồn tại trong hệ thống!";
    }

    public function loadWord() {
        $hreForAcc = Hre::where("id_user",Auth::user()->id)->get();
        return DataTables::of($hreForAcc)
            ->addColumn(
                'stt',
                function ($hreForAcc) {
                    $stt="";
                    return $stt;
                }
            )
            ->addColumn(
                'means',
                function ($hreForAcc) {
                    $listVN = VN::where("id_hre",$hreForAcc->id)->get();
                    $means = "";
                    foreach ($listVN as $value) {
                        $wordtype = WordType::where("id",$value->id_wordtype)->first()->plaintext;
                        $means .= '<p class="m-0"><i class="fas fa-check text-danger"></i> '.$value->plaintext.' - <span class="font-italic">'.$wordtype.'</span></p>';
                    }
                    return $means;
                }
            )
            ->addColumn(
                'actions',
                function ($hreForAcc) {
                    $actions='<button type="button" data-text="'.$hreForAcc->plaintext.'" data-id="'.$hreForAcc->id.'" class="btn btn-danger btn-block btn-sm" data-toggle="modal" data-target="#modalDelete">Xóa</button>';
                    return $actions;
                }
            )
            ->rawColumns(['stt','means','actions'])
            ->make(true);
    }
    public function logout() {
        Auth::logout();
        return redirect()->route("user.demo");
    }
    public function deleteWord(Request $req) {
        $findHre = Hre::where("id",$req->id)->first();
        $findVi = VN::where("id_hre",$req->id)->get();
        foreach($findVi as $value)
            $value->delete();
        $findHre->delete();
        return $mes = "Xóa thành công!";
    }
}
