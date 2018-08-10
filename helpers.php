<?php

use Modules\Idocs\Entities\Category;
use Modules\Idocs\Entities\Doc;
use Modules\Idocs\Entities\Status;
use Modules\User\Entities\Sentinel\User;


if (! function_exists('get_docs')) {

    function get_docs($options=array())
    {

        $default_options = array(
            'categories' => null,// categoria o categorias que desee llamar, se envia como arreglo ['categories'=>[1,2,3]]
            'users' => null, //usuario o usuarios que desea llamar, se envia como arreglo ['users'=>[1,2,3]]
            'include' => array(),//id de post a para incluir en una consulta, se envia como arreglo ['id'=>[1,2,3]]
            'exclude' => array(),// post, categorias o usuarios, que desee excluir de una consulta metodo de llmado category=>'', docs=>'' , users=>''
            'exclude_categories' => null,// categoria o categorias que desee Excluir, se envia como arreglo ['exclude_categories'=>[1,2,3]]
            'exclude_users' => null, //usuario o usuarios que desea Excluir, se envia como arreglo ['users'=>[1,2,3]]
            'created_at'=>['operator'=>'<=','date'=>date('Y-m-d H:i:s')],
            'take' => 5, //Numero de docs a obtener,
            'skip' => 0, //Omitir Cuantos post a llamar
            'order' => 'desc',//orden de llamado
            'status' => Status::PUBLISHED
        );

        $options = array_merge($default_options, $options);

        $docs = Doc::with(['categories']);

        if (!empty($options['categories']) || isset($options['exclude_categories'])) {

            $docs->leftJoin('idocs__doc__category', 'idocs__doc__category.doc_id', '=', 'idocs__docs.id');

        }

        // ojo estos dos IF requieren atencion, el whereIn no funciona
        if (!empty($options['categories'])) {

            $docs->where('idocs__docs.category_id', '=', $options['categories']);
        }

        //
        if (isset($options['exclude_categories'])) {

            $docs->whereNotIn('idocs__doc__category.category_id', $options['exclude_categories']);
        }

        if (!empty($options['exclude'])) {
            $docs->whereNotIn('idocs__docs.id', $options['exclude']);
        }

        if (isset($options['created_at'])) {
            $docs->where('created_at',$options['created_at']['operator'], $options['created_at']['date']);
        }

        $docs->whereStatus($options['status'])
            ->skip($options['skip'])
            ->take($options['take']);

        $docs->orderBy('created_at', $options['order']);

        if (!empty($options['include'])) {
            $docs->orWhere(function ($query) use ($options) {
                $query-> whereIn('idocs__docs.id', $options['include']);
            });

        }
        return $docs->get();

    }
}