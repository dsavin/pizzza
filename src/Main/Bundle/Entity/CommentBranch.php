<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\CommentChain;

/**
 * CommentBranch
 *
 * @ORM\Entity
 */
class CommentBranch extends CommentChain
{

    /**
     * @ORM\ManyToOne(targetEntity="Branch")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", nullable=true)
     */
    private $branch;


    public function __construct()
    {
        $this->branch = null;
        parent::__construct();
    }

    /**
     * @param Branch $branch
     * @return Comment $this
     */
    public function setBranch( Branch $branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    public function getType()
    {

        return self::TYPE_BRANCH;
    }
}
