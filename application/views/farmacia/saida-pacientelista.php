<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <div class="clear"></div>
    <form name="form_menuitens" id="form_menuitens" action="<?= base_url() ?>seguranca/operador/gravaroperadorconvenioprocedimento" method="post">
           
        <fieldset>
    <?

        ?>
        <table id="table_agente_toxico" border="0">
            <thead>

                <tr>
                    <th class="tabela_header">Produto</th>
                    <th class="tabela_header">Qtde</th>
                    <th class="tabela_header">Codigo de Barras</th>
                </tr>
            </thead>
            <?
            $estilo_linha = "tabela_content01";
            foreach ($lista as $item) {
                ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                ?>
                <tbody>
                    <tr>
                        <td class="<?php echo $estilo_linha; ?>"><?= $item->descricao; ?></td>
                        <td class="<?php echo $estilo_linha; ?>"><input type="text" name="txtNome" id='txtNome' class="texto02" /></td>
                        <td class="<?php echo $estilo_linha; ?>"><input type="text" name="descricao" id='descricao' class="texto04" /></td>

                    </tr>

                </tbody>
                <?
            }
        ?>

    </table> 

                        <div>
                <label>&nbsp;</label>
                <button type="submit" name="btnEnviar">Enviar</button>
            </div>
    </form>
            
</fieldset>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">


            function selecionaTexto()
            {
                document.getElementById("txtNome").focus();
            }


    $(function() {
        $( "#accordion" ).accordion();
    });



</script>