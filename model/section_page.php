<?php
class SectionPageDTO
{
    public string $sectionSlug;
    public string $sectionTitle;
    public int $sectionId;
    public int $pageId;
    public string $pageSlug;
    public string $pageTitle;
    public string $pageUrl;

    public string $rawSlug;

    public function __construct(array $row, string $rawSlug)
    {
        $this->sectionSlug = $row['section_slug'];
        $this->sectionTitle = $row['section_title'];
        $this->sectionId = (int) $row['section_id'];
        $this->pageId = (int) $row['page_id'];
        $this->pageSlug = $row['page_slug'];
        $this->pageTitle = $row['page_title'];
        $this->pageUrl = $row['page_url'];
        $this->rawSlug = $rawSlug;
    }

    public function getHumanReadableTitle(): string
    {
        return str_replace('_', ' ', $this->rawSlug);
    }
}
?>