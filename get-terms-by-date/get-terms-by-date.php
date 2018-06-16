<?php
/*
Plugin Name: get_terms_by_date()
Description: This function gets terms in the order of the posting date of the latest post related to term.
Author: minkapi
version: 1.0
*/
function get_terms_by_date( $taxonomies = null, $args = array() ) {
	global $wpdb;

	// taxonomy check
	$taxonomies = (array)$taxonomies;
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				return new WP_Error( 'invalid_taxonomy', __( 'Invalid taxonomy.' ) );
			}
		}
	}
	$defaults = array(
		'order'       => 'DESC',
		'number'      => '',
		'offset'      => '',
		'post_status' => 'publish',
		'post_type'   => 'post',
	);
	$query = wp_parse_args( $args, $defaults );
	
	/**
	 * Filters the terms query arguments.
	 *
	 * @since 1.0
	 *
	 * @param array $query      An array of get_terms_by_date() arguments.
	 * @param array $taxonomies An array of taxonomies.
	 */
	$query = apply_filters( 'GTBD_args', $query, $taxonomies );

	// taxonomy
	$sql_clauses['where']['taxonomy'] = "tt.taxonomy IN ('" . implode( "', '", array_map( 'esc_sql', $taxonomies ) ) . "')";

	// post_status
	$post_status = (array)$query['post_status'];
	$sql_clauses['where']['post_status'] = "p.post_status IN ('" . implode( "', '", array_map( 'esc_sql', $post_status ) ) . "')";

	// post_type
	$post_type = (array)$query['post_type'];
	$sql_clauses['where']['post_type'] = "p.post_type IN ('" . implode( "', '", array_map( 'esc_sql', $post_type ) ) . "')";

	/**
	 * Filters the terms query SQL clauses of where.
	 *
	 * @since 1.0
	 *
	 * @param array $sql_clauses['where'] Terms query SQL clauses of where.
	 * @param array $taxonomies           An array of taxonomies.
	 * @param array $query                An array of terms query arguments.
	 */
	$sql_clauses['where'] = apply_filters( 'GTBD_where', $sql_clauses['where'], $taxonomies, $query );

	// order
	$order = 'DESC';
	if ( 'ASC' === strtoupper( $query['order'] ) ) {
		$order = 'ASC';
	}

	// limit
	$number = absint( $query['number'] );
	$offset = absint( $query['offset'] );
	if ( $number ) {
		if ( $offset ) {
			$limit = 'LIMIT ' . $offset . ',' . $number;
		} else {
			$limit = 'LIMIT ' . $number;
		}
	} else {
		$limit = '';
	}

	// Make Request
	$select = 'SELECT ' . implode( ', ', array( 't.*', 'tt.*' ) );
	$from = "FROM $wpdb->posts AS p";
	$from .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id";
	$from .= " INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
	$from .= " INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id";
	$where = 'WHERE ' . implode( ' AND ', $sql_clauses['where'] );
	$orderby = "ORDER BY p.post_date $order";

	$request = "$select $from $where $orderby $limit;";


	$terms = $wpdb->get_results( $request );


	if ( empty( $terms ) ) {
		return array();
	}

	// group by tt.term_taxonomt_id
	$group_terms = array();
	$add_key = array();
	foreach ( $terms as $term ) {
		if ( ! isset( $add_key[$term->term_taxonomy_id] ) ) {
			$group_terms[] = $term;
			$add_key[$term->term_taxonomy_id] = $term->term_taxonomy_id;
		}
	}

	return $group_terms;
}
