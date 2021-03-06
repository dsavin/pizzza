<?php

    namespace Main\Bundle\Entity;

    use Doctrine\ORM\EntityRepository;

    /**
     * DiscountRepository
     *
     * This class was generated by the Doctrine ORM. Add your own custom
     * repository methods below.
     */
    class DiscountRepository extends EntityRepository
    {

        public function findSameDiscount($data)
        {
            $em = $this->getEntityManager();

            $query = $em->createQueryBuilder()
                ->select('d','ch')
                ->from('MainBundle:Discount','d')
                ->join('d.chain','ch')
                ->where('d.chain = :chain')
                ->andWhere('d.city_id = :city_id')
                ->andWhere('d.lang = :lang')
                ->andWhere('d.id <> :not_in_id')
                ->setParameters($data)
                ->setMaxResults(3)
                ;

            return $query->getQuery()->getResult();
        }

        /**
         * Выборка акций других пиццерий
         *
         * @param array $data
         * @return array
         */
        public function findOthers(array $data)
        {
            $em = $this->getEntityManager();

            $query = $em->createQueryBuilder()
                ->select('d','ch')
                ->from('MainBundle:Discount','d')
                ->join('d.chain','ch')
                ->where('d.chain <> :chain')
                ->andWhere('d.city_id = :city_id')
                ->andWhere('d.lang = :lang')
                ->andWhere('d.id > :not_in_id')
                ->setParameters($data)
                ->setMaxResults(3)
            ;

            return $query->getQuery()->getResult();
        }

        public function getAllWithChainByCity($city_id, $lang)
        {
            $em = $this->getEntityManager();

            $query = $em->createQueryBuilder()
                ->select('d')
                ->from('MainBundle:Discount','d')
                ->join('d.chain', 'c')
                ->where('d.city_id = :city_id')
                ->andWhere('d.lang = :lang')

                ->orderBy('d.id','DESC')

                ->setParameter('city_id',$city_id)
                ->setParameter('lang',$lang)
            ;

            return $query->getQuery();
        }
     }
