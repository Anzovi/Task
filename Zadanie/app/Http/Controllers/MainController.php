<?php

namespace App\Http\Controllers;

use App\StudModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home(){
        return view('home');
    }

    public function about(){

        $users = DB::table('stud_models')->get();

        return view('about',['cont' => $users->all()]);
    }

    public function Stud_check(Request $request){
        $valid = $request->validate([
            'Stud' => 'required'
        ]);

        $about = new StudModel();
        $about->name = $request->input('Stud');

        $about->save();

        return redirect()->route('StudCreate');
    }

    public function Stud_delete(Request $request){

        $index = $request->input('StudDelete');
        DB::delete('delete from stud_models where id = ?',[$index]);
        DB::delete('delete from conn where StudId = ?',[$index]);
        return redirect()->route('StudCreate');
    }

    public function Subject(){
        //$cont = new StudModel();
        $subjec = DB::table('subjects')->get();

        return view('subjects',['conte' => $subjec]);
    }

    public function Subject_check(Request $request){
        $valid = $request->validate([
            'Subject' => 'required'
        ]);


        $index = $request->input('Subject');
        DB::table('subjects')->insert(
            ['subject' => $index]
        );


        return redirect()->route('SubjectCreate');
    }

    public function Subject_delete(Request $req){

        $ind = $req->input('SubjectDelete');
        DB::delete('delete from subjects where id = ?',[$ind]);
        DB::delete('delete from conn where SubjectId = ?',[$ind]);
        return redirect()->route('SubjectCreate');
    }

    public function connectionID(){

        return view('connectionID');
    }

    public function connectionID_check(Request $request){
        $valid = $request->validate([
            'StudID' => 'required|numeric|min:1',
            'Subject' => 'required'
        ]);


        $indexStud = $request->input('StudID');
        $indexSubj = $request->input('Subject');

        $indSub = DB::table('subjects')->where('subject',$indexSubj)->value('id');

        DB::table('conn')->insert(
            ['StudId' => $indexStud,
                'SubjectId' => $indSub
            ]);


        return redirect()->route('connectionID');
    }

    public function connectionID_delete(Request $req){

        $ind = $req->input('SubjectDelete');
        DB::delete('delete from subjects where id = ?',[$ind]);
        return redirect()->route('SubjectCreate');
    }

    public function searchBySubject(Request $request){
        //$searchBy = DB::table('conn')->get();
        return view('searchBySubj',['content' => []]);
    }

    public function searchBySubject_check(Request $request){
        $valid = $request->validate([
            'Subj' => 'required|min:1',
        ]);


        $indexSubj = $request->input('Subj');

        $idSubj = DB::table('subjects')->where('subject',$indexSubj)->value('id');

        $idStud = DB::table('conn')->where('SubjectId',$idSubj)->get();
        //dd($idStud);
        $StudT = $idStud->toArray();
        //dd($idStud->toArray()[0]->id);
        $Stud = array_column($StudT,'StudId');
        $StudT = DB::table('stud_models')->whereIn('id',$Stud)->get();

        //dd($Stud);
        return view('searchBySubj',['content' => $StudT]);

    }

}
