<?php

namespace AnyComment\Hooks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Comment;

use AnyComment\AnyCommentCommentMeta;
use AnyComment\Models\AnyCommentLikes;
use AnyComment\Models\AnyCommentEmailQueue;
use AnyComment\Models\AnyCommentSubscriptions;
use AnyComment\Models\AnyCommentUploadedFiles;
use AnyComment\Admin\AnyCommentGenericSettings;
use AnyComment\Cache\AnyCommentRestCacheManager;
use AnyComment\Admin\AnyCommentIntegrationSettings;

/**
 * Class AnyCommentCommentHooks is used to control hooks related to comments.
 */
class AnyCommentCommentHooks {

	/**
	 * AnyCommentCommentHooks constructor.
	 */
	public function __construct () {
		$this->init();
	}

	/**
	 * Init method of all related hooks.
	 */
	private function init () {
		// Should delete files, likes, etc before comment is deleted
		// as WP by default remove comment meta, which is required to determine attachments IDs
		add_action( 'delete_comment', [ $this, 'process_deleted_comment' ], 10, 2 );

		// Should drop comment cache after it was deleted, just in case
//		add_action( 'deleted_comment', [ $this, 'process_soft_comment' ], 10, 2 );

		// After comment was updated
		add_action( 'edit_comment', [ $this, 'process_edit_comment' ], 10, 2 );

		// After comment was trashed, marked as spam, etc
//		add_action( 'trashed_comment', [ $this, 'process_soft_comment' ], 10, 2 );
//		add_action( 'untrashed_comment', [ $this, 'process_soft_comment' ], 10, 2 );
//		add_action( 'spam_comment', [ $this, 'process_soft_comment' ], 10, 2 );
//		add_action( 'unspam_comment', [ $this, 'process_soft_comment' ], 10, 2 );

		// On comment status change
//		add_action( 'wp_set_comment_status', [ $this, 'process_set_status_comment' ], 10, 2 );


		add_action( 'wp_insert_comment', [ $this, 'process_new_comment' ], 9, 2 );

		/**
		 * @see update_metadata() to find this hook (it is dynamically constructed) based on meta type
		 */
		add_action( "updated_comment_meta", [ $this, 'process_comment_meta_update' ], 10, 4 );

		remove_filter( 'pre_comment_content', 'wp_filter_post_kses' );
		remove_filter( 'pre_comment_content', 'wp_filter_kses' );

		// Extend allowed HTML tags to the needs of visual editor
		add_filter( 'pre_comment_content', [ $this, 'kses_allowed_html_for_quill' ], 9 );
	}

	/**
	 * Extend list of allowed HTML tags.
	 *
	 * @param string $data Post content to filter, expected to be escaped with slashes
	 *
	 * @return mixed
	 */
	public function kses_allowed_html_for_quill ( $data ) {
		global $allowedtags;

		$allowedtags = [];

		$allowedtags['p']          = [];
		$allowedtags['a']          = [ 'href' => true, 'target' => true, 'rel' => true ];
		$allowedtags['ul']         = [];
		$allowedtags['ol']         = [];
		$allowedtags['blockquote'] = [ 'class' => true ];
		$allowedtags['code']       = [];
		$allowedtags['li']         = [];
		$allowedtags['b']          = [];
		$allowedtags['i']          = [];
		$allowedtags['u']          = [];
		$allowedtags['strong']     = [];
		$allowedtags['em']         = [];
		$allowedtags['br']         = [];
		$allowedtags['img']        = [ 'class' => true, 'src' => true, 'alt' => true ];
		$allowedtags['figure']     = [];
		$allowedtags['iframe']     = [];

		return $data;
	}

