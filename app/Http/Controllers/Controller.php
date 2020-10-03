<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function JsonResponse($data)
    {     
       $response = [];
       $response['data'] = $data;
       $response['error'] = NULL;

       return json_encode($response);
    }

    /**
     * Método genérico que controla y retornar peticiones exitosas del API
     */
    public function JsonResponseSuccess($data, $code = 200, $msg = NULL)
    {       
        $response = [];
        $response['code'] = $code;
        $response['status'] = $code;
        $response['data'] = $data;
        $response['message'] = $msg;
        $response['error'] = NULL;

        if ($code == 200 && gettype($data) === 'array') {
            $response['totalRecords'] = count($data);
            
        }

        if ($code == 201) {
            $response['message'] = "¡Registro exitoso!";
        }

        if ($code == 204) {
            $response['message'] = "¡Registro no encontrado!";
        }

        if ($code == 200 && is_null($data)) {
            $code = 204;
            $response['code'] = $code;
            $response['status'] = $code;
            $response['message'] = $msg ? $msg : 'Sin contenido';
        }
       
        return json_encode($response);
    }

    
    /**
     * Método genérico que controla y retornar los erroes de Peticiones Malas
     */
    public function JsonResponseBadRequest($data)
    {    
        $response = [];
        $response['code'] = 400;
        $response['status'] = 400;
        $response['trackIdInvalid'] = false;
        $response['message'] = "¡Error, por favor llene los datos faltantes!";
        $response['error'] = true;

        if (key_exists('info', $data)) {
            $response['info'] = $data['info'];
        }

        if (key_exists('value', $data)) {
            $response['value'] = $data['value'];
        }

        if (key_exists('trackIdInvalid', $data)) {
            $response['trackIdInvalid'] = $data['trackIdInvalid'];
        }

        if (key_exists('message', $data)) {
            $response['message'] = $data['message'];
        } elseif (key_exists('info', $data)) {
            $response['message'] = $data['info'];
        }
        return json_encode($response);      
    }

    /**
     * Método genérico que controla y retorna los errores de Acceso Denegado
     */
    public function JsonResponseAccessDenied()
    {     
        $response = [];
        $response['code'] = 403;
        $response['status'] = 403;
        $response['message'] = "¡Lo siento; usted no tiene permiso para acceder a este recurso!";
        $response['error'] = true;

        return json_encode($response);       
    }

    /**
     * Método genérico que retorna mensaje de error 403
     */
    public function JsonResponse403($data = null)
    {     
        $response = [];
        $response['code'] = 403;
        $response['status'] = 403;
        $response['message'] = "¡Lo siento; usted no se encuentra activo en el sistema!";
        $response['error'] = true;

        if (!is_null($data) && key_exists('message', $data)) {
            $response['message'] = $data['message'];
        }     
        return json_encode($response);
    }

    /**
     * Método retorna mensaje de registros que no se pueden borrar
     */
    public function JsonResponseEliminarRegistroSistema()
    {      
        $response = [];
        $response['code'] = 403;
        $response['status'] = 403;
        $response['message'] = "¡Lo siento; este registro no se puede eliminar del sistema!";
        $response['error'] = true;
        
        return json_encode($response);
    }

    /**
     * Método genérico para registro no encontrado del API
     */
    public function JsonResponseNotFound()
    {    
        $response = [];
        $response['code'] = 404;
        $response['status'] = 404;
        $response['message'] = "¡Registro no encontrado!";
    
        return json_encode($response);
    }

    /**
     * Método genérico que controla y retorna los diferentes errores del API
     */
    public function JsonResponseError($data, $option)
    {
      
   
        $response = [];
        $response['code'] = 500;
        $response['status'] = 500;
        $response['error'] = true;

        if ($option == 'exception') {
            $response['message'] = "Ocurrió un error en el Servidor";
            $response['exception'] = $data->getMessage();
            $response['type'] = 'exception';
        }

        if ($option == 'validator') {
            $response['message'] = "Error en uno de los campos del registro";
            $response['description'] = $data;
            $response['type'] = 'validator';
        }

        if ($option == 'check_parameters') {
            $response['value'] = $data['value'];
            $response['message'] = $data['message'];
            $response['type'] = 'check_parameters';
            $response['code'] = 400;
            $response['status'] = 400;
        }

        if ($option == 'api_portal') {
            $response['message'] = $data['message'];
            $response['code'] = 400;
            $response['status'] = 400;
            $response['type'] = 'exception';
        }

        if ($option == 'file') {
            $response['message'] = "Ocurrió un error en el Servidor";
            $response['exception'] = $data['message'];
            $response['type'] = 'exception';
        }
        return json_encode($response);       
    }
}
