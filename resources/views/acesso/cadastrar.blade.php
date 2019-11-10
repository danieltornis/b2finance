@extends('master')

@section('styles')


@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Acesso</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <!-- if there are creation errors, they will show here -->

                            @include('flash::message')

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    <h4 class="alert-heading">Erros!</h4>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                    @endforeach
                                </ul>
                            @endif

                            {!! Form::open([
                                'route' => 'acesso.gravar',
                                'class' => 'form-horizontal form-label-left'
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('permissao', 'Permissão', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::text('permissao', Input::old('permissao'), array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('descricao', 'Descrição', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::text('descricao', Input::old('descricao'), array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    {{ csrf_field() }}
                                    {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
                                    <a href="{{ route('acesso') }}" class="btn btn-primary">Cancelar</a>
                                </div>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('scripts')


@stop
