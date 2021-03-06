<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Sala</a></h3>
        <div>
            <form name="form_sala" id="form_sala" action="<?= base_url() ?>ambulatorio/sala/gravar" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="txtexamesalaid" class="texto10" value="<?= @$obj->_exame_sala_id; ?>" />
                        <input type="text" name="txtNome" class="texto10" value="<?= @$obj->_nome; ?>" />
                    </dd>
                    <dt>
                    <label>Nome Chamada</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtnomechamada" class="texto10" value="<?= @$obj->_nome_chamada; ?>" />
                    </dd>
                    <dt>
                    <label>Tipo de sala</label>
                    </dt>
                    <dd>
                        <select name="tipo" id="tipo" class="size2" >
                            <option value='CONSULTORIO'<? if (@$obj->_tipo == 'CONSULTORIO'):echo 'selected';
endif;
?>>CONSULTORIO</option>
                            <option value='EXAME'<? if (@$obj->_tipo == 'EXAME'):echo 'selected';
                                    endif;
?>>EXAME</option>
                        </select>
                    </dd>
                </dl>    
                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });


    $(document).ready(function(){
        jQuery('#form_sala').validate( {
            rules: {
                txtNome: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                }
            }
        });
    });

</script>