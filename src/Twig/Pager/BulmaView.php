<?php

namespace App\Twig\Pager;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\ViewInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulmaView implements ViewInterface
{
    protected BulmaTemplate $template;

    protected TranslatorInterface $translator;

    private PagerfantaInterface $pagerfanta;

    private int $proximity;

    private int $currentPage;

    private int $nbPages;

    private int $startPage;

    private int $endPage;

    public function __construct(BulmaTemplate $template, TranslatorInterface $translator)
    {
        $this->template = $template;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function render(PagerfantaInterface $pagerfanta, $routeGenerator, array $options = []): string
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

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'bulma';
    }

    protected function generate(): string
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

    private function previous(): string
    {
        if ($this->pagerfanta->hasPreviousPage()) {
            return $this->template->previousEnabled($this->pagerfanta->getPreviousPage());
        }

        return $this->template->previousDisabled();
    }

    private function next(): string
    {
        if ($this->pagerfanta->hasNextPage()) {
            return $this->template->nextEnabled($this->pagerfanta->getNextPage());
        }

        return $this->template->nextDisabled();
    }

    private function page(int $page): string
    {
        if ($page === $this->currentPage) {
            return $this->template->current($page);
        }

        return $this->template->page($page);
    }

    private function separatorFirst(): string
    {
        if ($this->startPage > 2) {
            return $this->template->separator();
        }

        return '';
    }

    private function middlePages(): string
    {
        $pages = '';

        for ($page = $this->startPage; $page <= $this->endPage; ++$page) {
            $pages .= $this->page($page);
        }

        return $pages;
    }

    private function separatorLast(): string
    {
        if ($this->endPage < $this->nbPages - 1) {
            return $this->template->separator();
        }

        return '';
    }

    private function last(): string
    {
        if ($this->pagerfanta->getNbPages() > 1) {
            return $this->page($this->pagerfanta->getNbPages());
        }

        return '';
    }
}