	/**
	 * Process comment which will be deleted soon in order to clean-up after it.
	 *
	 * @param int $comment_id Comment ID.
	 * @param WP_Comment $comment Comment object.
	 *
	 * @return bool false when comment should not processed.
	 */
	public function process_new_comment ( $comment_id, $comment ) {

		if ( $comment->comment_type !== '' && $comment->comment_type !== 'review' ) {
			return false;
		}

		// If Akismet is not active, then we may process notifications normally
		// otherwise we should wait until it will be filtered and confired by Akismet that it is safe
		// to notify user about it. Otherwise, it may cause possible spam attack
		$is_akismet_active = AnyCommentIntegrationSettings::is_akismet_active() && is_plugin_active( 'akismet/akismet.php' );

		if ( ! $is_akismet_active ) {

			// Notify subscribers
			AnyCommentSubscriptions::notify_by( $comment );

			// Notify on comment reply
			if ( AnyCommentGenericSettings::is_notify_on_new_reply() ) {
				AnyCommentEmailQueue::add_as_reply( $comment );
			}

			// Notify admin
			if ( AnyCommentGenericSettings::is_notify_admin() ) {
				AnyCommentEmailQueue::add_as_admin_notification( $comment );
			}
		}

		// Flush post comment count cache
		AnyCommentRestCacheManager::flushPostCommentCount( $comment->comment_post_ID );

		return true;
	}

	/**
	 * Check update meta for comment.
	 *
	 * @param int $meta_id ID of the metadata entry to update.
	 * @param int $object_id Object ID.
	 * @param string $meta_key Meta key.
	 * @param mixed $_meta_value Meta value.
	 *
	 * @see update_metadata() for further information about list of params. They are based on what is provided by the hook.
	 */
	public function process_comment_meta_update ( $meta_id, $object_id, $meta_key, $_meta_value ) {
		// Akismet comment checking.
		// When Akismet option is active, meta about comment status changed to success -> should notify
		if ( AnyCommentIntegrationSettings::is_akismet_active() && $meta_key === 'akismet_result' && $_meta_value == 'true' ) {
			// Notify subscribers
			AnyCommentSubscriptions::notify_by( $object_id );

			// Notify on comment reply
			if ( AnyCommentGenericSettings::is_notify_on_new_reply() ) {
				AnyCommentEmailQueue::add_as_reply( $object_id );
			}

			// Notify admin
			if ( AnyCommentGenericSettings::is_notify_admin() ) {
				AnyCommentEmailQueue::add_as_admin_notification( $object_id );
			}
		}
	}

	/**
	 * Process comment which will be deleted soon in order to clean-up after it.
	 *
	 * @param int $comment_id Comment ID.
	 * @param WP_Comment $comment Comment object.
	 */
	public function process_deleted_comment ( $comment_id, $comment ) {
		// Delete likes of a comment
		AnyCommentLikes::deleteLikes( $comment_id );

		// Flush post comment count cache
		AnyCommentRestCacheManager::flushPostCommentCount( $comment->comment_post_ID );

		// Delete attached files
		$comment_metas = AnyCommentCommentMeta::get_attachments( $comment_id );

		if ( ! empty( $comment_metas ) ) {
			foreach ( $comment_metas as $comment_meta ) {
				AnyCommentUploadedFiles::delete( $comment_meta->file_id );
			}
		}
	}

	/**
	 * Drops cache of updated comment.
	 *
	 * @param int $comment_id Comment ID.
	 * @param array $data Comment data.
	 */
	public function process_edit_comment ( $comment_id, $data ) {
		// Mark comment as updated
		AnyCommentCommentMeta::mark_updated( $comment_id, $data );
	}

	/**
	 * Flush cache of comment after changing its status.
	 *
	 * @param int $comment_id Comment ID.
	 * @param int $status Status to be assigned.
	 */
	public function process_set_status_comment ( $comment_id, $status ) {
	}

	/**
	 * Soft touch on the comment, such as flushing, etc.
	 *
	 * Primarily used for (un)span, (un)trash comment, etc.
	 *
	 * @param int $comment_id Comment ID.
	 * @param WP_Comment $comment Comment object.
	 */
	public function process_soft_comment ( $comment_id, $comment ) {
	}
}
