<?php
function http_parse_headers( array $headers ) {
	$parsed = [];

	foreach ( $headers as $header ) {
		$parts = explode( ':', $header, 2 );
		if ( count( $parts ) === 2 ) {
			$parsed[ strtolower( trim( $parts[0] ) ) ] = trim( $parts[1] );
		}
	}

    return $parsed;
}

function http_get( string $url, array $headers = [] ) {
	$response = [];

	$options = [
		'http' => [
			'method' => 'GET',
			'header' => ''
		]
	];
	foreach ( $headers as $header_name => $header_value ) {
		$options['http']['header'] .= "$header_name: $header_value\r\n";
	}

    $context = stream_context_create( $options );
	$body = file_get_contents( $url, false, $context );

	$response['error'] = false;
	$response['headers'] = http_parse_headers( $http_response_header );
	$response['body'] = $body;

	if ( $body === false ) {
		$response['error'] = true;
		return $response;
	}

	return $response;
}

function http_post( string $url, array $headers = [], array $data = [] ) {
	$response = [];

	$options = [
		'http' => [
			'method' => 'POST',
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query( $data ),
		],
	];
	foreach ( $headers as $header_name => $header_value ) {
		$options['http']['header'] .= "$header_name: $header_value\r\n";
	}

    $context = stream_context_create( $options );
	$body = file_get_contents( $url, false, $context );

	$response['error'] = false;
	$response['headers'] = http_parse_headers( $http_response_header );
	$response['body'] = $body;

	if ( $body === false ) {
		$response['error'] = true;
		return $response;
	}

	return $response;
}
