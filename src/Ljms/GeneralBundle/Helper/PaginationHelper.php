<?php

namespace Ljms\GeneralBundle\Helper;

class PaginationHelper {

 	private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function calculateHash($object, $page, $limit)
    {

    	$container  = $this->container;

        $paginator  = $container->get("knp_paginator");
        $pagination = $paginator->paginate(
            $object,
            $page,
            $limit
        );
        return $pagination;

    }
}