<?php

namespace Ljms\GeneralBundle\Helper;

class ValidationRoleHelper {

 	private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function ValidateRole($data, $id, $dataForError)
    {
        $container  = $this->container;

        $em = $container->get("doctrine")->getManager();
        $error = array();

        if ((array_key_exists('divisions', $data)) && (count($data['divisions'])))
        {
            $busyDivisions = $em->getRepository('LjmsGeneralBundle:User')->getAllDivisionsWhereDirectorAlreadyHas();

            foreach ($data['divisions'] as $key => $value)
            {
                if ((array_key_exists($value, $busyDivisions)) && ($busyDivisions[$value] != $id))
                {
                    $error[] = 'Director for division '.$dataForError['divisionsList'][$value].' already has';
                   
                }
            }
        }

        if ((array_key_exists('teamsCoachs', $data)) && (count($data['teamsCoachs'])))
        {
            $busyCoachTeams = $em->getRepository('LjmsGeneralBundle:User')->getAllTeamsWhereCoachAlreadyHas();

            foreach ($data['teamsCoachs'] as $key => $value)
            {
                if ((array_key_exists($value, $busyCoachTeams)) && ($busyCoachTeams[$value] != $id))
                {
                    $error[] = 'Coach for team '.$dataForError['teamsList'][$value].' already has';
                   
                }
            }
        }

        if ((array_key_exists('teamsManagers', $data)) && (count($data['teamsManagers'])))
        {
            $busyManagerTeams = $em->getRepository('LjmsGeneralBundle:User')->getAllTeamsWhereManagerAlreadyHas();

            foreach ($data['teamsManagers'] as $key => $value)
            {
                if ((array_key_exists($value, $busyManagerTeams)) && ($busyManagerTeams[$value] != $id))
                {
                    $error[] = 'Manager for team '.$dataForError['teamsList'][$value].' already has';
                   
                }
            }
        }

        return $error;       

    }
}