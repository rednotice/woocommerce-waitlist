<?php
/**
 * @package woobitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Base\Paths;
use \Inc\Base\Helpers;

/**
 * Exports the subscribers to a CSV file.
 * 
 * @since 1.0.0
 */
class CsvExport extends Paths
{
    /**
	 * Used by the Init class to intantiate the class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        add_action('admin_head-edit.php', array($this, 'addExportButton'), 10);

        add_action('wp_ajax_nopriv_woobits_export', array($this, 'formatExportData'));
        add_action('wp_ajax_woobits_export', array($this, 'formatExportData'));
    }

    /**
     * @since 1.0.0
     * 
     * @return null|void
     */
    public function addExportButton()
    {
        global $current_screen;

        if ($current_screen->post_type !== 'woobitswaitlist') {
            return null;
        }

        echo '<script type="text/javascript" src="' . $this->pluginUrl .'/assets/js/csv-export.js"></script>';
    }

    /**
     * @since 1.0.0
     * 
     * @return void
     */
    public function formatExportData(): void
    {
        $subscribers = Helpers::getAllSubscribers();

        $formattedSubscribers = [];
        foreach($subscribers as $subscriber) {
            $email = Helpers::getSubscriberEmail($subscriber->ID);
            $status = str_replace('woobits_', '', $subscriber->post_status);
            $productId = Helpers::getProductId($subscriber->ID);
            $productName = Helpers::getProductName($subscriber->ID);
            $subscribedAt = Helpers::getSubscriptionDate($subscriber->ID);
            $mailsentAt = Helpers::getInstockMailDate($subscriber->ID);

            $formattedSubscribers[] = [
                'Email' => $email,
                'Status' => $status,
                'Product Id' => $productId,
                'Product Name' => $productName,
                'Subscription Date' => $subscribedAt,
                'Instock Mail Date' => $mailsentAt
            ];
        }

        $this->generateCsv($formattedSubscribers);
    }

    /**
     * @since 1.0.0
     * 
     * @param array $data
     * @return void
     */
    public function generateCsv(array $data): void 
    {
        $fh = fopen('php://temp', 'rw');

        fputcsv($fh, array_keys(current($data)));

        foreach ($data as $row) {
            fputcsv($fh, $row);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="waitlist.csv";');
        fpassthru($fh);

        echo $csv;
        exit;
    }
}