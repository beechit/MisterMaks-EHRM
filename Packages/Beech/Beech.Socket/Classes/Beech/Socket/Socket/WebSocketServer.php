<?php
namespace Beech\Socket\Socket;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
 *                                                                        */

/**
 * A web socket server which supports features defined in RFC 6455
 */
use TYPO3\Flow\Http\Headers;
use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Http\Response;
use TYPO3\Flow\Http\Uri;
use TYPO3\Flow\Annotations as Flow;

class WebSocketServer extends Server {

	/**
	 * @see http://tools.ietf.org/html/rfc6455#section-1.3
	 * @const string
	 */
	const HANDSHAKE_GUID = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $logger;

	/**
	 * Handles the initiation of a new connection by creating a new Connection
	 * object and sending it through a "connection" event.
	 *
	 * @param resource $socketStream Stream of the socket which just connected
	 * @return void
	 */
	public function handleConnection($socketStream) {
		stream_set_blocking($socketStream, 0);
		$connection = new Connection($socketStream, $this->loop);
		$connection->once('data', function($data) use($connection) {
			$request = self::createRequestFromRaw($data);
			$this->handshake($connection, $request);
		});
		$this->emit('connection', $connection);
	}

	/**
	 * Do the handshaking between client and server
	 *
	 * @param Connection $connection The connection to use for the handshake
	 * @param \TYPO3\Flow\Http\Request $request The HTTP request received from the client
	 * @return boolean
	 */
	protected function handshake(Connection $connection, Request $request) {
		if (!$request->hasHeader('Sec-WebSocket-Version')) {
			$this->logger->log('WebSocket handshake failed: the client does not support WebSocket', LOG_INFO);
		}

		$version = (integer)$request->getHeader('Sec-WebSocket-Version');
		if ($version !== 13) {
			$this->logger->log(sprintf('WebSocket hanshake failed: version %s is not supported by this library', $version), LOG_DEBUG);
		}

		$acceptKey = base64_encode(sha1($request->getHeader('Sec-WebSocket-Key') . self::HANDSHAKE_GUID, TRUE));

		$content = json_encode(array('notifications' => array(array('message' => 'Welcome!'))));

		$response = new Response();
		$response->setStatus(101);
		$response->getHeaders()->remove('Content-Type');
		$response->setHeader('Upgrade', 'websocket');
		$response->setHeader('Connection', 'Upgrade');
		$response->setHeader('Sec-WebSocket-Accept', $acceptKey);
		$response->setContent(self::encode($content));

		$connection->write($this->getAsRaw($response));

		$this->logger->log(sprintf('WebSocket handshake with "%s" was successful', $request->getHeader('Origin')), LOG_DEBUG);
		$this->emit('handshake', $connection);
		return TRUE;
	}

	/**
	 * Encode a text for sending to clients via ws://
	 *
	 * @param string $text The text to encode
	 * @return string The encoded text
	 */
	public static function encode($text) {
		// 0x1 text frame (FIN + opcode)
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);

		if ($length <= 125) {
			$header = pack('CC', $b1, $length);
		} elseif ($length > 125 && $length < 65536) {
			$header = pack('CCn', $b1, 126, $length);
		} elseif ($length >= 65536) {
			$header = pack('CCN', $b1, 127, $length);
		}

		return $header . $text;
	}

	/**
	 * Unmask a received payload
	 *
	 * @param string $payload The payload to unmask
	 * @return string Unmasked payload
	 */
	public static function unmask($payload) {
		$length = ord($payload[1]) & 127;

		if ($length == 126) {
			$masks = substr($payload, 4, 4);
			$data = substr($payload, 8);
		} elseif ($length == 127) {
			$masks = substr($payload, 10, 4);
			$data = substr($payload, 14);
		} else {
			$masks = substr($payload, 2, 4);
			$data = substr($payload, 6);
		}

		$text = '';
		for ($i = 0; $i < strlen($data); ++$i) {
			$text .= $data[$i] ^ $masks[$i % 4];
		}
		return $text;
	}

	/**
	 * Creates a request from the given raw, that is plain text, HTTP request.
	 *
	 * @param string $rawRequest
	 * @return \TYPO3\Flow\Http\Request
	 * @throws \InvalidArgumentException
	 * TODO: Move to TYPO3\Flow\Http\Request once it is stable
	 */
	static public function createRequestFromRaw($rawRequest) {
		$lines = explode(chr(10), $rawRequest);
		$firstLine = trim(array_shift($lines));

		list($requestMethod, $requestPath, $httpVersion) = explode(' ', $firstLine, 3);
		if ($httpVersion !== 'HTTP/1.1') {
			throw new \InvalidArgumentException(sprintf('Only supports HTTP/1.1 - request header contained "%s"', $httpVersion), 1360068531);
		}

		$uri = new Uri('ws://' . (isset($server['HTTP_HOST']) ? $server['HTTP_HOST'] : 'localhost') . $requestPath);
		$request = Request::create($uri, $requestMethod);

		$parsingHeader = TRUE;
		$contentLines = array();
		$headers = new Headers();
		foreach ($lines as $line) {
			if ($parsingHeader) {
				if (trim($line) === '') {
					$parsingHeader = FALSE;
					continue;
				}
				$fieldName = trim(substr($line, 0, strpos($line, ':')));
				$fieldValue = trim(substr($line, strlen($fieldName) + 1));
				$headers->set($fieldName, $fieldValue, FALSE);
			} else {
				$contentLines[] = $line;
			}
		}
		$content = implode(chr(10), $contentLines);

		# FIXME: Flow\Http\Request should support setHeaders(), so move that to Message
		# $request->setHeaders($headers);
		# Workaround:
		foreach ($headers->getAll() as $name => $value) {
			$request->setHeader($name, $value);
		}

		$request->setContent($content);
		return $request;
	}

	/**
	 * Return the given request as raw string output suitable to send as a HTTP
	 * response.
	 *
	 * @param \TYPO3\Flow\Http\Response $response
	 * @return string
	 * TODO: Move to TYPO3\Flow\Http\Response once it is stable
	 */
	protected function getAsRaw(Response $response) {
		$raw = 'HTTP/1.1 ' . $response->getStatus() . "\r\n";
		foreach($response->getHeaders()->getAll() as $fieldName => $fieldValues) {
			foreach ($fieldValues as $fieldValue) {
				$raw .= "$fieldName: $fieldValue\r\n";
			}
		}
		$raw .= "\r\n";
		$raw .= $response->getContent();
		return $raw;
	}

}