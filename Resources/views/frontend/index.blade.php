@extends('layouts.master')

@section('title')
    {{$category->title}} | @parent
@stop
@section('meta')
    <meta name="description" content="@if(!empty($category->description)){!!$category->description!!}@endif">
    <!-- Schema.org para Google+ -->
    <meta itemprop="name" content="{{$category->title}}">
    <meta itemprop="description" content="@if(! empty($category->description)){!!$category->description!!} @endif">
    <meta itemprop="image"
          content=" @if(! empty($category->options->mainimage)){{url($category->options->mainimage)}} @endif">

@stop

@section('content')


<div class="page document page-{{$category->slug}}">
    <div class="container">
        <div class="row">

            <div class="row">
                <div class="col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="/">Inicio</a></li>
                        <li>{{$category->title}}</li>
                    </ol>
                </div>
            </div>

            <!-- document Entries Column -->
            <div class="col-xs-12 col-md-12 category-body-1 my-5">

                @php
                $titulo = $category->title;
                $pos1 = strpos($titulo, ' ');
                if(isset($pos1)&&!empty($pos1)) {
                    $titulo1 = substr($titulo, 0, ($pos1 +1));
                    $titulo2 = substr($titulo, ($pos1 +1) ,strlen($titulo) );
                } else
                { $pos1 = 0;
                    $titulo1 = $titulo;
                    $titulo2 = "";
                }

                @endphp

                <h1 class="page-header border-top w-25">{{$titulo1}}<br><span class="text-primary">{{$titulo2}}</span></h1>
                <div class="list-group">


                @if (!empty($docs))

                    <?php $cont=0; ?>
                        <ul class="list-group list-group-flush list-doc">
                            <table class="table table-hover doc">
                                <thead>
                                <tr>
                                    <th scope="col">IMAGEN</th>
                                    <th scope="col">DOCUMENTO</th>
                                    <th scope="col">TAMAÑO</th>
                                    <th scope="col">FECHA CREACION</th>
                                    <th scope="col">FECHA ACTUALIZACION</th>
                                    <th scope="col">DESCARGAR</th>
                                </tr>
                                </thead>
                    @foreach($docs as $doc)
                                <!-- document doc -->

                                        <tbody>
                                            <tr>
                                                <td>
                                                    @if(isset($doc->options->mainimage)&&!empty($doc->options->mainimage))
                                                        <img class="img-fluid" src="{{url($doc->options->mainimage)}}"
                                                             alt="{{$doc->title}}"/>
                                                    @else
                                                        <img class="img-fluid"
                                                             src="{{url('modules/iblog/img/doc/default.jpg')}}" alt="{{$doc->title}}"/>
                                                    @endif
                                                </td>
                                                <td><a href="{{URL($doc->url)}}" target="_blank">
                                                        <h2>
                                                            <i class="fa fa-file-pdf-o icon" aria-hidden="true"></i><span class="doc">{{$doc->title}}</span>
                                                        </h2>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="doc" style="color: #6c8495">{{$doc->size}}</span>
                                                </td>
                                                <td>
                                                    <span class="doc"  style="color: #6c8495">{{format_date($doc->created_at, '%B %d, %Y')}}</span>

                                                </td>
                                                <td>
                                                    <span class="doc"  style="color: #6c8495">{{format_date($doc->updated_at, '%B %d, %Y')}}</span>
                                                </td>
                                                <td style="padding-left: 50px">
                                                    <a href="{{URL($doc->url)}}" target="_blank"><i class="fa fa-download icon" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        <tbody>




                        <?php $cont++; ?>

                    @endforeach
                            </table>
                        </ul>
                        <br>

                </div>

                    <div class="clearfix"></div>
                    <div class="paginacion-document row">
                        {{ $docs->links() }}
                    </div>

                @endif

            </div>

        </div>
        
    </div>

</div>
@stop