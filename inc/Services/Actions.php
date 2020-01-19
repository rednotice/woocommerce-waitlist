<?php
/**
 * @package wpbitsWaitlist
 * 
 * @since 1.0.0
 */

namespace Inc\Services;

use \Inc\Services\Mail;

/**
 * Class used to implement custom actions on the waitlist custom post type admin page.
 *
 * @since 1.0.0
 */
class Actions
{
    /**
	 * Instance of the Mail class.
	 *
	 * @since 1.0.0
     * 
	 * @var object
	 */
    public $mail;

    /**
	 * Used by the Init class to intantiate this class.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function register(): void
    {
        $this->mail = new Mail;

        // Row actions
        add_filter('post_row_actions', array( $this, 'modifyPostRowActions' ), 10, 2);
        add_action('admin_post_wpbits_mailsent', array( $this, 'sendInstockMail' ), 10);
        add_action('admin_post_wpbits_unsubscribed', array( $this, 'unsubscribe' ), 10);

        // Bulk actions
        add_filter('bulk_actions-edit-wpbitswaitlist', array( $this, 'registerBulkActions'));
        add_filter('handle_bulk_actions-edit-wpbitswaitlist', array( $this, 'handleBulkActions'), 10, 3);
        
        add_action('admin_notices', array( $this, 'adminNotices' ), 10);
    }

    /**
	 * Modies the post row actions on the custom post type admin page.
	 *
	 * @since 1.0.0
     * 
     * @param array $actions Post row actions.
     * @param object $post
	 * @return array
	 */
    public function modifyPostRowActions( array $actions, object $post): array
    {
        if ($post->post_type == 'wpbitswaitlist' &&  $post->post_status != 'trash') {
            $trash = $actions['trash'];
            $actions = [];

            if (get_post_status($post) != 'wpbits_unsubscribed') {
                $quickLinks = [
                    [
                        'name' => 'wpbits_mailsent',
                        'label' => 'Send Mail'
                    ],
                    [
                        'name' => 'wpbits_unsubscribed',
                        'label' => 'Unsubscribe'
                    ]
                ];

                foreach($quickLinks as $quickLink) {
                    $nonce = wp_create_nonce('quick-' . $quickLink['name'] . '-action'); 
                    $link = admin_url( "admin-post.php??post_type={$post->post_type}&action={$quickLink['name']}&post_id={$post->ID}");
                    $actions[$quickLink['name']] = "<a href='$link'>{$quickLink['label']}</a>";
                }
            }

            $actions['trash'] = $trash;
        }

        return $actions;
    }    

    /**
	 * Row action to send instock mail.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function sendInstockMail(): void
    {
        if($_GET['action'] != 'wpbits_mailsent') {
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }

        $subscriberId = $_GET['post_id'];
        $mailSent = $this->mail->sendInstockMail($subscriberId);
        $status = $this->mail->updateSubscriberStatus($subscriberId, $mailSent);

        wp_redirect(add_query_arg($status, 1, $_SERVER['HTTP_REFERER']));
        exit();
    }

    /**
	 * Row action to unsubscribe a subscriber.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function unsubscribe(): void
    {
        if( $_GET['action'] != 'wpbits_unsubscribed' ) {
            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit();
        }

        $post = array(
            'ID' => $_GET['post_id'],
            'post_status' => 'wpbits_unsubscribed'
        );
        wp_update_post($post);

        wp_redirect( add_query_arg( 'wpbits_unsubscribed', 1, $_SERVER['HTTP_REFERER'] ) );
        exit();
    }

    /**
	 * Registers the bulk actions on the custom post type admin page.
	 *
	 * @since 1.0.0
     * 
     * @param array $bulk_actions
	 * @return array|null
	 */
    public function registerBulkActions(array $bulk_actions): ?array
    {
        if( isset( $_GET['post_status'] ) && $_GET['post_status'] == 'trash' ) {
            return null;
        }

        unset( $bulk_actions['edit'] );

        $trash = $bulk_actions['trash'];
        unset( $bulk_actions['trash'] );

        $bulk_actions['wpbits_mailsent'] = 'Send Instock Mail';
        $bulk_actions['wpbits_unsubscribed'] = 'Unsubscribe';
        $bulk_actions['trash'] = $trash;
        return $bulk_actions;
    }

    /**
	 * Handles the bulk actions.
	 *
	 * @since 1.0.0
     * 
     * @param string $redirect The redirect url.
     * @param string $doaction The action being taken.
     * @param array $object_ids The items to take the action on.
	 * @return string
	 */
    public function handleBulkActions(string $redirect, string $doaction, array $object_ids): string
    {
        // Remove query args.
        $redirect = remove_query_arg( array('wpbits_mailsent', 'wpbits_unsubscribed' ), $redirect);

	    if($doaction == 'wpbits_mailsent') {
            $queryArgs = [
                'wpbits_mailsent' => 0,
                'wpbits_failed' => 0,
            ];
 
		    foreach($object_ids as $postId) {
                $mailSent = $this->mail->sendInstockMail($postId);
                $status = $this->mail->updateSubscriberStatus($postId, $mailSent);
                $queryArgs[$status] += 1;
            }
            
             // Add query args for admin notice.
            $redirect = add_query_arg($queryArgs, $redirect);
        }
        
        // Handle "Unsubscribe" bulk action
        if ($doaction == 'wpbits_unsubscribed') {

            foreach ($object_ids as $post_id) {
                wp_update_post([
				    'ID' => $post_id,
				    'post_status' => 'wpbits_unsubscribed'
                ]);
            }
            $redirect = add_query_arg('wpbits_unsubscribed', count($object_ids), $redirect);
        }
 
        return $redirect;
    }

    /**
	 * Admin notices for post row actions and bulk actions.
	 *
	 * @since 1.0.0
     * 
	 * @return void
	 */
    public function adminNotices(): void
    {
        if(!empty($_REQUEST['wpbits_failed'])) {
            printf('<div id="message" class="error notice is-dismissible"><p>' .
                _n('There was an error. %s subscriber was not notified.',
                'There was an error. %s subscribers were not notified.',
                intval($_REQUEST['wpbits_failed'])
            ) . '</p></div>', intval($_REQUEST['wpbits_failed']));
        }

        if(!empty( $_REQUEST['wpbits_mailsent'])) {
            printf('<div id="message" class="updated notice is-dismissible"><p>' .
                _n('%s subscriber was notified.',
                '%s subscribers were notified.',
                intval($_REQUEST['wpbits_mailsent'])
            ) . '</p></div>', intval($_REQUEST['wpbits_mailsent']));
        }

        if(!empty( $_REQUEST['wpbits_unsubscribed'])) {
            printf('<div id="message" class="updated notice is-dismissible"><p>' .
                _n('%s subscriber was unsubscribed.',
                '%s subscribers were unsubscribed.',
                intval($_REQUEST['wpbits_unsubscribed'])
            ) . '</p></div>', intval($_REQUEST['wpbits_unsubscribed']));
        }
    }
}
