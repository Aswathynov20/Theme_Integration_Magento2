<?php

namespace TopBrands\BrandsList\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    public const ID = 'id'; // Uppercase constant
    public const TITLE = 'title';

    public const IMAGE = 'image'; // Uppercase constant

    /**
     * Get ArticleId.
     *
     * @return int
     */
    public function getArticleId();

    /**
     * Set ArticleId.
     *
     * @param int $articleId
     */
    public function setArticleId($articleId);

    /**
     * Get Title.
     *
     * @return string
     */
    public function getTitle();

    // Uncomment and add missing @param and @return annotations
    // /**
    //  * Get Image.
    //  *
    //  * @return string
    //  */
    // public function getImage();

    // /**
    //  * Set Image.
    //  *
    //  * @param string $image
    //  */
    // public function setImage($image);

    /**
     * Set Title.
     *
     * @param string $title
     */
    public function setTitle($title);
}
