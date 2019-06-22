<?php
// class dashboard
class Dashboard{

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;
    public $clientesAtivos;
    public $clientesInativos;
    public $totalReclamacoes;
    public $totalElogios;
    public $totalSugestoes;
    public $totalDespesas;

    public function __get($atributo){
        return $this->$atributo;
    }
    public function __set($atributo, $valor){
        $this->$atributo = $valor;
        return $this;
    }
}

class Connection {
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function connect(){
        try{
            $connection = new PDO(
            "mysql:host=$this->host;dbname=$this->dbname",
            "$this->user",
            "$this->pass"
            );

            $connection->exec('set charset set utf8');
            return $connection;

        }catch(PDOException $e){
            echo '<p>'.$e->getMessage().'</p>';
        }
    }
}

class Bd {
    private $connection;
    private $dashboard;

    public function __construct(Connection $connection, Dashboard $dashboard){
        $this->connection = $connection->connect();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas() {
        $query = '
            select
                count(*) as numero_vendas
            from
                tb_vendas
            where
                data_venda between :data_inicio and :data_fim
        ';

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function getTotalVendas() {
        $query = '
            select
                SUM(total) as total_vendas
            from
                tb_vendas
            where
                data_venda between :data_inicio and :data_fim
        ';

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }

    public function getTotalClientesAtivos(){
        $query = "
                select 
                    COUNT(*) as clientes_ativos 
                from 
                    tb_clientes
                where 
                    cliente_ativo = 1";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;

    }

    public function getTotalClientesInativos(){
        $query = "
                select 
                    COUNT(*) as clientes_inativos
                from
                    tb_clientes
                where
                    cliente_ativo = 0";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
    }

    public function getTotalReclamacoes(){
        $query = "
                select 
                    COUNT(*) as reclamacoes
                from
                    tb_contatos
                where
                    tipo_contato = 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->reclamacoes;
    }
    public function getTotalSugestoes(){
        $query = "
                select 
                    COUNT(*) as sugestoes
                from
                    tb_contatos
                where
                    tipo_contato = 2";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->sugestoes;
    }
    public function getTotalElogios(){
        $query = "
                select 
                    COUNT(*) as elogios
                from
                    tb_contatos
                where
                    tipo_contato = 3";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->elogios;
    }

    public function getTotalDespesas() {
        $query = '
            select
                SUM(total) as total_despesas
            from
                tb_despesas
            where
                data_despesa between :data_inicio and :data_fim
        ';

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
    }
}

$dashboard = new Dashboard();

$connection = new Connection();

// $competencia = explode('-', $_GET['competencia']);
// $ano = $competencia[0];
// $mes = $competencia[1];

// $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

// $dashboard->__set('data_inicio', $ano.'-'.$mes.'-'.'01');
// $dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);

$dataInicio = $_GET['data_inicio'];
$dataFim = $_GET['data_fim'];

$dashboard->__set('data_inicio', $dataInicio);
$dashboard->__set('data_fim', $dataFim);

$bd = new Bd($connection, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('clientesAtivos', $bd->getTotalClientesAtivos());
$dashboard->__set('clientesInativos', $bd->getTotalClientesInativos());
$dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
$dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());
$dashboard->__set('totalElogios', $bd->getTotalElogios());
$dashboard->__set('totalDespesas', $bd->getTotalDespesas());
//print_r($dashboard);
echo json_encode($dashboard);

?>

