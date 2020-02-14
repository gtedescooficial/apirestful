<?php 
namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

trait ApiResponser{



    private function successResponse($data,$code = 200){
        return response()->json($data,$code);

    }

    protected function errorResponse($message,$code = 404){
        return response()->json(array('message'=>$message,'code'=>$code),$code);
    }

    protected function showAll(Collection $collection, $code = 200){
        
        // CASO A COLLECTION VIER VAZIA, ENTAO DIRETAMENTE NEM TRANSFORMO NADA
        if( $collection->isEmpty() ){

            return $this->successResponse(['data'=>$collection],$code);
        }

        // ESTOU PUXANDO A PROPRIEDADE TRANSFORMER DO MODELO, PARA SABER QUAL É
        // E PODER PREENCHER O ATRIBUTO DO METODO TRANSFORMDATA
        $transformer = $collection->first()->transformer;

        //ANTES FILTRO SE FOI ENVIADA NA URL ALGUMA QUERYSTRING P FILTRAR A BUSCA
        $collection = $this->filterData($collection, $transformer);
        // dd($collection);
        
        // ANTES DE TRANSFORMAR APLICO UM ORDER BY
        $collection = $this->sortData($collection, $transformer);
        
        // DEPOIS DE TER FILTRADO E ORDERADO PAGINAMOS OS RESULTADOS
        $collection = $this->paginate($collection);
        
        // AGORA MANDO TRANSFORMAR A COLLECTION COM SEU TRANSFORMADOR CORRESPONDENTE
        //LEMBRAR QUE ISTO RETORNA UM ARRAY, JA NAO MAIS UMA COLEÇÃO!
        $collection = $this->transformData($collection, $transformer);

        // AGORA VAMOS APLICAR CACHE NA RESPOSTA
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection,$code);
    }
    
    protected function showOne(Model $model, $code = 200){
        $transformer = $model->transformer;
        $transformed = $this->transformData($model, $transformer);
        return $this->successResponse($transformed,$code);
    }

    protected function showMessage($message, $code = 200){
        return $this->successResponse(['data' =>$message],$code);
    }

    protected function transformData($data, $transformer){
        
        $transformation = fractal($data, new $transformer);

        return  $transformation->toArray();
    }

    protected function sortData(Collection $collection, $transformer){

            if( request()->has('sort_by') ){

                $attr = $transformer::originalAttr( request()->sort_by ) ;
                $collection = $collection->sortBy->{$attr};
            }
            return $collection;
    }

    protected function filterData(Collection $collection, $transformer){
        // dd($transformer);

        foreach (request()->query() as $query => $value) {
            
            $attribute = $transformer::originalAttr($query);
          
            if( isset($attribute,$value)){

                $collection = $collection->where($attribute, $value);

              

            }
        }

        return $collection;
    }


    protected function paginate(Collection $collection){

            $rules = [
                'per_page' => 'integer|min:2|max:50',
            ];


            Validator::validate(request()->all(), $rules);


        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;

        if(request()->has('per_page')){

            // dd(request()->per_page);

            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice( ($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends( request()-> all() );
        return $paginated;




    }

    public function cacheResponse( $data){

        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";
        return Cache::remember($fullUrl, 30/60, function() use ($data){

            return $data;
    });

    }

}



?>