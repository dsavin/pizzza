<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\Comment;

/**
 * CommentChain
 *
 * @ORM\Entity

 */
class CommentChain extends Comment
{

    /**
     * @ORM\ManyToOne(targetEntity="Chain")
     * @ORM\JoinColumn(name="chain_id", referencedColumnName="id", nullable=false)
     */
    private $chain;

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

        return self::TYPE_CHAIN;
    }
}
