<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\Comment;

/**
 * CommentDelivery
 *
 * @ORM\Entity
 */
class CommentDelivery extends Comment
{

    /**
     * @ORM\ManyToOne(targetEntity="Chain")
     * @ORM\JoinColumn(name="chain_id", referencedColumnName="id", nullable=false)
     */
    private $chain;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Chain $chain
     * @return Comment $this
     */
    public function setChain( Chain $chain)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Get chain
     *
     * @return Chain
     */
    public function getChain()
    {
        return $this->chain;
    }

    public function getType()
    {

        return self::TYPE_DELIVERY;
    }
}
