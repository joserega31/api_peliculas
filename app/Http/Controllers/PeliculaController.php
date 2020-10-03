<?php

namespace App\Http\Controllers;

use App\Pelicula;
use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeliculaController extends Controller
{

    /**
    * @OA\Get(
    *     path="/api/peliculas",
    *     summary="Mostar toda las peliculas",
    * tags={"Pelicula"},
    *      @OA\Parameter(
    *      name="nombre",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar toda las Peliculas .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="Pelicula")
    * )    
    */
    // sean76
    public function index(Request $request)
    {
        $nombre = $request->input('nombre');
        $pelicula = DB::table('peliculas as p')
        ->select('p.id','p.nombre','p.descripcion','p.year',DB::raw("c.nombre as categoria"))
        ->join('categorias as c','p.categoria_id','=','c.id')
        ->where('p.nombre','LIKE',$nombre.'%')            
        ->get();
        return $this->JsonResponseSuccess($pelicula); 
    }

  

  /**
    * @OA\Post(
    *     path="/api/peliculas",
    *     summary="Registrar",
    * tags={"Pelicula"},
    *      @OA\Parameter(
    *      name="nombre",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="descripcion",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),  @OA\Parameter(
    *      name="year",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *  @OA\Parameter(
    *      name="categoria_id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),    
    *   @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="nombre",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="descripcion",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="year",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria_id",
 *                     type="integer"
 *                 ), 
 * 
 *                 example={"nombre": "a3fb6", "descripcion": "Jessica Smith",
 *                  "year":2020,"categoria_id":1 
 *                  }
 *             )
 *         )
 *     ),
 * 
 * 
    *     @OA\Response(
    *         response=200,
    *         description="Registrar Pelicula .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="Pelicula")
    * )    
    */
    public function store(Request $request)
    {
        try {
          $pelicula = new Pelicula();

          $nombre = $request->input('nombre');
          $descripcion = $request->input('descripcion');
          $categoria_id = $request->input('categoria_id');
          $year = $request->input('year');

          if(!Categoria::find($categoria_id)){
            $response['value'] = $nombre;
            $response['info'] = "Por favor introduzca un categoria";
            return $this->JsonResponseBadRequest($response);
          }

          if(!$nombre){
            $response['value'] = $nombre;
            $response['info'] = "Por favor introduzca un nombre";
            return $this->JsonResponseBadRequest($response);
          }
          if(!$descripcion){
            $response['value'] = $descripcion;
            $response['info'] = "Por favor introduzca una descripcion";
            return $this->JsonResponseBadRequest($response);
          }
     
          if(!$year){
            $response['value'] = $year;
            $response['info'] = "Por favor introduzca un year";
            return $this->JsonResponseBadRequest($response);
          }

          $pelicula->nombre = $nombre;
          $pelicula->descripcion = $descripcion;
          $pelicula->categoria_id = $categoria_id;
          $pelicula->year = $year;
          $pelicula->save();
          
        } catch (Exception $ex) {
          return $this->JsonResponseError($ex, 'exception');
        }
        return $this->JsonResponseSuccess($pelicula,200,'Pelicula Registrada'); 
    }




   /**
    * @OA\Get(
    *     path="/api/peliculas/{id}",
    *     summary="Show",
    * tags={"Pelicula"},
    *        @OA\Parameter(
    *      name="id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    * 
    *     @OA\Response(
    *         response=200,
    *         description="Buscar una pelicula .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="Pelicula")
    * )    
    */
    public function show($id)
    {
      $pelicula = DB::table('peliculas as p')
      ->select('p.id','p.nombre','p.descripcion','p.year',DB::raw("c.nombre as categoria"))
      ->join('categorias as c','p.categoria_id','=','c.id')
      ->where('p.id','=',$id)            
      ->get();
      return $this->JsonResponseSuccess($pelicula); 
    }  

  /**
    * @OA\Put(
    *     path="/api/peliculas/{id}",
    *     summary="Editar una pelicula",
    * tags={"Pelicula"},
    *       @OA\Parameter(
    *      name="id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *
    *       @OA\Parameter(
    *      name="nombre",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="descripcion",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),  @OA\Parameter(
    *      name="year",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *  @OA\Parameter(
    *      name="categoria_id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),    
    *   @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="nombre",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="descripcion",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="year",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria_id",
 *                     type="integer"
 *                 ), 
 * 
 *                 example={"id":0,"nombre": "a3fb6", "descripcion": "Jessica Smith",
 *                  "year":2020,"categoria_id":1 
 *                  }
 *             )
 *         )
 *     ),
 * 
 * 
    *     @OA\Response(
    *         response=200,
    *         description="Editar una pelicula .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="Pelicula")
    * )    
    */
    public function update(Request $request, $id)
    {
      try {

        $nombre = $request->input('nombre'); 
        $year = $request->input('year'); 
        $categoria_id = $request->input('categoria_id');     

        $pelicula = Pelicula::find($id);
        if(!$pelicula){
            $response['value'] = '';
            $response['info'] = "pelicula no registrada";
            return $this->JsonResponseBadRequest($response);
        }

        if(!Categoria::find($categoria_id)){
          $response['value'] = '';
          $response['info'] = "categoria no registrada";
          return $this->JsonResponseBadRequest($response);
        }
        
        $modificar = false;

        if (trim($nombre) && !is_null($nombre) && $pelicula->nombre != $nombre) {  
            $pelicula->nombre = $nombre;    
            $modificar = true;
        }
        if (trim($year) && !is_null($year) && $pelicula->year != $year) {  
            $pelicula->year = $year;    
            $modificar = true;
        }
        if (trim($categoria_id) && !is_null($categoria_id) && $pelicula->categoria_id != $categoria_id) {  
            $pelicula->categoria_id = $categoria_id;    
            $modificar = true;
        }

        
        if ($modificar) {
            $pelicula->save();
        } else {
            return $this->JsonResponseSuccess(NULL, 200, "¡Registro sin alterar!");
        }             

    } catch (Exception $ex) {
        return $this->JsonResponseError($ex, 'exception');
    }
    return $this->JsonResponseSuccess($pelicula,200,'Pelicula Editado');       
  }

  /**
    * @OA\Delete(
    *     path="/api/peliculas/{id}",
    *     summary="Eliminar una pelicula",
    * tags={"Pelicula"},
    *      @OA\Parameter(
    *      name="id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *     @OA\Response(
    *         response=200,
    *         description="Eliminar una pelicula .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="Pelicula")
    * )    
    */
    public function destroy($id)
    {
        try {
        $pelicula = Pelicula::find($id);
        $pelicula->delete();
      } catch (Exception $ex) {
        return $this->JsonResponseError($ex, 'exception');
      }
      return $this->JsonResponseSuccess($pelicula,200,'Pelicula Eliminada');       
    }
}
