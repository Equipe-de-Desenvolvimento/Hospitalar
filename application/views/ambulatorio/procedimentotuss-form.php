<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Procedimento TUSS</a></h3>
        <div>
            <form name="form_procedimento" id="form_procedimento" action="<?= base_url() ?>ambulatorio/procedimento/gravartuss" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Nome</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtNome" class="size10" value="<?= $procedimento[0]->descricao; ?>" />
                        <input type="hidden" name="tuss_id" value="<?= $procedimento[0]->tuss_id; ?>" />
                    </dd>
                    <dt>
                    <label>Codigo</label>
                    </dt>
                    <dd>
                        <input type="text" name="procedimento" id="procedimento" class="size04" value="<?= $procedimento[0]->codigo; ?>"/>
                    </dd>
                    <dt>
                    <label>Valor</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtvalor" class="texto02" value="<?= $procedimento[0]->valor; ?>"/>
                    </dd>
                    <dt>
                    <label>Classificaco</label>
                    </dt>
                    <dd>

                        <select name="classificaco" id="classificaco" class="size2" >
                            <option value='' >selecione</option>
                            <?php foreach ($classificacao as $item) { ?>
                                <option value="<?php echo $item->tuss_classificacao_id; ?>" <?if ($item->tuss_classificacao_id ==  $procedimento[0]->classificacao):echo 'selected'; endif;?>><?php echo $item->nome; ?></option>
                            <?php } ?>
                        </select>
                    </dd>
                    <dt>
                    <label>Texto</label>
                    </dt>
                    <div>
                        <textarea id="laudo" name="laudo" rows="10" cols="60" style="width: 80%"><?= $procedimento[0]->texto; ?></textarea>
                    </div>
                </dl>    

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
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