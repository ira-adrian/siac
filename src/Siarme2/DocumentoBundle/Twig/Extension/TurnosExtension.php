<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class TurnosExtension extends \Twig_Extension
{

    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('Turnos', [$this, 'TurnosFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function TurnosFilter( $usuarioId)
    {
        # el usuario me devuelve el departamento de los expedientes a buscar
      $result = [];
      $usuario = $this->em->getRepository('UsuarioBundle:Usuario')->find($usuarioId);
      $repository = $this->em->getRepository('ExpedienteBundle:Expediente');
         $fecha = new \DateTime('now');
        $query = $repository->createQueryBuilder('e'); 
              $query->join('e.clasificacion' , 'c')
                    ->join('e.agente' , 'a')
                    ->addSelect('a')
                    ->join('e.datoAt' , 'dt')
                    ->addSelect('dt')
                    ->Where('e.departamentoRm = :dpto')
                    ->andWhere('dt.fechaTurno = :fecha')
                    ->andWhere('dt.turno = :estado or dt.estado = :estado1')
                    ->andWhere('e.estado = :estado2')
                    ->setParameter('dpto', $usuario->getDepartamentoRm()->getId())
                    ->setParameter('estado', true)
                    ->setParameter('estado1', "Con Turno")
                    ->setParameter('estado2', true)

                    ->setParameter('fecha', $fecha->format('Y-m-d'))
                    ->orderBy('dt.fechaTurno', 'ASC'); 

                $dql = $query->getQuery();
        $turnos = $dql->getResult();


       $result["hoy"]= count($turnos);

        $query = $repository->createQueryBuilder('e'); 
              $query->join('e.clasificacion' , 'c')
                    ->join('e.agente' , 'a')
                    ->addSelect('a')
                    ->join('e.datoAt' , 'dt')
                    ->addSelect('dt')
                    ->Where('e.departamentoRm = :dpto')
                    ->andWhere('dt.fechaTurno > :fecha')
                    ->andWhere('dt.turno = :estado or dt.estado = :estado1')
                    ->andWhere('e.estado = :estado2')
                    ->setParameter('dpto', $usuario->getDepartamentoRm()->getId())
                    ->setParameter('estado', true)
                    ->setParameter('estado1', "Con Turno")
                    ->setParameter('estado2', true)

                    ->setParameter('fecha', $fecha->format('Y-m-d'))
                    ->orderBy('dt.fechaTurno', 'ASC'); 

                $dql = $query->getQuery();
        $turnos = $dql->getResult();
        $result["total"] = count($turnos);

        # result es un array ['hoy'=>0, 'total'=>0]
        # en twig se accede: {% set turnos = usuario.id | Turnos() %}
        # luego {% turnos.hoy %} // 0  

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'turnos_extension';
    }
}