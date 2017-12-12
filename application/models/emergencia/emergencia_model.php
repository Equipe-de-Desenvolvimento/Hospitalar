<?php

    require_once APPPATH . 'models/base/BaseModel.php';

    class Emergencia_model extends BaseModel {

        /* Propriedades da classe */
        var $_ficha_id = null;
        var $_be = null;
        var $_leito = null;
        var $_nome = null;
        var $_solicitacao = null;
        var $_reserva = null;
        var $_laudo = null;
        var $_data_nascimento = null;
        var $_data = null;
        var $_protuario = null;


        /* Método construtor */
    function Emergencia_model($ficha_id=null) {
            parent::Model();
            
            if (isset ($ficha_id))
            { $this->instanciar($ficha_id); }
         }

    function totalRegistros($parametro) {
        
            $this->db->select('ficha_id');
            $this->db->from('tb_emergencia_ficha');
            if ($parametro != null && $parametro != -1)
            {
                $this->db->where('nome ilike', $parametro . "%");
                $this->db->orwhere('be ilike', $parametro . "%");
            }
            $return = $this->db->count_all_results();
            return $return;
        }

    function listarGrupoResposta($classeresposta) {
            $sql = "SELECT 
                    gruporesposta_id,
                    descricao
                    FROM ijf.tb_emergencia_gruporesposta
                    WHERE classeresposta_id = $classeresposta
                    ORDER BY descricao
            ";

     return $this->db->query($sql)->result();
     }

     function retornaEmail() {

            $this->db->select();
            $this->db->from('tb_emergencia_confirma_email');
            $this->db->where('data', date('Y-m-d'));
            $return = $this->db->count_all_results();
             
            return $return;
     }

     function inserirEmail() {
           $data = date('d/m/Y');
           $sql = "insert into ijf.tb_emergencia_confirma_email(operador_id,data,hora) values
          (".$this->session->userdata('operador_id').",'".$data."','".date('H:i:s')."')";
     $this->db->query($sql);
     }

    function listarLeito() {
            $sql = "SELECT
                    leito_id,
                    nome
                    FROM ijf.tb_leitos
                    ORDER BY nome
            ";

     return $this->db->query($sql)->result();
     }

    function cid10($parametro=null) {
        $this->db->select('co_cid,
                            no_cid');
        if ($parametro != null) {
            $this->db->where('co_cid ilike', $parametro . "%");
            $this->db->orwhere('no_cid ilike', $parametro . "%");
        }
        $this->db->from('tb_cid');
        $return = $this->db->get();
        return $return->result();
    }
    

    function listarEvolucao($ficha_id) {
        
            $this->db->select('emergencia_evolucao_id as evolucao_id,
                                ficha_id,
                                cid_pri,
                                plano_terapeutico_imediato');
            $this->db->from('tb_emergencia_evolucao');
            $this->db->where('ficha_id', $ficha_id);
            $this->db->where('ativo', 't');
            $this->db->orderby('emergencia_evolucao_id');
            $return = $this->db->get();

            return $return->result();
            
    }
    
    function evolucao($emergencia_rae_id) {
            $this->db->select('r.emergencia_rae_id,
                                       r.paciente_id,
                                       r.local_ocorrencia,
                                       r.veiculo_ocorrencia,
                                       r.descricao_ocorrencia,
                                       r.sitomas_atendimento,
                                       r.tipo_atendimento,
                                       r.escala_dor,
                                       r.pas,
                                       r.pad,
                                       r.fr,
                                       r.so2,
                                       r.classificacao,
                                       r.escala_dor,
                                       r.data_cadastro,
                                       r.peso,
                                       r.temperatura,
                                       p.nome as paciente,
                                       p.municipio_id,
                                       p.sexo,
                                       p.cep,
                                       p.nascimento,
                                       p.nome_mae,
                                       p.raca_cor,
                                       p.logradouro,
                                       p.numero,
                                       p.rg,
                                       p.nomeresp,
                                       p.telefoneresp,
                                       p.complemento,
                                       m.estado,
                                       m.nome as municipio,
                                       m.codigo_ibge,
                                       rc.raca_cor_id,
                                       rc.nome as raca,
                                       sa.paciente_id,
                                       sa.gravidade,
                                       sa.operador_atendimento,
                                       o.nome as operador,
                                       e.frequencia_respiratoria,
                                       e.plano_terapeutico_imediato as anamnase,
                                       e.pa_diast,
                                       e.pa_sist,
                                       e.diagnostico,
                                       e.procedimento,
                                       e.conduta,
                                       e.cid_pri as cid,
                                       pr.procedimento as cod');
            $this->db->from('tb_emergencia_rae r');
            $this->db->where('r.emergencia_rae_id', $emergencia_rae_id);
            $this->db->join('tb_paciente p', 'p.paciente_id = r.paciente_id');
            $this->db->join('tb_municipio m', 'm.municipio_id = p.municipio_id');
            $this->db->join('tb_raca_cor rc', 'rc.raca_cor_id = p.raca_cor');
            $this->db->join('tb_emergencia_solicitacao_acolhimento sa', 'p.paciente_id = sa.paciente_id');
            $this->db->join('tb_operador o', 'sa.operador_atendimento = o.operador_id');
            $this->db->join('tb_emergencia_evolucao e', 'r.emergencia_rae_id = e.ficha_id');
            $this->db->join('tb_procedimento pr', 'pr.procedimento_id = e.procedimento');
            $return = $this->db->get();
            return $return->result();
            
    }
    
    function empresa() {
       $empresa= $this->session->userdata('empresa_id'); 
        $this->db->select('empresa_id,
                            nome,
                            cnpj,
                            razao_social,
                            logradouro,
                            bairro,
                            telefone,
                            numero');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $empresa);
        $return = $this->db->get();
            return $return->result();
    }
                

    function listarSolicitacoesParecer($parametro=null) {
            $this->db->select('sp.solicitacao_id,
                                       sp.evolucao_id,
                                       sp.parecer_id,
                                       sp.datasolicitacao,
                                       sp.horasolicitacao,
                                       sp.descricao as detalhes,
                                       f.nome,
                                       l.nome as leito,
                                       g.descricao as especialidade,
                                       sp.prioridade');
            $this->db->from('tb_emergencia_solicitacao_parecer sp');
            $this->db->where('sp.dataatendida is null');
            $this->db->join('tb_emergencia_evolucao e', 'e.evolucao_id = sp.evolucao_id');
            $this->db->join('tb_leitos l', 'l.leito_id = e.leito');
            $this->db->join('tb_emergencia_ficha f', 'f.ficha_id = e.ficha_id');
            $this->db->join('tb_emergencia_gruporesposta g', 'g.gruporesposta_id = sp.gruporesposta_id');
            
            if ($parametro != null && $parametro!= -1)
            {                
                $this->db->where('g.descricao ilike', $parametro . "%");
                $this->db->orwhere('f.nome ilike', $parametro . "%");
            }
            $this->db->orderby('sp.prioridade');
            $this->db->orderby('sp.datasolicitacao');
            $this->db->orderby('sp.prioridade');
            $return = $this->db->get();
            $erro = $this->db->_error_message();
            return $return->result();

    }

    function listarSolicitacoesParecerRelatorio() {
            $this->db->select('sp.solicitacao_id,
                                       sp.evolucao_id,
                                       sp.parecer_id,
                                       sp.datasolicitacao,
                                       sp.horasolicitacao,
                                       sp.descricao as detalhes,
                                       f.nome,
                                       l.nome as leito,
                                       g.descricao as especialidade,
                                       sp.prioridade');
            $this->db->from('tb_emergencia_solicitacao_parecer sp');
            $this->db->where('sp.dataatendida is null');
            $this->db->join('tb_emergencia_evolucao e', 'e.evolucao_id = sp.evolucao_id');
            $this->db->join('tb_leitos l', 'l.leito_id = e.leito');
            $this->db->join('tb_emergencia_ficha f', 'f.ficha_id = e.ficha_id');
            $this->db->join('tb_emergencia_gruporesposta g', 'g.gruporesposta_id = sp.gruporesposta_id');
            $this->db->orderby('sp.prioridade');
            $this->db->orderby('sp.datasolicitacao');
            $this->db->orderby('sp.prioridade');
            $return = $this->db->get();
            $erro = $this->db->_error_message();
            return $return->result();

    }
    
    function relatorioEvolucao($evolucao_id) {
        
            $this->db->select(' ee.emergencia_evolucao_id,
                                ee.ficha_id,
                                ee.cid_pri,
                                ee.cid_sec,
                                ee.plano_terapeutico_imediato,
                                ee.saturacao,
                                ee.fio2,
                                ee.frequencia_respiratoria,
                                ee.pa_sist,
                                ee.pa_diast,
                                ee.pulso,
                                ee.medico_id,
                                ee.diagnostico,
                                ee.conduta,
                                pr.procedimento,
                                o.nome,
                                c.no_cid,
                                er.paciente_id,
                                er.peso,
                                er.temperatura,
                                er.fr,
                                er.pad,
                                p.nome as paciente,
                                p.municipio_id,
                                p.sexo,
                                p.cep,
                                p.nascimento,
                                p.nome_mae,
                                p.raca_cor,
                                p.logradouro,
                                p.numero,
                                p.rg,
                                p.nomeresp,
                                p.telefoneresp,
                                p.complemento,
                                m.estado,
                                m.nome as municipio,
                                m.codigo_ibge,
                                rc.raca_cor_id,
                                rc.nome as raca
                                ');
            $this->db->from('tb_emergencia_evolucao ee');
            $this->db->where('ee.ativo', 't');
            $this->db->where('ee.emergencia_evolucao_id', $evolucao_id);
            $this->db->join('tb_operador o', 'o.operador_id = ee.medico_id', 'left');
            $this->db->join('tb_cid c', 'c.co_cid = ee.cid_pri', 'left');
            $this->db->join('tb_emergencia_rae er', 'er.emergencia_rae_id = ee.ficha_id', 'left');
            $this->db->join('tb_paciente p', 'p.paciente_id = er.paciente_id', 'left');
            $this->db->join('tb_municipio m', 'm.municipio_id = p.municipio_id', 'left');
            $this->db->join('tb_raca_cor rc', 'rc.raca_cor_id = p.raca_cor', 'left');
            $this->db->join('tb_procedimento pr', 'pr.procedimento_id = ee.procedimento', 'left');

            $return = $this->db->get();
            
            return $return->result();

    }

    function listarSolicitacoesCirurgia($parametro=null) {
            
            $this->db->select('sc.cirurgia_id,
                                       sc.evolucao_id,
                                       sc.parecer_id,
                                       sc.datasolicitacao,
                                       sc.horasolicitacao,
                                       f.nome,
                                       g.descricao as especialidade,
                                       sc.prioridade');
            $this->db->from('tb_emergencia_solicitacao_cirurgia sc');
            $this->db->where('sc.dataatendida is null');
            $this->db->join('tb_emergencia_evolucao e', 'e.evolucao_id = sc.evolucao_id');
            $this->db->join('tb_emergencia_ficha f', 'f.ficha_id = e.ficha_id');
            $this->db->join('tb_emergencia_gruporesposta g', 'g.gruporesposta_id = sc.gruporesposta_id');
            if ($parametro != null && $parametro!= -1)
            {     
                $this->db->where('g.descricao ilike', $parametro . "%");
                $this->db->orwhere('f.nome ilike', $parametro . "%");
            }
            $this->db->orderby('sc.prioridade');
            $this->db->orderby('sc.datasolicitacao');
            $this->db->orderby('sc.horasolicitacao');
            $return = $this->db->get();
            return $return->result();

    }
    function listarpesotemperatura($rae_id) {
            
            $this->db->select('peso,
                               temperatura ');
            $this->db->from('tb_emergencia_rae');
            $this->db->where('emergencia_rae_id', $rae_id);
            $return = $this->db->get();
            return $return->result();
    }

    function listarSolicitacoesTomografia($parametro=null) {

            $this->db->select('st.tomografia_id,
                                       st.evolucao_id,
                                       st.parecer_id,
                                       st.datasolicitacao,
                                       st.horasolicitacao,
                                       f.nome,
                                       g.descricao as especialidade,
                                       st.prioridade');
            $this->db->from('tb_emergencia_solicitacao_tomografia st');
            $this->db->where('st.dataatendida is null');
            $this->db->join('tb_emergencia_evolucao e', 'e.evolucao_id = st.evolucao_id');
            $this->db->join('tb_emergencia_ficha f', 'f.ficha_id = e.ficha_id');
            $this->db->join('tb_emergencia_gruporesposta g', 'g.gruporesposta_id = st.gruporesposta_id');
            if ($parametro != null && $parametro!= -1)
            {     
                $this->db->where('g.descricao ilike', $parametro . "%");
                $this->db->orwhere('f.nome ilike', $parametro . "%");
            }
            $this->db->orderby('st.prioridade');
            $this->db->orderby('st.datasolicitacao');
            $this->db->orderby('st.horasolicitacao');
            $return = $this->db->get();
            return $return->result();

    }

    function relatorioParecer($evolucao_id, $parecer_id) {
            $sql= "select p.descricao, p.conduta, p.evolucao_id, p.tempoconduta, p.dataparecer, p.horaparecer, s.gruporesposta_id, gr.descricao as especialidadesolicitada, g.descricao as especialidade, f.nome,
                                       p.tempoconduta from ijf.tb_emergencia_parecer p
                                       join ijf.tb_emergencia_evolucao e on e.evolucao_id = p.evolucao_id
                                       join ijf.tb_emergencia_solicitacao_parecer s on s.solicitacao_id = p.solicitacao_id
                                       join ijf.tb_emergencia_gruporesposta gr on gr.gruporesposta_id = s.gruporesposta_id
				       join ijf.tb_emergencia_ficha f on f.ficha_id = e.ficha_id
				       join ijf.tb_emergencia_gruporesposta g on g.gruporesposta_id = p.conduta
				        where p.evolucao_id = $evolucao_id
                                        and p.parecer_id = $parecer_id";

            return $this->db->query($sql)->result();

    }

    function listaParecer($evolucao_id) {
            $sql= "select p.parecer_id, p.descricao, p.conduta, p.evolucao_id, sp.prioridade, sp.datasolicitacao, sp.horasolicitacao, g.descricao as especialidade, f.nome,
                                       p.tempoconduta 
                                       from ijf.tb_emergencia_solicitacao_parecer sp
                                       join ijf.tb_emergencia_evolucao e on e.evolucao_id = sp.evolucao_id
				       join ijf.tb_emergencia_ficha f on f.ficha_id = e.ficha_id
				       left join ijf.tb_emergencia_parecer p on p.solicitacao_id = sp.solicitacao_id
				       join ijf.tb_emergencia_gruporesposta g on g.gruporesposta_id = sp.gruporesposta_id
				       where sp.evolucao_id = $evolucao_id
                                       order by sp.datasolicitacao, sp.horasolicitacao";
            
            return $this->db->query($sql)->result();
    }

    function gravarFicha($args = array()){

                $args['operador_id'] = ($this->session->userdata('operador_id'));
                $args['be'] = $_POST['txtBe'];
                $args['protuario'] = $_POST['txtProtuario'];
                $args['data'] = date("d/m/Y");
                $args['nome'] = $_POST['txtNome'];
                $args['solicitacao'] = $_POST['txtSolicitacao'];
                $args['reserva'] = $_POST['txtReserva'];
                $args['laudo'] = $_POST['txtLaudo'];
                if (($_POST['txtDataNascimento'] != '//'))
                    {$args['data_nascimento'] = $_POST['txtDataNascimento'];
                    }else {
                        $args['data_nascimento'] = null;
                   }

                $this->db->insert('tb_emergencia_ficha', $args);
                return $this->db->insert_id();
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
                

        }

    function gravarEvolucao($ficha_id){
                $operador_id = ($this->session->userdata('operador_id'));

                $this->db->set('ficha_id', $ficha_id);
                $this->db->set('operador_id', $operador_id);
                $this->db->set('cid_pri', $_POST['cid1ID']);
                $this->db->set('cid_sec', $_POST['cid2ID']);
                $this->db->set('plano_terapeutico_imediato', $_POST['txtPlanoTerapeuticoImediato']);
                $this->db->set('saturacao', $_POST['txtSaturacao']);
                $this->db->set('fio2', $_POST['txtFio2']);
                $this->db->set('frequencia_respiratoria', $_POST['txtFrequenciaRespiratoria']);
                $this->db->set('pulso', $_POST['txtPulso']);
                $this->db->set('pa_diast', $_POST['txtPADiast']);
                $this->db->set('diagnostico', $_POST['txtdiagnostico']);
                $this->db->set('conduta', $_POST['txtconduta']);
                $this->db->set('procedimento', $_POST['procedimentoID']);
                $this->db->set('pa_sist', $_POST['txtPASist']);
                $this->db->set('medico_id', $operador_id);
                $this->db->insert('tb_emergencia_evolucao');
                $this->db->insert_id();
                
                $this->db->set('peso', $_POST['txtPeso']);
                $this->db->set('temperatura', $_POST['txtTemperatura']);
                $this->db->where('emergencia_rae_id', $ficha_id);
                $this->db->update('tb_emergencia_rae');
//                $sql = "INSERT INTO ijf.tb_emergencia_evolucao (ficha_id, operador_id, leito, medico_id, cid_pri, cid_sec1, cid_sec2, cid_sec3, plano_terapeutico_imediato,
//                saturacao, fio2, frequencia_respiratoria, pa_sist, pa_diast, pulso, ramsay, glasgow) VALUES
//                ($ficha_id, $operador_id, $leito, $medico_id, '$cidprimario', '$cidsecundario1', '$cidsecundario2', '$cidsecundario3', '$plano_terapeutico_imediato',
//                '$saturacao', '$fio2', '$frequencia_respiratoria', '$pasist', '$padiast', '$pulso', '$ramsay', '$glasgow')";
                //$this->db->query($sql);
                
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
    }

    function gravarResposta($ficha_id, $evolucao_id){

            if ( $_POST['txtCenso']!= '') {
                $censo = $_POST['txtCenso'];
                $sql = "INSERT INTO ijf.tb_emergencia_resposta
                       (ficha_id, evolucao_id, gruporesposta_id) VALUES
                       ($ficha_id, $evolucao_id, $censo)";
                $this->db->query($sql);
                $erro = $this->db->_error_message();
            }
            if ($_POST['txtclassificassao']!='') {
                $urgencia = $_POST['txtUrgencia'];
                $sql = "INSERT INTO ijf.tb_emergencia_resposta
                       (ficha_id, evolucao_id, gruporesposta_id) VALUES
                       ($ficha_id, $evolucao_id, $urgencia)";
                $this->db->query($sql);
                $erro = $this->db->_error_message();
            }
            if ($_POST['txtTipo']!= '') {
                $tipo = $_POST['txtTipo'];
                $sql = "INSERT INTO ijf.tb_emergencia_resposta
                       (ficha_id, evolucao_id, gruporesposta_id) VALUES
                       ($ficha_id, $evolucao_id, $tipo)";
                $this->db->query($sql);
                $erro = $this->db->_error_message();
            }
            if ($_POST['txtViasAereas']!='') {
                $viasaereas = $_POST['txtViasAereas'];
                $sql = "INSERT INTO ijf.tb_emergencia_resposta
                       (ficha_id, evolucao_id, gruporesposta_id) VALUES
                       ($ficha_id, $evolucao_id, $viasaereas)";
                $this->db->query($sql);
                $erro = $this->db->_error_message();
            }
            $arrayinfusaodrogas = $_POST['txtInfusaoDrogas'];
            $arraydose = $_POST['txtDose'];
          $i=-1;
          foreach ($arrayinfusaodrogas as $agentetoxico){
            $z=-1;
            $i++;
             if ($agentetoxico!='-1') {
                foreach ($arraydose as $itemdose){
                        $z++;
                        if ($i == $z){
                            $dose = $itemdose;
                        }
                    }
                $sql = "INSERT INTO ijf.tb_emergencia_resposta
                       (ficha_id, evolucao_id, gruporesposta_id, dose) VALUES
                       ($ficha_id,$evolucao_id,$agentetoxico,$dose)";
                $this->db->query($sql);

          }
          }
          return true;

     }

    function gravarParecer(){
                $operador_id = ($this->session->userdata('operador_id'));
                $solicitacao = $_POST['txtSolicitacao'];
                $evolucao_id = $_POST['txtEvolucao_id'];
                $descricao = $_POST['txtDescricao'];
                $conduta = $_POST['txtConduta'];
                $tempo = $_POST['txtTempo'];
                $data = $_POST['txtdata'];
                $hora = $_POST['txthora'];
                $dataatual = date('d/m/Y');
                $horaatual = date('H:i');
                $medico_id = $_POST['txtmedico'];


                $sql = "INSERT INTO ijf.tb_emergencia_parecer (descricao, medico_id, operador_id, evolucao_id, conduta, tempoconduta, solicitacao_id, dataparecer, horaparecer) VALUES
                ('$descricao', $medico_id, $operador_id, $evolucao_id, '$conduta','$tempo',$solicitacao, '$data', '$hora')";
                
                $this->db->query($sql);
                $parecer_id = $this->db->insert_id();
                
                $sql=("UPDATE ijf.tb_emergencia_solicitacao_parecer SET dataatendida='$dataatual', horaatendida='$horaatual' WHERE solicitacao_id= $solicitacao");
                $this->db->query($sql);
                $erro = $this->db->_error_message();
                


                if ($conduta == "74"){

                    $sql = "insert into ijf.tb_emergencia_solicitacao_parecer
                    (gruporesposta_id, parecer_id, evolucao_id, datasolicitacao, horasolicitacao, prioridade)
                    select sp.gruporesposta_id, $parecer_id, sp.evolucao_id, '$dataatual', '$horaatual', '$tempo' from ijf.tb_emergencia_solicitacao_parecer sp
                    where sp.solicitacao_id = $solicitacao";

                    $this->db->query($sql);
                    $erro = $this->db->_error_message();
                }

                if ($conduta == "75"){

                    $sql = "insert into ijf.tb_emergencia_solicitacao_tomografia
                    (gruporesposta_id, parecer_id, evolucao_id, datasolicitacao, horasolicitacao, prioridade)
                    select sp.gruporesposta_id, $parecer_id, sp.evolucao_id, '$dataatual', '$horaatual', '$tempo' from ijf.tb_emergencia_solicitacao_parecer sp
                    where sp.solicitacao_id = $solicitacao";

                    $this->db->query($sql);
                    $erro = $this->db->_error_message();
                }

                if ($conduta == "70" || $conduta == "71"){

                    $sql = "insert into ijf.tb_emergencia_solicitacao_cirurgia
                    (gruporesposta_id, parecer_id, evolucao_id, datasolicitacao, horasolicitacao, prioridade)
                    select sp.gruporesposta_id, $parecer_id, sp.evolucao_id, '$dataatual', '$horaatual', '$tempo' from ijf.tb_emergencia_solicitacao_parecer sp
                    where sp.solicitacao_id = $solicitacao";

                    $this->db->query($sql);
                    $erro = $this->db->_error_message();
                }


                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }

        }

    function saidaCirurgia(){
                $operador_id = ($this->session->userdata('operador_id'));

                $cirurgia = $_POST['txtcirurgia'];

                if (($_POST['conduta'] == '81'))
                    {$this->db->set('prioridade', $_POST['conduta_adiamento']);
                }else if (($_POST['conduta'] == '80'))
                    {$this->db->set('prioridade', $_POST['conduta_transferencia']);
                }
                $this->db->set('conduta', $_POST['conduta']);
                if (($_POST['conduta'] != '81')) {$this->db->set('dataatendida', date('d/m/Y'));}
                if (($_POST['conduta'] != '81')) {$this->db->set('horaatendida', date('H:i'));}
                $this->db->set('descricao', $_POST['conduta_desc']);
                $this->db->set('operador_id', $operador_id);
                $this->db->where('cirurgia_id', $cirurgia);
                $this->db->update('tb_emergencia_solicitacao_cirurgia');
                
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
        }

    function prioridadeparecer(){
                $operador_id = ($this->session->userdata('operador_id'));
                $solicitacao = $_POST['txtsolicitacao'];
                $this->db->set('prioridade', $_POST['conduta_adiamento']);
                $this->db->set('descricao', $_POST['conduta_desc']);
                $this->db->set('operador_id', $operador_id);
                $this->db->where('solicitacao_id', $solicitacao);
                $this->db->update('tb_emergencia_solicitacao_parecer');

                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
        }

    function prioridadetomografia(){
        
                $operador_id = ($this->session->userdata('operador_id'));
                $tomografia = $_POST['txttomografia'];
                $this->db->set('prioridade', $_POST['conduta_adiamento']);
                $this->db->set('descricao', $_POST['conduta_desc']);
                $this->db->set('operador_id', $operador_id);
                $this->db->where('tomografia_id', $tomografia);
                $this->db->update('tb_emergencia_solicitacao_tomografia');

                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
        }
        
    function excluirevolucao($evolucao_id){
                $this->db->set('ativo', 'f');
                $this->db->where('emergencia_evolucao_id', $evolucao_id);
                $this->db->update('tb_emergencia_evolucao');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
        }

    function gravarTomografia($tomografia_id){
                $operador_id = ($this->session->userdata('operador_id'));
                $dataatual = date('d/m/Y');
                $horaatual = date('H:i');

                $sql=("UPDATE ijf.tb_emergencia_solicitacao_tomografia SET dataatendida='$dataatual', horaatendida='$horaatual', operador_id = $operador_id WHERE tomografia_id= $tomografia_id");
                $this->db->query($sql);

                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                { return false; }
                else
                { return true; }
        }

    function listarFichas($args = array()){

        $this->db->select('f.ficha_id,
                        f.be,
                        f.data,
                        f.nome,
                        f.data_nascimento,
                        f.protuario');
        $this->db->from('tb_emergencia_ficha f');
//        $this->db->orderby('nome');
//        $this->db->groupby('ficha_id, nome, be, data, data_nascimento, protuario');
        
                    if ($args) {
                        if (isset ($args['nome']) && strlen($args['nome']) > 0) {
                            $this->db->where('f.nome ilike', $args['nome'] . "%", 'left');
                            $this->db->orwhere('f.be ilike', $args['nome'] . "%", 'left');
                        }
                    }

                    return $this->db;
  }

    function instanciar($ficha_id){
             if ($ficha_id != 0) {
            $this->db->select('f.ficha_id,
                        f.be,
                        f.data,
                        f.leito,
                        f.nome,
                        f.data_nascimento,
                        f.protuario');
            $this->db->from('tb_emergencia_ficha');
            $this->db->where("ficha_id", $ficha_id);
            $query = $this->db->get();
            $return = $query->result();

            $this->_ficha_id = $return[0]->ficha_id;
            $this->_be = $return[0]->be;
            $this->_data = $return[0]->data;
            $this->_leito = $return[0]->leito;
            $this->_nome = $return[0]->nome;
            $this->_data_nascimento = $return[0]->data_nascimento;
            $this->_protuario = $return[0]->protuario;
            } else  {
            $this->_ficha_id = null;
            }


  }

    function gravarRespostaSolicitacaoParecer($ficha_id, $evolucao_id){
      
          $i=-1;

          foreach ($_POST['parecer'] as $parecer){
            $z= -1;
            $x= -1;
            $y= -1;
            $w= -1;
                if ($parecer!='-1') {
                    foreach ($_POST['datasolicitacaoparecer'] as $itemdatasolicitacaoparecer){

                        if ($i == $z){
                            $datasolicitacaoparecer = $itemdatasolicitacaoparecer;
                            break;
                        }
                        $z++;
                    }
                    foreach ($_POST['horasolicitacaoparecer'] as $itemhorasolicitacaoparecer){
                        
                        if ($i == $x){
                            $horasolicitacaoparecer = $itemhorasolicitacaoparecer;
                            break;
                        }
                        $x++;
                    }
                    foreach ($_POST['txtUrgencia'] as $itemurgencia){
                        
                        if ($i == $y){
                            $urgencia = $itemurgencia;
                            break;
                        }
                        $y++;
                    }
                    foreach ($_POST['txtDescricao'] as $itemdescricao){

                        if ($i == $w){
                            $descricao = $itemdescricao;
                            break;
                        }
                        $w++;
                    }
            $i++;


                $sql = "INSERT INTO ijf.tb_emergencia_solicitacao_parecer
                       (gruporesposta_id, evolucao_id, datasolicitacao, horasolicitacao, prioridade, descricao) VALUES
                       ($parecer, $evolucao_id, '$datasolicitacaoparecer', '$horasolicitacaoparecer', '$urgencia', '$descricao')";
                $this->db->query($sql);
          }
          }
          return true;
    }

    function listarMedico($parametro=null) {
        $this->db->select('medico_id,
                            nome,
                            crm');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%". $parametro . "%");
            $this->db->orwhere('crm ilike', $parametro . "%");
        }
        $this->db->from('tb_medico');
        $return = $this->db->get();
        return $return->result();
    }

//    function gravarRespostaSolicitacaoexame($ficha_id, $evolucao_id){
//
//          $i=-1;
//
//          foreach ($_POST['exame'] as $exame){
//            $z=-1;
//            $x=-1;
//            $y=-1;
//            $w=-1;
//            $p=-1;
//            $q=-1;
//            $r=-1;
//            $s=-1;
//            $i++;
//                    foreach ($_POST['datasolicitacaoexame'] as $itemdatasolicitacaoexame){
//                        $z++;
//                        if ($i == $z){
//                            $datasolicitacaoexame = $itemdatasolicitacaoexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horasolicitacaoexame'] as $itemhorasolicitacaoexame){
//                        $x++;
//                        if ($i == $x){
//                            $horasolicitacaoexame = $itemhorasolicitacaoexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['dataultimasolicitacaoexame'] as $itemdataultimasolicitacaoexame){
//                        $y++;
//                        if ($i == $y){
//                            $dataultimasolicitacaoexame = $itemdataultimasolicitacaoexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horaultimasolicitacaoexame'] as $itemhoraultimasolicitacaoexame){
//                        $w++;
//                        if ($i == $w){
//                            $horaultimasolicitacaoexame = $itemhoraultimasolicitacaoexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['dataprevistaexame'] as $itemdataprevistaexame){
//                        $p++;
//                        if ($i == $p){
//                            $dataprevistaexame = $itemdataprevistaexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horaprevistaexame'] as $itemhoraprevistaexame){
//                        $q++;
//                        if ($i == $q){
//                            $horaprevistaexame = $itemhoraprevistaexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['dataaltaexame'] as $itemdataaltaexame){
//                        $r++;
//                        if ($i == $r){
//                            $dataaltaexame = $itemdataaltaexame;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horaaltaexame'] as $itemhoraaltaexame){
//                        $s++;
//                        if ($i == $s){
//                            $horaaltaexame = $itemhoraaltaexame;
//                            break;
//                        }
//                    }
//
//
//                $sql = "INSERT INTO ijf.tb_emergencia_resposta_solicitacao
//                       (gruporesposta_id,ficha_id, evolucao_id, datasolicitacao, horasolicitacao, dataultimasolicitacao, horaultimasolicitacao,
//                        dataprevista, horaprevista, dataalta, horaalta) VALUES
//                       ($exame,$ficha_id, $evolucao_id, '$datasolicitacaoexame', '$horasolicitacaoexame', '$dataultimasolicitacaoexame', '$horaultimasolicitacaoexame',
//                        '$dataprevistaexame', '$horaprevistaexame', '$dataaltaexame', '$horaaltaexame')";
//                $this->db->query($sql);
//                $erro = $this->db->_error_message();
//                if (trim($erro) != "") // erro de banco
//                { return false; }
//                else
//                { return true;
//             }
//          }
//    }
//
//    function gravarRespostaSolicitacaolaboratorial($ficha_id, $evolucao_id){
//
//          $i=-1;
//
//          foreach ($_POST['laboratorial'] as $laboratorial){
//            $z=-1;
//            $x=-1;
//            $y=-1;
//            $w=-1;
//            $p=-1;
//            $q=-1;
//            $r=-1;
//            $s=-1;
//            $i++;
//                    foreach ($_POST['datasolicitacaolaboratorial'] as $itemdatasolicitacaolaboratorial){
//                        $z++;
//                        if ($i == $z){
//                            $datasolicitacaolaboratorial = $itemdatasolicitacaolaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horasolicitacaolaboratorial'] as $itemhorasolicitacaolaboratorial){
//                        $x++;
//                        if ($i == $x){
//                            $horasolicitacaolaboratorial = $itemhorasolicitacaolaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['dataultimasolicitacaolaboratorial'] as $itemdataultimasolicitacaolaboratorial){
//                        $y++;
//                        if ($i == $y){
//                            $dataultimasolicitacaolaboratorial = $itemdataultimasolicitacaolaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horaultimasolicitacaolaboratorial'] as $itemhoraultimasolicitacaolaboratorial){
//                        $w++;
//                        if ($i == $w){
//                            $horaultimasolicitacaolaboratorial = $itemhoraultimasolicitacaolaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['dataprevistalaboratorial'] as $itemdataprevistalaboratorial){
//                        $p++;
//                        if ($i == $p){
//                            $dataprevistalaboratorial = $itemdataprevistalaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horaprevistalaboratorial'] as $itemhoraprevistalaboratorial){
//                        $q++;
//                        if ($i == $q){
//                            $horaprevistalaboratorial = $itemhoraprevistalaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['dataaltalaboratorial'] as $itemdataaltalaboratorial){
//                        $r++;
//                        if ($i == $r){
//                            $dataaltalaboratorial = $itemdataaltalaboratorial;
//                            break;
//                        }
//                    }
//                    foreach ($_POST['horaaltalaboratorial'] as $itemhoraaltalaboratorial){
//                        $s++;
//                        if ($i == $s){
//                            $horaaltalaboratorial = $itemhoraaltalaboratorial;
//                            break;
//                        }
//                    }
//
//
//                $sql = "INSERT INTO ijf.tb_emergencia_resposta_solicitacao
//                       (gruporesposta_id,ficha_id, evolucao_id, datasolicitacao, horasolicitacao, dataultimasolicitacao, horaultimasolicitacao,
//                        dataprevista, horaprevista, dataalta, horaalta) VALUES
//                       ($laboratorial,$ficha_id, $evolucao_id, '$$datasolicitacaolaboratorial', '$horasolicitacaolaboratorial', '$dataultimasolicitacaolaboratorial', '$horaultimasolicitacaolaboratorial',
//                        '$dataprevistalaboratorial', '$horaprevistalaboratorial', '$dataaltalaboratorial', '$horaaltalaboratorial')";
//                $this->db->query($sql);
//                $erro = $this->db->_error_message();
//                if (trim($erro) != "") // erro de banco
//                { return false; }
//                else
//                { return true;
//             }
//          }
//    }
     

    
 }
?>
