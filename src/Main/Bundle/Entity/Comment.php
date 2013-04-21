<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Comment
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="Main\Bundle\Entity\CommentRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="smallint")
 * @ORM\DiscriminatorMap({1 = "Comment", 2 = "CommentChain", 3 = "CommentDelivery", 4 = "CommentBranch"})
 *
 * @Assert\Callback(methods={"validateThisEntity"})
 */
class Comment
{
    const TYPE_COMMENT = 1;
    const TYPE_CHAIN = 2;
    const TYPE_DELIVERY = 3;
    const TYPE_BRANCH = 4;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Неможет быть пустое")
     * @Assert\Length(min = "3", minMessage="Минимальная длинна 3 символа")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "Данный адресс '{{ value }}' некорректный",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="smallint")
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

    /**
     * @var string
     * @Assert\NotBlank(message="Неможет быть пустое")
     * @Assert\Length(min = "50", minMessage="Больше 50ти символов")
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_ip", type="integer")
     */
    private $userIp;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->parent = null;
        $this->created_at = new \DateTime('now');
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Comment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Comment
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Comment
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }


    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {

        return self::TYPE_COMMENT;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Add children
     *
     * @param Comment $children
     */
    public function addChildren(Comment $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param Comment $parent
     */
    public function setParent(Comment $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Comment
     */
    public function getParent()
    {

        return $this->parent;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Comments
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Функция для валидации всей ентети
     *
     * @param ExecutionContext $context
     * @return ExecutionContext
     */
    public function validateThisEntity(ExecutionContext $context)
    {

        if ($this->email == 'jdfg93sad2@gmail.com') {
            $context->addViolationAtSubPath('email', 'Введите свой Email');
        }
        if ($this->name == 'Имя') {
            $context->addViolationAtSubPath('name', 'Введите ваше Имя');
        }

        return $context;
    }

    /**
     * Забиваем все нам нужные поля приходящими данными
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        foreach ($data as $k => $v) {
            $data[$k] = trim($v);
        }

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }
        if (isset($data['email'])) {
            $this->setEmail($data['email']);
        }
        if (isset($data['text'])) {
            $this->setText($data['text']);
        }
        if (isset($data['rating'])) {
            $this->setRating($data['rating']);
        }

        return $this;
    }

    /**
     * Set userIp
     *
     * @param integer $userIp
     * @return Comment
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;

        return $this;
    }

    /**
     * Get userIp
     *
     * @return integer
     */
    public function getUserIp()
    {
        return $this->userIp;
    }
}
