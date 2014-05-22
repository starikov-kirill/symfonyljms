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

        // if sent divisions check them
        if ((array_key_exists('divisions', $data)) && (count($data['divisions'])))
        {   
            // get from db divisions that have Director
            $busyDivisions = $em->getRepository('LjmsGeneralBundle:User')->getAllDivisionsWhereDirectorAlreadyHas();

            // if sented division have director add validation error
            foreach ($data['divisions'] as $key => $value)
            {
                if ((array_key_exists($value, $busyDivisions)) && ($busyDivisions[$value] != $id))
                {
                    $error[] = 'Director for division '.$dataForError['divisionsList'][$value].' already has';
                   
                }
            }
        }

        // if sent teamsCoachs check them
        if ((array_key_exists('teamsCoachs', $data)) && (count($data['teamsCoachs'])))
        {   
            // get from db teams that have Coach
            $busyCoachTeams = $em->getRepository('LjmsGeneralBundle:User')->getAllTeamsWhereCoachAlreadyHas();

            // if sented team have coach add validation error
            foreach ($data['teamsCoachs'] as $key => $value)
            {
                if ((array_key_exists($value, $busyCoachTeams)) && ($busyCoachTeams[$value] != $id))
                {
                    $error[] = 'Coach for team '.$dataForError['teamsList'][$value].' already has';
                   
                }
            }
        }

        // if sent teamsManagers check them
        if ((array_key_exists('teamsManagers', $data)) && (count($data['teamsManagers'])))
        {   
            // get from db teams that have Manager
            $busyManagerTeams = $em->getRepository('LjmsGeneralBundle:User')->getAllTeamsWhereManagerAlreadyHas();

            // if sented team have manager add validation error
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