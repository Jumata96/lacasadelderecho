<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collection;
use DB;
use Validator;
use Auth;
use Image;

class VisionController extends Controller
{
     public function index()
    {
    	$valida = 0;

        //-- Validación para mostrar mensajes al realizar un CRUD
        $validacion = DB::table('validacion')
                        ->select('valor')
                        ->where('idusuario',Auth::user()->id)->get();

        foreach ($validacion as $val) {
            $valida = $val->valor;
        }
        if ($valida > 0) {
            DB::table('validacion')
            ->where('idusuario',strval(Auth::user()->id))
            ->update(['valor' => 0]);
        }

        $vision = DB::table('vision')->get();
        $dvision = DB::table('dvision')->get();
        //dd($carrusel);

        return view('forms.nosotros.mntVision', [
            'vision'	=> $vision,
            'dvision'	=> $dvision,
            'valida'	=> $valida
		]);
    }

    public function update(Request $request)
    {
    	//dd($request);
         $rules = array(
            'titulo'        => 'required'
        );

        $validator = Validator::make ( $request->all(), $rules );

        if ($validator->fails()){
            $var = $validator->getMessageBag()->toarray();
            array_push($var, 'error');
            //return response::json(array('errors'=> $validator->getMessageBag()->toarray()));
            return response()->json($var);
        }
        else {
            DB::table('vision')
            ->where('id',$request->id)
            ->update([
                'titulo'        => $request->titulo,
                'descripcion'	=> $request->descripcion
            ]);

            $vision = DB::table('vision')->where('id',$request->id)->get();
            $collection = Collection::make($vision);

            return response()->json($collection->toJson());
        }
    }

    public function imgUpdate(Request $request)
    {
        //dd($request);
        $idusu = Auth::user()->id;
        $validacion = DB::table('validacion')->where('idusuario',$idusu)->get();

        $file = $request->file('url_imagen');

        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
            $path = public_path('images/'.$fileName);
            //dd( $fileName);
            Image::make($file)->save($path);


            DB::table('dvision')
            ->where('id',$request->u_id)
            ->update([
                'tipo_img_icon'     => 1,
                'icono'             => $request->u_icono,
                'titulo'            => $request->u_titulo,
                'descripcion'       => $request->u_descripcion,
                'fila'              => $request->u_fila,
                'url_imagen'        => 'images/'.$fileName,
                'imagen'            => $fileName
            ]);
        }else{

            DB::table('dvision')
            ->where('id',$request->u_id)
            ->update([
                'tipo_img_icon'     => 1,
                'imagen'            => $request->u_imagen,
                'icono'             => $request->u_icono,
                'titulo'            => $request->u_titulo,
                'descripcion'       => $request->u_descripcion,
                'fila'              => $request->u_fila
            ]);
        }


        if (count($validacion) > 0) {
            DB::table('validacion')
            ->where('idusuario',strval($idusu))
            ->update(['valor' => 2]);
        }

        $producto = DB::table('producto')->where('codigo',$request->codigo)->get();


        $data['success'] = $producto;
        //$data['path'] = 'images/carrusel/'.$fileName . '?' . uniqid();

