<?php namespace LaravelSeed\Repositories;

use Illuminate\Database\ConnectionResolverInterface;

class SeederDbRepository {

    /**
     * @var ConnectionResolverInterface
     */
    private $manager;

    public function __construct(ConnectionResolverInterface $manager) {

        $this->manager = $manager;
    }
}