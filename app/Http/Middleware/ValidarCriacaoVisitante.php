<?php

namespace App\Http\Middleware;

use Closure;

class ValidarCriacaoVisitante
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data=$request->json()->all();

        $visitante_valido = true;
        $arrayErro=[];

        if(isset($data['nome'])) {

        } else {
            $visitante_valido = false;
            array_push($arrayErro,"O nome é obrigatorio");
        }

        if(isset($data['tipo_documento'])){
          if (in_array($data['tipo_documento'], ['BI', 'passaporte', 'DIRE'])) {

          } else {
            $visitante_valido = false;
            array_push($arrayErro, sprintf("<<%s>> é um tipo de documento inválido", $data['tipo_documento']));
          }
        } else {
           $visitante_valido = false;
           array_push($arrayErro,"O tipo de documento é obrigatorio");
        }


        if (isset($data['numero_documento'])) {
            $nr_visitantes = \App\Visitante
                ::where('numero_documento', $data['numero_documento'])
                ->count();

            if ($nr_visitantes > 0) {
                $visitante_valido = false;
                array_push($arrayErro, "Um visitante com este documento ja existe.");
            }
        } else { 
             $visitante_valido=false;
            array_push($arrayErro,"O numero de documento eh obrigatorio"); 
        }

        if(isset($data['contacto'])){
            $nr_visitantes = \App\Visitante
                ::where('contacto', $data['contacto'])
                ->count();

            if ($nr_visitantes > 0) {
                $visitante_valido = false;
                array_push($arrayErro, "Um visitante com este contacto ja existe.");
            }
        
        }else{
            $visitante_valido=false;
            array_push($arrayErro,"O contacto eh obrigatorio"); 
        

        if(isset($data['email'])){
            $nr_visitantes = \App\Visitante
                ::where('email', $data['email'])
                ->count();

            if ($nr_visitantes > 0) {
                $visitante_valido = false;
                array_push($arrayErro, "Um visitante com este email ja existe.");
            }

        }

       if(isset($data['tipo_visitante'])) {

        if (in_array($data['tipo_visitante'], ['interno', 'externo'])) {

          } else {
            $visitante_valido = false;
            array_push($arrayErro, sprintf("<<%s>> é um tipo de visitante inválido", $data['tipo_visitante']));
        
            } 
        }
       if ($visitante_valido) {
            $request->{"visitante_data"} = $data;
            return $next($request);
       } else {
            return response()->json($arrayErro,400);
       }
    }
} }
// 