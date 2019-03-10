<?php

namespace App\Twig\Pager;

use Pagerfanta\View\Template\Template;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulmaTemplate extends Template
{
    protected static $defaultOptions = [
        'previous_message' => 'global.previous',
        'next_message' => 'global.next',
        'container_template' => '<nav class="pagination" role="navigation">%rel_buttons%<ul class="pagination-list">%pages%</ul></nav>',
        'previous_template' => '<a href="%href%" class="pagination-previous" rel="prev" %disabled%>%previous_message%</a>',
        'next_template' => '<a href="%href%" class="pagination-next" rel="next" %disabled%>%next_message%</a>',
        'page_template' => '<li><a href="%href%" class="pagination-link %current%">%page%</a></li>',
        'dots_template' => '<span class="pagination-ellipsis">&hellip;</span>',
    ];

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function container()
    {
        return $this->option('container_template');
    }

    /**
     * {@inheritdoc}
     */
    public function page($page)
    {
        $text = $page;

        return $this->pageWithText($page, $text);
    }

    /**
     * {@inheritdoc}
     */
    public function pageWithText($page, $text, $rel = null)
    {
        $search = ['%href%', '%current%', '%page%'];
        $replace = [$this->generateRoute($page), '', $text];

        return str_replace($search, $replace, $this->option('page_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function previousDisabled()
    {
        $search = ['%href%', '%disabled%', '%previous_message%'];
        $replace = ['#', 'disabled', $this->translator->trans($this->option('previous_message'))];

        return str_replace($search, $replace, $this->option('previous_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function previousEnabled($page)
    {
        $search = ['%href%', '%disabled%', '%previous_message%'];
        $replace = [$this->generateRoute($page), '', $this->translator->trans($this->option('previous_message'))];

        return str_replace($search, $replace, $this->option('previous_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function nextDisabled()
    {
        $search = ['%href%', '%disabled%', '%next_message%'];
        $replace = ['#', 'disabled', $this->translator->trans($this->option('next_message'))];

        return str_replace($search, $replace, $this->option('next_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function nextEnabled($page)
    {
        $search = ['%href%', '%disabled%', '%next_message%'];
        $replace = [$this->generateRoute($page), '', $this->translator->trans($this->option('next_message'))];

        return str_replace($search, $replace, $this->option('next_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->page(1);
    }

    /**
     * {@inheritdoc}
     */
    public function last($page)
    {
        return $this->page($page);
    }

    /**
     * {@inheritdoc}
     */
    public function current($page)
    {
        $search = ['%href%', '%current%', '%page%'];
        $replace = [$this->generateRoute($page), 'is-current', $page];

        return str_replace($search, $replace, $this->option('page_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function separator()
    {
        return $this->option('dots_template');
    }
}