        return $data;
    }

    public function store(Request $request)
    {
    	$idusu = Auth::user()->id;
        $validacion = DB::table('validacion')->where('idusuario',$idusu)->get();

    	//dd($request);
         $rules = array(
            'titulo'        => 'required',
            'descripcion'	=> 'required'
        );

        $validator = Validator::make ( $request->all(), $rules );

        if ($validator->fails()){
            $var = $validator->getMessageBag()->toarray();
            array_push($var, 'error');
            //return response::json(array('errors'=> $validator->getMessageBag()->toarray()));
            return response()->json($var);
        }
        else {
            DB::table('dvision')
            ->insert([
            	'idvision'			=> 'VSN',
            	'estado'            => 1,
                'tipo_img_icon'     => 0,
                'icono'             => $request->icono,
                'imagen'            => $request->imagen,
                'titulo'            => $request->titulo,
                'fila'              => $request->fila,
                'idtipo'            => $request->idtipo,
                'descripcion'       => $request->descripcion,
                'fecha_creacion'    => date('Y-m-d h:m:s')
            ]);

            if (count($validacion) === 0) {
	            DB::table('validacion')
	            ->insert([
	                'idusuario' => $idusu,
	                'valor'     => 1
	            ]);
	        }else{
	            DB::table('validacion')
	            ->where('idusuario',strval($idusu))
	            ->update(['valor' => 1]);

	        }

            $dvision = DB::table('dvision')->where('imagen',$request->imagen)->get();
            $collection = Collection::make($dvision);

            return response()->json($collection->toJson());
        }
    }

    public function imgStore(Request $request)
    {
        //dd($request);
        $idusu = Auth::user()->id;
        $validacion = DB::table('validacion')->where('idusuario',$idusu)->get();

        $file = $request->file('imagenURL');
        //dd($file);
        $extension = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();
        $path = public_path('images/'.$fileName);
        //dd( $fileName);
        Image::make($file)->save($path);


        DB::table('dvision')
        ->insert([
            'idvision'         => 'GEN',
            'estado'            => 1,
            'tipo_img_icon'     => 1,
            'icono'             => $request->icono,
            'titulo'            => $request->titulo,
            'fila'              => $request->fila,
            'descripcion'       => $request->descripcion,
            'url_imagen'        => 'images/'.$fileName,
            'imagen'            => $fileName
        ]);

        if (count($validacion) === 0) {
            DB::table('validacion')
            ->insert([
                'idusuario' => $idusu,
                'valor'     => 1
            ]);
        }else{
            DB::table('validacion')
            ->where('idusuario',strval($idusu))
            ->update(['valor' => 1]);

        }

        $producto = DB::table('producto')->where('codigo',$request->codigo)->get();


        $data['success'] = $producto;
        $data['path'] = 'images/'.$fileName . '?' . uniqid();

        return $data;
    }


    public function dUpdate(Request $request)
    {
    	$idusu = Auth::user()->id;
        $validacion = DB::table('validacion')->where('idusuario',$idusu)->get();

    	//dd($request);
         $rules = array(
            'u_imagen'        => 'required',
            'u_titulo'        => 'required',
            'u_descripcion'	=> 'required'
        );

        $validator = Validator::make ( $request->all(), $rules );

        if ($validator->fails()){
            $var = $validator->getMessageBag()->toarray();
            array_push($var, 'error');
            //return response::json(array('errors'=> $validator->getMessageBag()->toarray()));
            return response()->json($var);
        }
        else {
            DB::table('dvision')
            ->where('id',$request->u_id)
            ->update([
                'tipo_img_icon'     => 0,
            	'imagen'           => $request->u_imagen,
                'icono'             => $request->u_icono,
                'titulo'        	=> $request->u_titulo,
                'descripcion'		=> $request->u_descripcion,
                'fila'	        	=> $request->u_fila,
                'idtipo'            => $request->u_idtipo
            ]);

            if (count($validacion) > 0) {
	            DB::table('validacion')
	            ->where('idusuario',strval($idusu))
	            ->update(['valor' => 2]);
	        }

            $dvision = DB::table('dvision')->where('imagen',$request->imagen)->get();
            $collection = Collection::make($dvision);

            return response()->json($collection->toJson());
        }
    }

    public function disabled(Request $request)
    {
        DB::table('dvision')
            ->where('id',$request->id)
            ->update([
                'estado'    => 0
            ]);

        $dvision = DB::table('dvision')->get();

        $data['success'] = $dvision;
	    //$data['path'] = 'images/carrusel/'.$fileName . '?' . uniqid();

	    return $data;
    }

    public function habilitar(Request $request)
    {
        DB::table('dvision')
            ->where('id',$request->id)
            ->update([
                'estado'    => 1
            ]);

        $dvision = DB::table('dvision')->get();

        $data['success'] = $dvision;
	    //$data['path'] = 'images/carrusel/'.$fileName . '?' . uniqid();

	    return $data;
    }

    public function destroy(Request $request)
    {
    	$idusu = Auth::user()->id;
        $validacion = DB::table('validacion')->where('idusuario',$idusu)->get();

    	//dd($request->id);
        DB::table('dvision')
            ->where('id',$request->id)->delete();

        if (count($validacion) === 0) {
            DB::table('validacion')
            ->insert([
                'idusuario' => $idusu,
                'valor'     => 3
            ]);
        }else{
            DB::table('validacion')
            ->where('idusuario',strval($idusu))
            ->update(['valor' => 3]);

        }

        return response()->json();
    }

}
