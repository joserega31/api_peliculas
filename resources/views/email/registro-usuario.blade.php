@extends('layout.email')

@section('nombre')

    <tr>
      <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
        <div style="font-family:Roboto;font-size:16px;line-height:1;text-align:left;color:#000000;">
            Estimado(a), <b> {{$msj['name']}} . </b></div>
      </td>
    </tr>

@stop       


@section('body')  

   <tr>
      <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
        <div style="font-family:Roboto;font-size:16px;line-height:1.6;text-align:left;color:#000000;">
            Sus credenciales son:<br>
            <b>Usuario: {{$msj['email']}}</b> <br>
            <b>Contraseña: {{$msj['password']}}</b><br>
        </div>
      </td>
    </tr>  

@stop