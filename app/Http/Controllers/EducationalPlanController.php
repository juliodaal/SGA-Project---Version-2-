<?php

namespace App\Http\Controllers;

use App\EducationalPlan;
use App\Career;
use App\Discipline;
use Illuminate\Http\Request;
use App\Http\Controllers\FileAdminDataController;
use Exception;


class EducationalPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.EducationalPlan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!is_null($request->document)){
            return FileAdminDataController::validateFile($request,'document','xlsx','excel_files','FileAdminDataController@educationalPlan','/admin');
        } else {
            try {
                $this->validate($request, [
                    'acronymCareer'=>'required',
                    'acronymDiscipline'=>'required',
                    'semester'=>'required',
                ]);
                $msg = '"'. $request->acronymCareer . '" - "' . $request->acronymDiscipline . '"';
                $career = Career::where('acronym_career', '=', $request->acronymCareer)->first();
                $discipline = Discipline::where('acronym_discipline', '=', $request->acronymDiscipline)->first();
                
                if(!is_null($career) && !is_null($discipline)){
                    EducationalPlan::create([
                        'acronym_career_from_careers' => strtoupper($request->acronymCareer),
                        'acronym_discipline_from_disciplines' => strtoupper($request->acronymDiscipline),
                        'semester' => strtoupper($request->semester)
                    ]);
                } else {
                    throw new Exception('Este curso o disciplina no existe');
                }
            } catch (\Exception $e){
                if(!isset($msg)){ $msg = null; }
                return FileAdminDataController::reportError('/home',$e,$msg);
            }
            return redirect('/home')->with('successfully', 'Curso agregado con éxito'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EducationalPlan  $educationalPlans
     * @return \Illuminate\Http\Response
     */
    public function show(EducationalPlan $educationalPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EducationalPlan  $educationalPlans
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $plan = EducationalPlan::findOrFail($id);
        } catch (\Exception $e) {
            return FileAdminDataController::reportError('/admin/plan',$e);
        }
        return view('admin.EducationalPlan.edit.editEducationalPlan', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EducationalPlan  $educationalPlans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EducationalPlan $educationalPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EducationalPlan  $educationalPlans
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if(EducationalPlan::findOrFail($id)->delete()){
                return redirect('/admin/plan')->with('successfully', 'El Plan de Educación fue elimina con éxito'); 
            } else {
                throw new Exception('Error en la conexión con la Base de Datos');
            }
        } catch (\Exception $e) {
            return FileAdminDataController::reportError('/admin/plan',$e);
        }
    }
    public function findPlan(Request $request)
    {
        $plans = null;
        try {    
            if($request->acronymCareer){
                $plans = EducationalPlan::where('acronym_career_from_careers', '=', $request->acronymCareer)->get();
                if($request->acronymCareer == '*'){
                    $plans = EducationalPlan::all();
                }
            } else {
                throw new Exception('Error al intentar encontrar el plan de Educación');
            }
        } catch (\Exception $e) {
            return FileAdminDataController::reportError('/admin/plan',$e);
        }
        return view('admin.EducationalPlan.educationaPlanList', compact('plans'));
    }
}
