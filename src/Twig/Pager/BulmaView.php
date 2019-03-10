<?php

namespace App\Twig\Pager;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\ViewInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulmaView implements ViewInterface
{
    /**
     * @var BulmaTemplate
     */
    protected $template;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var PagerfantaInterface
     */
    private $pagerfanta;

    /**
     * @var int
     */
    private $proximity;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $nbPages;

    /**
     * @var int
     */
    private $startPage;

    /**
     * @var int
     */
    private $endPage;

    /**
     * @param BulmaTemplate $template
     * @param TranslatorInterface $translator
     */
    public function __construct(BulmaTemplate $template, TranslatorInterface $translator)
    {
        $this->template = $template;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function render(PagerfantaInterface $pagerfanta, $routeGenerator, array $options = [])
    {
        $this->pagerfanta = $pagerfanta;
        $this->currentPage = $pagerfanta->getCurrentPage();
        $this->nbPages = $pagerfanta->getNbPages();
        $this->proximity = isset($options['proximity']) ? (int) $options['proximity'] : 2;

        $this->template->setRouteGenerator($routeGenerator);
        $this->template->setTranslator($this->translator);
        $this->template->setOptions($options);

        $startPage = $this->currentPage - $this->proximity;
        $endPage = $this->currentPage + $this->proximity;

        if ($startPage < 2) {
            $endPage = min($endPage + (1 - $startPage), $this->nbPages);
            $startPage = 2;
        }
        if ($endPage > $this->nbPages - 1) {
            $startPage = max($startPage - ($endPage - $this->nbPages), 1);
            $endPage = $this->nbPages - 1;
        }

        $this->startPage = $startPage;
        $this->endPage = $endPage;

        return $this->generate();
    }

    protected function generate()
    {
        $relButtons = $this->previous().$this->next();

        $pages =
            $this->page(1)
            .$this->separatorFirst()
            .$this->middlePages()
            .$this->separatorLast()
            .$this->last()
        ;

        $search = ['%rel_buttons%', '%pages%'];
        $replace = [$relButtons, $pages];

        return str_replace($search, $replace, $this->template->container());
    }

    private function previous()
    {
        if ($this->pagerfanta->hasPreviousPage()) {
            return $this->template->previousEnabled($this->pagerfanta->getPreviousPage());
        }

        return $this->template->previousDisabled();
    }

    private function next()
    {
        if ($this->pagerfanta->hasNextPage()) {
            return $this->template->nextEnabled($this->pagerfanta->getNextPage());
        }

        return $this->template->nextDisabled();
    }

    private function page(int $page)
    {
        if ($page === $this->currentPage) {
            return $this->template->current($page);
        }

        return $this->template->page($page);
    }

    private function separatorFirst()
    {
        if ($this->startPage > 2) {
            return $this->template->separator();
        }
    }

    private function middlePages()
    {
        $pages = '';

        for ($page = $this->startPage; $page <= $this->endPage; ++$page) {
            $pages .= $this->page($page);
        }

        return $pages;
    }

    private function separatorLast()
    {
        if ($this->endPage < $this->nbPages - 1) {
            return $this->template->separator();
        }
    }

    private function last()
    {
        if ($this->pagerfanta->getNbPages() > 1) {
            return $this->page($this->pagerfanta->getNbPages());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bulma';
    }
}
