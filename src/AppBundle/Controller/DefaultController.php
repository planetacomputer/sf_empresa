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
        $sql = "SELECT * FROM T_EMPLOYEES e INNER JOIN T_OFFICES o ON e.OFFC_ID = o.OFFC_ID WHERE EMPL_ID = :id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $empleat=$query->fetch(\PDO::FETCH_OBJ);
        return $this->render('default/showEmpleat.html.twig', array('user' => $empleat));
    }

    public function sidebarAction($id){
        $dbh = $this->databaseConnection();
        $sql = "SELECT * FROM T_PROJECTS p INNER JOIN T_PROJECTS_EMPLOYEES pe ON p.PRJT_ID  = pe.PRJT_ID  WHERE pe.EMPL_ID = :id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $projectes=$query->fetchAll(\PDO::FETCH_OBJ);
        return $this->render('default/sidebar.html.twig', array('projectes' => $projectes));
    }

    private function databaseConnection(){
        $dbh = new \PDO("mysql:host=".$this->container->getParameter('database_host').";dbname=".$this->container->getParameter('database_name'),$this->container->getParameter('database_user'), $this->container->getParameter('database_password'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        return $dbh;
    }
}
