$(document).ready(function(){

    $(".fancybox").fancybox({
        tpl: {
            closeBtn: '<div title="Close" id="myCloseID ">[CLOSE]</div>'
        }
    });

    $(".select2_single").select2({
        allowClear: true
    });

    $("#data").datepicker(); // Sample

    $('select[name=filial]').on('change', function(){
        var filial_id = this.value;
        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: route_cliente_ajax,
            dataType: 'json',
            data: {filial_id : filial_id}
        })
            .done(function(data) {
                //$("select[name='cliente']").select2("destroy");
                var $el = $("select[name='cliente']");
                $el.empty(); // remove old options
                $el.append($("<option value='' selected='selected'>Todos</option>"));

                if (data != '') {
                    $.each(data, function (value, key) {
                        $el.append($("<option></option>")
                            .attr("value", key.clt_id).text(key.clt_nome_razao));
                    });
                    var filial_pesquisada = $('input[name=cliente_pesquisada]').val();
                    if(filial_pesquisada > 0) {
                        $("select[name='cliente']").val(filial_pesquisada);
                        //$('input[name=cliente_pesquisada]').val('');
                    }
                }
                //$("select[name='cliente']").select2();
                $('#cliente').trigger('change');
            })
            .error(function(e){
                //alert('Ocorreu um erro ao tentar buscar o cliente!');
            });
    }).trigger('change');

    $('select[name=cliente]').on('change', function(){
        var cliente_id = this.value;
        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: route_projeto_ajax,
            dataType: 'json',
            data: {
                cliente_id : cliente_id
            }
        })
        .done(function(data) {
            //$("select[name='projeto']").select2("destroy");
            var $el = $("select[name='projeto']");
            $el.empty(); // remove old options
            $el.append($("<option value='' selected='selected'>Todos</option>"));

            if (data != '') {
                $.each(data, function (value, key) {
                    $el.append($("<option></option>")
                        .attr("value", key.equip_id).text(key.equip_desc));
                });
                var cliente_pesquisada = $('input[name=projeto_pesquisada]').val();
                if(cliente_pesquisada > 0) {
                    $("select[name='projeto']").val(cliente_pesquisada);
                    //$('input[name=projeto_pesquisada]').val('');
                }
            }
            //$("select[name='projeto']").select2();
        })
        .error(function(e){
            //alert('Ocorreu um erro ao tentar buscar o projeto!');
        });
    }).trigger('change');


    $('#form_filtrar').submit(function(e){
        e.preventDefault();

        cor = '';

        $div_result_form = $('#div_result_form');
        $div_result_form.html('Carregando....');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route_filtro,
            type: "POST",
            data: $('#form_filtrar').serializeArray(),
            success:function(html){
                $div_result_form.html(html);

                // pegando primeira data da tabela
                var prmeira_data = $('input[name=aux_primeira_data_tabela]').val();
                $('input[name=primeira_data_tabela]').val(prmeira_data);

                table = $('#tabela_datatable').DataTable({
                    language: {
                        url: url_language
                    },
                    stateSave: true,
                    scrollY:        "500px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         false,
                    fixedColumns:   {
                        leftColumns: 1
                    },
                    rowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

                        // zebra
                        if(cor == 'rgb(211,214,255)') {
                            cor = 'rgb(234,235,255)';
                        } else {
                            cor = 'rgb(211,214,255)';
                        }

                        $(nRow).find('td:eq(0)')
                            .css('background-color', cor)
                            .css('color', 'black');
                        //console.log(aData);
                        //$(nRow[0]).closest('td').css('background-color', '#ff0000');
                        //$('td:first', nRow[0]).css('background-color', '#f2dede' );
                    }
                });
            },
            error:function(){
                $div_result_form.html('Erro ao carregar. Tente novamente!')
            }
        });
    }).trigger('submit');

    /*
    *
    * botoes de direcao
    * */
    setar_direcao = function(direcao) {
        $('input[name=semana_direcao]').val(direcao);
        $('#form_filtrar').trigger('submit');
        // limpar logo em seguida
        $('input[name=semana_direcao]').val('');
    };
    $('#btn_anterior').on('click', function(){
        setar_direcao('anterior');
    });
    $('#btn_seguinte').on('click', function(){
        setar_direcao('seguinte');
    });


    atualizar_agenda_corporativa = function() {
        $('#form_filtrar').trigger('submit');
    }


});
