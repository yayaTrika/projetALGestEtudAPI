<?php

namespace App\Http\Controllers;

use App\Model\Etudiant;
use Illuminate\Http\Request;
use App\Http\Requests\EtudiantRequest;
use App\Http\Resources\Etudiant\EtudiantCollection;
use App\Http\Resources\Etudiant\EtudiantResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api')->except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EtudiantCollection::collection(Etudiant::paginate(20)); 
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EtudiantRequest $request)
    {
        //return $request;
        $etudiant = new Etudiant();
        $etudiant->matricule = $request->matricule;
        $etudiant->nom = $request->nom;
        $etudiant->prenom = $request->prenom;
        $etudiant->nameMovie = $request->nameMovie;
        $etudiant->tel = $request->tel;
        $etudiant->email = $request->email;
        $etudiant->idDateNaiss = $request->idDateNaiss;

        $etudiant->save();

        return response([
            'data' => new EtudiantResource($etudiant)
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function show(Etudiant $etudiant)
    {
        return new EtudiantResource($etudiant);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        try{
            DB::beginTransaction();

            $saveImage = false; 

            // $image = $request->nameMovie;
            // $requestImage_path = public_path(). '/images/etudiants/' .$image;
            
            // if(file_exists($requestImage_path) == false){
               
            //     if($article->nameMovie !== ""){
            //         $image_path = public_path().'/images/etudiant/'. $article->nameMovie;
            //         if(file_exists($image_path) === true){
            //             unlink($image_path);
            //         }
            //     }

            //     $imageName = 'E'.'-'.time().'.'.explode('/',explode(':',
            //             substr($image,0, strpos($image, ';')))[1])[1];
                   
            //     $request['nameMovie'] = $imageName;
            //     $saveImage= true;  
            // }
            // else{

            //     if($request->nameMovie === $article->nameMovie){
            //         $saveImage = false;
            //     }
            // }

            $etudiant->update($request->all());

            // if($saveImage === true){
            //     $image_path = public_path().'/images/etudiant/'. $request->nameMovie;
            //     Image::make($image)->save($image_path);
            // }

            DB::commit();

            return response([
                'data' => new EtudiantResource($etudiant)
            ],Response::HTTP_CREATED);
        }
        catch(Exception $e){
            report($e);
            DB::roolBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Etudiant $etudiant)
    {
        // On ne supprime jamais dans une base de donnée
    }
}
