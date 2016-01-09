<?php

namespace Wallabag\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KPhoen\RulerZBundle\Validator\Constraints as RulerZAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tagging rule.
 *
 * @ORM\Entity(repositoryClass="Wallabag\CoreBundle\Repository\TaggingRuleRepository")
 * @ORM\Table(name="`tagging_rule`")
 * @ORM\Entity
 */
class TaggingRule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @RulerZAssert\ValidRule(
     *  allowed_variables={"title", "url", "isArchived", "isStared", "content", "language", "mimetype", "readingTime", "domainName"},
     *  allowed_operators={">", "<", ">=", "<=", "=", "is", "!=", "and", "not", "or", "matches"}
     * )
     * @ORM\Column(name="rule", type="string", nullable=false)
     */
    private $rule;

    /**
     * @var array
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="tags", type="simple_array", nullable=false)
     */
    private $tags = [];

    /**
     * @ORM\ManyToOne(targetEntity="Wallabag\CoreBundle\Entity\Config", inversedBy="taggingRules")
     */
    private $config;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rule.
     *
     * @param string $rule
     *
     * @return TaggingRule
     */
    public function setRule($rule)
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * Get rule.
     *
     * @return string
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Set tags.
     *
     * @param array<string> $tags
     *
     * @return TaggingRule
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return array<string>
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set config.
     *
     * @param Config $config
     *
     * @return TaggingRule
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }
}
