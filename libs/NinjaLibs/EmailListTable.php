<?php

namespace NinjaLibs;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

use WP_List_Table;

class EmailListTable extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular'=> 'ninjalibs_email',
            'plural' => 'ninjalibs_emails',
            'ajax'   => false
         ));
    }

    public function get_columns()
    {
        return  [
            'cb'=>'<input type="checkbox" />',
            'email'=>__('Email'),
            'block_reason'=>__('Block Reason'),
            'block_date'=>__('Block Date'),
        ];
    }

    public function get_hidden_columns()
    {
        return [];
    }

    /**
     * Get the available bulk actions.
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        return array(
            'unblock' => __('Unblock', 'ninjalibs-ses'),
        );
    }

    /**
     * @param object $item
     * @param string $column_name
     */
    protected function column_default($item, $column_name)
    {
        return $item->{$column_name};
    }

    public function column_cb($item)
    {
       return sprintf('<input type="checkbox" name="email[]" value="%s" />', $item->id);
    }

    public function display_table()
    {
        echo '<form id="' . esc_attr($this->_args['plural']) . '-filter" method="post">';
        wp_nonce_field('ninjalibs-emails-nonce', 'ninjalibs-emails-nonce', false);
        $this->search_box(__("Search"), "ninjalibs-search");
        parent::display();
        echo '</form>';
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    public function prepare_items()
    {
        global $wpdb;
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);


        /* -- Preparing your query -- */
        $query = "SELECT * FROM ".$wpdb->base_prefix.'ninjalibs_ses_blocked';


        if (isset($_REQUEST['s'])) { //search filter
            $query .=" WHERE `email` LIKE '%".esc_sql($_REQUEST['s'])."%'";
        }

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ?  esc_sql($_GET["orderby"]) : "id";
        $order = !empty($_GET["order"]) ? esc_sql($_GET["order"]) : "DESC";
        if (!empty($orderby) && !empty($order)) {
            $query.=' ORDER BY '.$orderby.' '.$order;
        }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 20;
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? ($_GET["paged"]) : "";
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged<=0) {
            $paged=1;
        } //How many pages do we have in total?
        $totalpages = ceil($totalitems/$perpage); //adjust the query to take pagination into account
         if (!empty($paged) && !empty($perpage)) {
             $offset=($paged-1)*$perpage;
             $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
         } /* -- Register the pagination -- */

        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
         ));
        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);
    }
}
