<?php

namespace Ljms\GeneralBundle\Helper;

class PaginationHelper {

 	private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function calculate_hash($object, $page, $limit)
    {

    	$container  = $this->container;

        $paginator  = $container->get("knp_paginator");
        $pagination = $paginator->paginate(
            $object,
            $page/*page number*/,
            $limit/*limit per page*/
        );
        return $pagination;

    }
}