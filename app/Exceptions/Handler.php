<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ApiResponser;

//IPORTANDO DEFINIÇÃO DE EXEÇÕES DO FUNDATION
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if($exception instanceof AuthenticationException){
            return $this->errorResponse('No autenticado',401);
        }
       
        if($exception instanceof QueryException){
           //dd($exception->errorInfo);
           if( $exception->errorInfo[1] == 1451):
                $error = "O recurso que tenta eliminar tem outros recursos do BD amarrados";
            else:
                
                $error = "A eliminação solicitada retornou um erro de segurança do banco 
                de dados, pode ser um recurso comppartilhado o qual está tentando eliminar";
            endif;
        
            return $this->errorResponse($error,405);
        }
        
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('Metodo HTTP invalido para esta rota',405);
        }
        
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('URL inexistente',404);
        }
        
        if($exception instanceof AuthorizationException){ 
            return $this->errorResponse('Nao tem permisao para executar esta acao ou acessar esta url',403);
        }
        
        if($exception instanceof ValidationException){
            $errors = $exception->validator->errors()->getMessages();
            return $this->errorResponse($errors,422);
            
        }
        
        if( $exception instanceof ModelNotFoundException):
            $modelo = $exception->getModel();
            return $this->errorResponse("Nao existe nenhuma instancia - $modelo - com o id especificado",404);
        endif;
        
        
        // CASO SEJA UMA EXCEPTION QUE NAO SE ENQUADRE NAS ANTERIORES
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }

        if( config('app.debug')):

            return parent::render($request, $exception);
        else:
            return $this->errorResponse('Falha inesperada, retorne mais tarde',500);
        endif;
    }
    
    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

}
