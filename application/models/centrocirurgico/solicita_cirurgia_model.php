<?php

require_once APPPATH . 'models/base/BaseModel.php';

//require_once APPPATH . 'models/base/ConvertXml.php';



class solicita_cirurgia_model extends BaseModel {

    var $_internacao_motivosaida_id = null;
    var $_localizacao = null;
    var $_nome = null;

    function solicita_cirurgia_model($internacao_motivosaida_id = null) {
        parent::Model();
        if (isset($internacao_motivosaida_id)) {
            $this->instanciar($internacao_motivosaida_id);
        }
    }

    private function instanciar($internacao_motivosaida_id) {
        if ($internacao_motivosaida_id != 0) {

            $this->db->select('internacao_motivosaida_id,
                            nome');
            $this->db->from('tb_solicitacao_cirurgia');
            $this->db->where('ativo', 'true');
            $this->db->where('internacao_motivosaida_id', $internacao_motivosaida_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_internacao_motivosaida_id= $internacao_motivosaida_id;
            $this->_nome = $return[0]->nome;
        }
    }

    function listamotivosaida($args = array()) {
        $this->db->select(' internacao_motivosaida_id,
                            nome');
        $this->db->from('tb_solicitacao_cirurgia');
        $this->db->where('ativo', 't');
        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('nome ilike', "%" . $args['nome'] . "%", 'left');
            }
        }
        return $this->db;
    }

    
    function listamotivosaidapacientes() {
        $this->db->select('nome,
                internacao_motivosaida_id');
        $this->db->from('tb_solicitacao_cirurgia');
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }
    
    function listaleitomotivosaida(){
        $this->db->select('internacao_leito_id,
                           nome,
                           tipo,
                           condicao,
                           enfermaria_id,
                           ativo');
        $this->db->from('tb_internacao_leito');
        $return = $this->db->get();
        return $return->result();
    }
    function listaleitocirugia(){
        $this->db->select('internacao_leito_id,
                           nome,
                           tipo,
                           condicao,
                           enfermaria_id,
                           ativo');
        $this->db->from('tb_internacao_leito');
        $this->db->where('condicao', 'Cirurgico');
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }
    
    
    function mostrarsaidapaciente($internacao_id){

        $this->db->select('i.internacao_id,
                           p.nome as paciente,
                           m.nome as motivosaida,
                           i.motivo_saida,
                           m.internacao_motivosaida_id,
                           p.paciente_id,
                           i.data_internacao,
                           i.observacao_saida,
                           i.leito,
                           p.sexo,
                           p.nascimento');
        $this->db->from('tb_internacao i, tb_paciente p, tb_operador o,tb_solicitacao_cirurgia m');
        $this->db->where('i.internacao_id', $internacao_id);
        $this->db->where('p.paciente_id = i.paciente_id');
        $this->db->where('o.operador_id = i.medico_id');
        
       // $this->db->where('m.internacao_motivosaida_id = i.motivo_saida ');
        
        $return = $this->db->get();
        return $return->result();
    }
    
    
    function excluirsolicitacaocirurgia($solicitacao_id) {
        $this->db->set('excluido', 't');
        $this->db->where('solicitacao_cirurgia_id', $solicitacao_id);
        $this->db->update('tb_solicitacao_cirurgia');
    }
    
    function gravarsolicitacaocirurgia(){

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        
        $this->db->set('internacao_id', $_POST['internacao_id']);
        $this->db->set('procedimento_id', $_POST['procedimentoID']);
        $this->db->set('data_solicitacao', $horario);
        $this->db->set('operador_solicitacao', $operador_id);
        $this->db->insert('tb_solicitacao_cirurgia');
   
    }
    
    function solicitacirurgia($internacao_id){

        $this->db->select('p.nome as paciente,
                           i.internacao_id,
                           p.paciente_id,
                           i.data_internacao,
                           il.internacao_leito_id as leito_id,
                           p.sexo,
                           p.nascimento,
                           il.nome as leito');
        $this->db->from('tb_internacao i, tb_paciente p, tb_internacao_leito il');
        $this->db->where('i.internacao_id', $internacao_id);
        $this->db->where('i.ativo', 't');
        $this->db->where('p.paciente_id = i.paciente_id');
        
        $return = $this->db->get();
        return $return->result();
    
    }
    
    function listamotivosaidaautocomplete($parametro = null) {
        $this->db->select('internacao_motivosaida_id,
                            nome,
                            localizacao');
        $this->db->from('tb_solicitacao_cirurgia');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

     function excluirmotivosaida($internacao_motivosaida_id) {


        $this->db->set('ativo', 'f');
        $this->db->where('internacao_motivosaida_id', $internacao_motivosaida_id);
        $this->db->update('tb_solicitacao_cirurgia');
    }
     
    function gravarsaida() {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        
      //Tabela internação alteração
      if($_POST['motivosaida']== 'transferencia'){
        $this->db->set('ativo', 'f');
        $this->db->set('hospital_transferencia', $_POST['hospital']);
        $this->db->set('observacao_saida', $_POST['observacao']);
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('data_saida', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('paciente_id', $_POST['idpaciente']);
        $this->db->update('tb_internacao');
      }
      else{
        $this->db->set('ativo', 'f');
        $this->db->set('motivo_saida', $_POST['motivosaida']);
        $this->db->set('observacao_saida', $_POST['observacao']);
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('data_saida', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('paciente_id', $_POST['idpaciente']);
        $this->db->update('tb_internacao');
      }
        //Tabela Ocupação alteração
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('ocupado', 'f');
        $this->db->where('paciente_id', $_POST['idpaciente']);
        $this->db->update('tb_internacao_ocupacao');
        
        //Tabela internacao_leito
        
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('ativo', 't');
        $this->db->where('internacao_leito_id', $_POST['leito']);
        $this->db->update('tb_internacao_leito');
       
    }
    
    function gravarmotivosaida() {

        try {
            $this->db->set('nome', $_POST['nome']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');


            // $this->db->set('paciente_id',$_POST['txtPacienteId'] );

            if ($_POST['internacao_motivosaida_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_solicitacao_cirurgia');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                }
                else
                    $internacao_motivosaida_id = $this->db->insert_id();
            }
            else { // update
                $internacao_motivosaida_id = $_POST['internacao_motivosaida_id'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('internacao_motivosaida_id', $internacao_motivosaida_id);
                $this->db->update('tb_solicitacao_cirurgia');
            }


            return $internacao_motivosaida_id;
        } catch (Exception $exc) {
            return false;
        }
    }

}

?>