<?php

namespace Core;
defined('ROOTPATH') OR exit('Access Denied!');

class Pager
{
    public array $links = [];
    public int $offset = 0;
    public int $page_number = 1;
    public int $start = 1;
    public int $end = 1;
    public int $limit = 10;
    public string $nav_class = "";
    public string $ul_class = "pagination justify-content-center";
    public string $li_class = "page-item";
    public string $a_class = "page-link";

    public function __construct($limit = 10, $extras = 1)
    {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = $page_number < 1 ? 1 : $page_number;

        $this->end = $page_number + $extras;
        $this->start = $page_number - $extras;

        if ($this->start < 1) {
            $this->start = 1;
        }

        $this->offset = ($page_number - 1) * $limit;
        $this->page_number = $page_number;
        $this->limit = $limit;

        $url = isset($_GET['url']) ? $_GET['url'] : "";

        $current_link = DOCUMENT_ROOT . '/' . $url . '?' . trim(str_replace("url=", "", str_replace($url, "", $_SERVER['QUERY_STRING'])), "&");
        $current_link = !strstr($current_link, "page=") ? $current_link . "&page=1" : $current_link;

        if (!strstr($current_link, "?")) {
            $current_link = str_replace("&page=", "?page=", $current_link);
        }

        $first_link = preg_replace('/page=[0-9]+/', 'page=1', $current_link);
        $next_link = preg_replace('/page=[0-9]+/', 'page=' . ($page_number + $extras + 1), $current_link);

        $this->links['first'] = $first_link;
        $this->links['current'] = $current_link;
        $this->links['next'] = $next_link;
    }

    public function display($record_count = null)
    {
        if ($record_count == null) {
            $record_count = $this->limit;
        }
        if ($record_count == $this->limit || $this->page_number > 1) {
        ?>
        <br class="clearfix">
        <div>
            <nav class="<?php echo $this->nav_class; ?>">
                <ul class="<?php echo $this->ul_class; ?>">
                    <li class="<?php echo $this->li_class; ?>"><a class="<?php echo $this->a_class; ?>" href="<?php echo $this->links['first']; ?>">First</a></li>

                    <?php for ($x = $this->start; $x <= $this->end; $x++): ?>
                    <li  class="<?php echo $this->li_class; ?>
                    <?php echo ($x == $this->page_number) ? ' active ' : ''; ?>
                    "><a class="<?php echo $this->a_class; ?>" href="
                    <?php echo preg_replace('/page=[0-9]+/', "page=" . $x, $this->links['current']); ?>
                    "><?php echo $x; ?></a></li>
                    <?php endfor; ?>

                    <li class="<?php echo $this->li_class; ?>"><a class="<?php echo $this->a_class; ?>" href="<?php echo $this->links['next']; ?>">Next</a></li>
                </ul>
            </nav>
        </div>
        <?php
        }
    }
}