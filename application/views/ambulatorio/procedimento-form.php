<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ponto/horariostipo">
            Voltar
        </a>

    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Horario</a></h3>
        <div>
            <form name="form_procedimento" id="form_procedimento" action="<?= base_url() ?>ambulatorio/procedimento/gravar" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="hidden" name="txtprocedimentotussid" value="<?= @$obj->_procedimento_tuss_id; ?>" />
                        <input type="text" name="txtNome" class="texto10" value="<?= @$obj->_nome; ?>" />
                    </dd>
                    <dt>
                    <label>Procedimento</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtprocedimento" id="txtprocedimento" class="size2" value="<?= @$obj->_tuss_id; ?>"  />
                        <input type="hidden" name="txtcodigo" id="txtcodigo" class="size2" />
                        <input type="hidden" name="txtdescricao" id="txtdescricao" class="size2" />
                        <input type="text" name="txtprocedimentolabel" id="txtprocedimentolabel" class="size10" value="<?= @$obj->_descricao; ?>" />
                    </dd>
                    <dt>
                    <label>Grupo</label>
                    </dt>
                    <dd>
                        <select name="grupo" id="grupo" class="size1" >
                            <option value='' >Selecione</option>
                            <?foreach($grupos as $item){?>
                            <option value='<?=$item->nome?>' <?if(@$obj->_grupo == $item->nome){echo 'selected';}?>><?=$item->nome?></option>    
                                
                            <?}?>
                        </select>
                    </dd>
                    <dt>
                    <label>Perc. Medico</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtperc_medico" class="texto" value="<?= @$obj->_perc_medico; ?>" required/>
                    </dd>
                    <dt>
                    <label>Qtde de sess&otilde;es</label>
                    </dt>
                    <dd>
                        <input type="number" name="txtqtde" min="1" class="texto" value="<?= @$obj->_qtde; ?>" required/>
                    </dd>
                    <dt>
                    <label>Dencidade Calor.</label>
                    </dt>
                    <dd>
                        <input type="text" name="dencidade_calorica" class="texto" value="<?= @$obj->_dencidade_calorica; ?>" />
                    </dd>
                    <dt>
                    <label>Proteinas</label>
                    </dt>
                    <dd>
                        <input type="text" name="proteinas" class="texto" value="<?= @$obj->_proteinas; ?>" />
                    </dd>
                    <dt>
                    <label>Carboidratos</label>
                    </dt>
                    <dd>
                        <input type="text" name="carboidratos" class="texto" value="<?= @$obj->_carboidratos; ?>" />
                    </dd>
                    <dt>
                    <label>Lipidios</label>
                    </dt>
                    <dd>
                        <input type="text" name="lipidios" class="texto" value="<?= @$obj->_lipidios; ?>" />
                    </dd>
                    <dt>
                    <label>Kcal nao proteicas</label>
                    </dt>
                    <dd>
                        <input type="text" name="kcal" class="texto" value="<?= @$obj->_kcal; ?>" />
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
        $("#accordion").accordion();
    });

    $(function() {
        $("#txtprocedimentolabel").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=procedimentotuss",
            minLength: 3,
            focus: function(event, ui) {
                $("#txtprocedimentolabel").val(ui.item.label);
                return false;
            },
            select: function(event, ui) {
                $("#txtprocedimentolabel").val(ui.item.value);
                $("#txtprocedimento").val(ui.item.id);
                $("#txtcodigo").val(ui.item.codigo);
                $("#txtdescricao").val(ui.item.descricao);
                return false;
            }
        });
    });

    $(document).ready(function() {
        jQuery('#form_procedimento').validate({
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                },
                txtprocedimentolabel: {
                    required: true
                },
                grupo: {
                    required: true
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                },
                txtprocedimentolabel: {
                    required: "*"
                },
                grupo: {
                    required: "*"
                }
            }
        });
    });

</script>