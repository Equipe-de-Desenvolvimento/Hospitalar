<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Campo OD AV</a></h3>
        <div>
            <form name="form_grupomedico" id="form_grupomedico" action="<?= base_url() ?>ambulatorio/modelooftamologia/gravarodav" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                        <label>Valor</label>
                    </dt>
                    <dd>
                        <input type="text" name="txtNome" class="texto10" value="<?//= @$obj->_nome; ?>" />
                    </dd>
                    
                    <dd>
                        <input type="hidden" name="numero" class="texto10" value="<?//= @$obj->_nome; ?>" />
                    </dd>
                   
                </dl>

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
            <br>
            <br>
            <? if (count($odav) > 0) { ?>

                <table>
                    <thead>
                        <tr>
                            <th class="tabela_header">Valor</th>
                            <th class="tabela_header" width="70px;" colspan="2"><center>Detalhes</center></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?
                        $estilo_linha = "tabela_content01";
                        foreach ($odav as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            ?>
                            <tr>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome; ?></td>                             <td class="<?php echo $estilo_linha; ?>" width="70px;">                                  
                                    <a onclick="javascript: return confirm('Deseja realmente exlcuir essa opção?');" href="<?= base_url() ?>ambulatorio/modelooftamologia/excluirodav/<?= $item->od_av_id ?>">Excluir</a>
                                </td>
                            </tr>

                        </tbody>
                    <? } ?>

                    <tfoot>
                        <tr>
                            <th class="tabela_footer" colspan="6">
                                Valor Total: <?php echo number_format(count($odav)); ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>

            <? } ?>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
                                        $('#btnVoltar').click(function () {
                                            $(location).attr('href', '<?= base_url(); ?>ambulatorio/modelooftamologia');
                                        });

                                        $(function () {
                                            $("#accordion").accordion();
                                        });


                                        

</script>