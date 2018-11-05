<?php

class AnyCommentRestSubscriptions extends AnyCommentRestController {

	/**
	 * Constructor.
	 *
	 * @since 4.7.0
	 */
	public function __construct() {
		$this->namespace = 'anycomment/v1';
		$this->rest_base = 'subscribe';

		if ( AnyCommentGenericSettings::is_notify_subscribers() ) {
			add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		}
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @since 4.7.0
	 */
	public function register_routes() {

		register_rest_route( $this->namespace, '/' . $this->rest_base, [
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'create_item' ],
				'permission_callback' => [ $this, 'create_item_permissions_check' ],
				'args'                => [
					'email' => [
						'description' => __( "User email to subscribe", 'anycomment' ),
						'type'        => 'int'
					],
					'post'  => [
						'description' => __( "Post ID to subscribe to", 'anycomment' ),
						'type'        => 'int'
					],
				],
			],
			'schema' => [ $this, 'get_public_item_schema' ],
		] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_item_permissions_check( $request ) {

		if ( empty( $request['email'] ) ) {
			return new WP_Error( 'rest_comment_like_invalid_post_id', __( 'Sorry, email is required.', 'anycomment' ), array( 'status' => 403 ) );
		}

		if ( empty( $request['post'] ) ) {
			return new WP_Error( 'rest_comment_like_invalid_post_id', __( 'Sorry, post is required.', 'anycomment' ), array( 'status' => 403 ) );
		}

		$post = get_post( (int) $request['post'] );

		if ( ! $post ) {
			return new WP_Error( 'rest_comment_like_invalid_post_id', __( 'Sorry, post does not exist.', 'anycomment' ), array( 'status' => 403 ) );
		}

		if ( 'draft' === $post->post_status ) {
			return new WP_Error( 'rest_comment_like_draft_post', __( 'Sorry, you are not allowed to create a comment on this post.', 'anycomment' ), array( 'status' => 403 ) );
		}

		if ( 'trash' === $post->post_status ) {
			return new WP_Error( 'rest_comment_like_trash_post', __( 'Sorry, you are not allowed to create a comment on this post.', 'anycomment' ), array( 'status' => 403 ) );
		}

		if ( AnyCommentSubscriptions::is_subscribed_by( $request['email'], $request['post'] ) ) {
			return new WP_Error( 'rest_already_subscribed', __( 'This email is already subscribed for this post', 'anycomment' ), [ 'status' => 403 ] );
		}

		return true;
	}


	/**
	 * Subscribe.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or error object on failure.
	 */
	public function create_item( $request ) {
		$model = new AnyCommentSubscriptions();

		$model->post_ID = $request['post'];

		$user = get_user_by( 'email', $request['email'] );

		if ( false !== $user ) {
			$model->user_ID      = $user->ID;
			$model->is_active    = true;
			$model->confirmed_at = time();
		} else {
			$model->is_active = false;
		}

		$model->email = $request['email'];

		if ( false === $model->save() ) {
			return new WP_Error( 'rest_subscription_failure', __( 'Error, failed to subscribe. Please try again later.', 'anycomemnt' ), [ 'status' => 403 ] );
		}

		$response = $this->prepare_item_for_response( $model, $request );
		$response = rest_ensure_response( $response );

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $like->ID ) ) );


		return $response;
	}

	/**
	 * Prepares a single like output for response.
	 *
	 * @since 4.7.0
	 *
	 * @param AnyCommentSubscriptions $model
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $model, $request ) {

		$data = [
			'subscribed_at' => $model->confirmed_at
		];

		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->add_additional_fields_to_object( $data, $request );
		$data    = $this->filter_response_by_context( $data, $context );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		return $response;
	}

}