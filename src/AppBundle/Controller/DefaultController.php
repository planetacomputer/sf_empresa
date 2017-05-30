<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/empleats", name="empleats")
     */
    public function empleatsAction(Request $request)
    {
        try{
            $dbh = $this->databaseConnection();

            $sql = "SELECT * FROM T_EMPLOYEES";
            $query = $dbh->prepare($sql);
            $query->execute();

            $results=$query->fetchAll(\PDO::FETCH_OBJ);
            }
        catch (PDOException $e){
            exit("Error: " . $e->getMessage());
        }
        return $this->render('default/empleats.html.twig', array('empleats' => $results));
    }

    /**
     *  @Route("/empleat/{id}", name="empleat_show", requirements={"id": "\d+"})
     */
    public function showEmpleatAction(Request $request)
    {
        $id = $request->get('id');
        $dbh = $this->databaseConnection();
        $sql = "SELECT * FROM T_EMPLOYEES WHERE EMPL_ID = :id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $empleat=$query->fetch(\PDO::FETCH_OBJ);
        return $this->render('default/showEmpleat.html.twig', array('user' => $empleat));
    }

    private function databaseConnection(){
        $dbh = new \PDO("mysql:host=".$this->container->getParameter('database_host').";dbname=".$this->container->getParameter('database_name'),$this->container->getParameter('database_user'), $this->container->getParameter('database_password'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        return $dbh;
    }
}
