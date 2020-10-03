<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistoUsuario;

/**
* @OA\Info(title="Prueba API", version="1.0")
*
* @OA\Server(url="http://swagger.local")
*/

class UserController extends Controller
{
  
    /**
    * @OA\Post(
    *     path="/api/loginUser",
    *     summary="Login Usuario.",
    * tags={"User"},
    *      @OA\Parameter(
    *      name="email",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *      @OA\Parameter(
    *      name="password",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
*
 * @OA\RequestBody(
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 
 *                 example={"email":" ","password": " "}
 *             )
 *         )
 *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Login de usuario .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="User")
    * )    
    */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
    
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }



    /**
    * @OA\Post(
    *     path="/api/registrarUser",
    *     summary="Registrar Usuario.",
    * tags={"User"},
    *      @OA\Parameter(
    *      name="email",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *       @OA\Parameter(
    *      name="nombre",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="apellido",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),  
    *    @OA\Parameter(
    *      name="fecha_nacimiento",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="date"
    *      )
    *   ),
    *      @OA\Parameter(
    *      name="password",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *
    *
    *   @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="nombre",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="apellido",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="fecha_nacimiento",
 *                     type="date"
 *                 ),                 
 *                 @OA\Property(
 *                     property="password",
 *                     type="date"
 *                 ),                 
 *                            
 * 
 *                 example={"email":"admin@gmail.com","nombre": "Jose", "apellido": "Regalado",
 *                  "fecha_nacimiento":"2020-10-10","password":"000000"
 *                  }
 *             )
 *         )
 *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Registrar Usuario .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="User")
    * )    
    */
    public function registrarUser(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:60',
                'apellido' => 'required|string|max:60',
                'fecha_nacimiento' => 'required',
                'email' => 'required|string|email|max:100|unique:users',               
                'password' => [
                    'required',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                ],            
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'nombre' => $request->get('nombre'),
                'apellido' => $request->get('apellido'),
                'fecha_nacimiento' => $request->get('fecha_nacimiento'),
                'email' => $request->get('email'),               
                'activo' => true,           
                'password' => Hash::make($request->get('password')),
            ]);         

        } catch (Exception $ex) {
            return $this->JsonResponseError($ex, 'exception');
        }
        return $this->JsonResponseSuccess($user,200,'Registro exitoso!');     
    }

     /**
    * @OA\Post(
    *     path="/api/editarUser",
    *     summary="Editar usuario",
    * tags={"User"},
    *        @OA\Parameter(
    *      name="nombre",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="apellido",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),  
    *    @OA\Parameter(
    *      name="fecha_nacimiento",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="date"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="activo",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="boolean"
    *      )
    *   ),
    *
    *   @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="nombre",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="apellido",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="fecha_nacimiento",
 *                     type="date"
 *                 ),                 
 *                 @OA\Property(
 *                     property="activo",
 *                     type="boolean"
 *                 ),                 
 * 
 *                 example={"nombre": "Jose", "apellido": "Regalado",
 *                  "fecha_nacimiento":"2020-10-10","activo":true
 *                  }
 *             )
 *         )
 *     ),
 * 
 * 
    *     @OA\Response(
    *         response=200,
    *         description="Editar Usuario .",
    *        
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Ha ocurrido un error."
    *     ),
    * @OA\Tag(name="User")
    * )    
    */
    public function editarUser(Request $request)
    {
       

        try {
            $user = User::find($request->input('id'));
            if(!$user){
                $response['value'] = '';
                $response['info'] = "usuario no registrado";
                return $this->JsonResponseBadRequest($response);
            }

            $nombre = $request->input('nombre'); 
            $apellido = $request->input('apellido'); 
            $fecha_nacimiento = $request->input('fecha_nacimiento'); 
            $activo = $request->input('activo'); 

            
            $modificar = false;

            if (trim($nombre) && !is_null($nombre) && $user->nombre != $nombre) {  
                $user->nombre = $nombre;    
                $modificar = true;
            }

            if (trim($apellido) && !is_null($apellido) && $user->apellido != $apellido) {  
                $user->apellido = $apellido;    
                $modificar = true;
            }

            if (trim($fecha_nacimiento) && !is_null($fecha_nacimiento) && $user->fecha_nacimiento != $fecha_nacimiento) {  
                $user->fecha_nacimiento = $fecha_nacimiento;    
                $modificar = true;
            }

            if ($user->activo != $activo) {  
                $user->activo = $activo;    
                $modificar = true;
            }
            
            if ($modificar) {
                $user->save();
            } else {
                return $this->JsonResponseSuccess(NULL, 200, "¡Registro sin alterar!");
            }             

        } catch (Exception $ex) {
            return $this->JsonResponseError($ex, 'exception');
        }

        return $this->JsonResponseSuccess($user,200,'Usuario Editado');        
    }
}
