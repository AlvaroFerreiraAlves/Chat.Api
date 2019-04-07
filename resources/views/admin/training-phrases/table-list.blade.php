@extends('adminlte::page')

@section('title', 'Frases de treinamento')

@section('content_header')
    <h1>Frases de treinamento</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <form action="{{route('admin.searchphrases')}}" method="POST" class="form form-inline">
                {!! csrf_field() !!}

                <select name="status" class="form-control" required>
                    <option value="">-- Selecione o Status --</option>
                    <option value="0">Não exportado</option>
                    <option value="1">Exportado</option>

                </select>

                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <a href="{{route('admin.downlaodfilephrases', $status)}}" class="btn btn-success pull-right">Exportar dados</a>

            </form>
        </div>
        <div class="box-body">
            <table class="table" id="tabela">
                <thead>
                <tr>
                    <th scope="col">Cód</th>
                    <th scope="col">Frase</th>
                    <th scope="col">Data</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($phrases as $phrase)
                    <tr>
                        <th scope="row">{{$phrase->id}}</th>
                        <td>{{$phrase->phrases}}</td>
                        <td>{{date_format($phrase->created_at,'d/m/Y H:i')}}</td>
                        <td>
                            @if($phrase->status === 0)
                                <span class="label label-danger">Não exportado</span>
                            @else
                                <span class="label label-success">Exportado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if(isset($dataForm))
                {!! $phrases->appends($dataForm)->links() !!}
            @else
                {!! $phrases->links() !!}
            @endif
        </div>
    </div>
@stop