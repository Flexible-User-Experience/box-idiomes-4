<?php

namespace App\Entity\Traits;

/**
 * Slug trait.
 *
 * @category Trait
 *
 * @author   David Romaní <david@flux.cat>
 */
trait SlugTrait
{
    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
