<?php

namespace App\Repository;

use App\Entity\WeatherForecast;
use App\Module\Weather\Filter\WeatherForecastFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherForecast>
 *
 * @method WeatherForecast|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherForecast|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherForecast[]    findAll()
 * @method WeatherForecast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherForecastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherForecast::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByFilter(WeatherForecastFilter $filter): ?WeatherForecast
    {
        $queryBuilder = $this->basicFilterQueryBuilder($filter);

        $queryBuilder
            ->orderBy('w.id', 'desc')
            ->setMaxResults(1);

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByFilter(WeatherForecastFilter $filter): array
    {
        $queryBuilder = $this->basicFilterQueryBuilder($filter);
        return $queryBuilder->getQuery()->getArrayResult();
    }

    private function basicFilterQueryBuilder(WeatherForecastFilter $filter): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('w');

        if ($filter->createdAt) {
            $queryBuilder
                ->andWhere('w.created_at BETWEEN :start_date AND :end_date')
                ->setParameter('start_date', $filter->createdAt->format('Y-m-d 00:00:00'))
                ->setParameter('end_date', $filter->createdAt->format('Y-m-d 23:59:59'));
        }

        if ($filter->country) {
            $queryBuilder
                ->andWhere('w.country = :country')
                ->setParameter('country', $filter->country->value);
        }

        if ($filter->city) {
            $queryBuilder
                ->andWhere('w.city = :city')
                ->setParameter('city', $filter->city->value);
        }

        return $queryBuilder;
    }
}
