<div class="content ficha_ceatox">
    <h3 class="h3_title">Evolucao de Notificação e de Atendimento</h3>
    <form name="evolucao_form" id="evolucao_form" action="<?=base_url() ?>emergencia/emergencia/gravarevolucao/<?= "$ficha"?>" method="post">
        <fieldset>
            <legend>Atendimento</legend>
            <div>
                <label>Idade</label>
                <input type="text" name="txtIdade" id="txtIdade" alt="numeromask" class="size1" />
            </div>
            <div>
                <label>Satura&ccedil;&atilde;o %</label>
                <input type="text" name="txtSaturacao" id="txtSaturacao" alt="numeromask" class="size1" />
            </div>
            <div>
                <label>FiO&sup2; %</label>
                <input type="text" name="txtFio2" id="txtFio2" alt="numeromask" class="size1" />
            </div>
            <div>
                <label>Peso</label>
                <? if($pesotemperatura[0]->peso != ''){ ?>
                <input type="text" name="txtPeso" id="txtPeso" alt="numeromask" value="<?=$pesotemperatura[0]->peso?>" class="size1" readonly/>
                <? } else { ?>
                <input type="text" name="txtPeso" id="txtPeso" alt="numeromask" class="size1" />
                <?}?>
            </div>
            <div>
                <label>Temperatura</label>
                <? if($pesotemperatura[0]->temperatura != ''){ ?>
                <input type="text" name="txtTemperatura" id="txtTemperatura" alt="numeromask" value="<?=$pesotemperatura[0]->temperatura?>" class="size1" readonly/>
                <? } else { ?>
                <input type="text" name="txtTemperatura" id="txtTemperatura" alt="numeromask" class="size1" />
                <?}?>
            </div>
            <div> 
                <label>Plano terapeutico imediato</label>
                <input type="text" name="txtPlanoTerapeuticoImediato" id="txtPlanoTerapeuticoImediato" class="size2" />
            </div>
                        
            <div>
                <label>Frequencia Repiratoria</label>
                <input type="text" name="txtFrequenciaRespiratoria" alt="numeromask" id="txtFrequenciaRespiratoria" class="size2" />
            </div>
            
            <div>
            <div>
                <label>PA Sist</label>
                <input type="text" name="txtPASist" id="txtPASist" alt="numeromask" class="size2" />
            </div>
            <div>
                <label>PA Diast</label>
                <input type="text" name="txtPADiast" id="txtPADiast" alt="numeromask" class="size2" />
            </div>
            <div>
                <label>Pulso</label>
                <input type="text" name="txtPulso" id="txtPulso" alt="numeromask" class="size2" />
            </div>
            </div>
            
            
            <div>
            <div>
                <label>CID principal</label>
                <input type="hidden" id="txtcid1ID" class="texto_id" name="cid1ID" value="<?= @$obj->_cid1; ?>" />
                <input type="text" id="txtcid1" class="texto10" name="txtcid1" value="<?= @$obj->_cid1_nome; ?>" />
            </div>
            <div>
                <label>CID secundario</label>
                <input type="hidden" id="txtcid2ID" class="texto_id" name="cid2ID" value="<?= @$obj->_cid2; ?>" />
                <input type="text" id="txtcid2" class="texto10" name="txtcid2" value="<?= @$obj->_cid2_nome; ?>" />
            </div>
            <div>
                <label>Procedimento</label>
                <input type="hidden" id="txtprocedimentoID" class="texto_id" name="procedimentoID" value="<?= @$obj->_procedimento_id; ?>" />
                <input type="text" id="txtprocedimento" class="texto10" name="txtprocedimento" value="<?= @$obj->_procedimento_nome; ?>" />
            </div>
            <div>
                <label>Diagnostico</label>
                <textarea cols="" rows="" name="txtdiagnostico" id="txtdiagnostico" value="" class="texto_area"></textarea>
            </div>
            <div>
                <label>Conduta</label>
                <textarea cols="" rows="" name="txtconduta" id="txtconduta" value="" class="texto_area"></textarea>
            </div>
            </div>    
        </fieldset>
        <button type="submit" name="btnEnviar">Enviar</button>
        <button type="reset" name="btnLimpar">Limpar</button>
    </form>
</div>
<div class="clear"></div>

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(function() {
        $( "#txtcid1" ).autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cid1",
            minLength: 2,
            focus: function( event, ui ) {
                $( "#txtcid1" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $( "#txtcid1" ).val( ui.item.value );
                $( "#txtcid1ID" ).val( ui.item.id );
                return false;
            }
        });
    });
    
    $(function() {
        $( "#txtprocedimento" ).autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=procedimento",
            minLength: 2,
            focus: function( event, ui ) {
                $( "#txtprocedimento" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $( "#txtprocedimento" ).val( ui.item.value );
                $( "#txtprocedimentoID" ).val( ui.item.id );
                return false;
            }
        });
    });
    

    $(function() {
        $( "#txtcid2" ).autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=cid2",
            minLength: 2,
            focus: function( event, ui ) {
                $( "#txtcid2" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $( "#txtcid2" ).val( ui.item.value );
                $( "#txtcid2ID" ).val( ui.item.id );
                return false;
            }
        });
    });
    
        $(document).ready(function(){
        jQuery('#evolucao_form').validate( {
            rules: {
                txtIdade: {
                    required: true
                },
                txtcid1: {
                    required: true
                },
                txtCICsecundariolabel: {
                    required: true
                },
                txtPlanoTerapeuticoImediato: {
                    required: true
                },
                txtSaturacao: {
                    required: true
                },
                txtFio2: {
                    required: true
                },
                txtFrequenciaRespiratoria: {
                    required: true
                },
                txtPASist: {
                    required: true
                },
                txtPADiast: {
                    required: true
                },
                txtPulso: {
                    required: true
                },
                txtPeso: {
                    required: true
                },
                txtTemperatura: {
                    required: true
                },
                txtdiagnostico: {
                    required: true
                },
                txtprocedimento: {
                    required: true
                },
                txtconduta: {
                    required: true
                }
            },
            messages: {
                txtIdade: {
                    required: "*"
                },
                txtCICPrimariolabel: {
                    required: "*"
                },
                txtCICsecundariolabel: {
                    required: "*"
                },
                txtPlanoTerapeuticoImediato: {
                    required: "*"
                },
                txtSaturacao: {
                    required: "*"
                },
                txtFio2: {
                    required: "*"
                },
                txtFrequenciaRespiratoria: {
                    required: "*"
                },
                txtPASist: {
                    required: "*"
                },
                txtPADiast: {
                    required: "*"
                },
                txtPulso: {
                    required: "*"
                },
                txtPeso: {
                    required: "*"
                },
                txtTemperatura: {
                    required: "*"
                },
                txtdiagnostico: {
                    required: "*"
                },
                txtprocedimento: {
                    required: "*"
                },
                txtconduta: {
                    required: "*"
                }
            }
        });
    });
</script>