<?php

namespace blueprints\src\WordPress\Blueprints\Resources;

class PHPStreamResource implements Resource {
	public function __construct(
		protected $stream
	) {
	}

	public function saveTo( string $path ): void {
		while ( $chunk = $this->read( 8192 ) ) {
			file_put_contents( $path, $chunk, FILE_APPEND );
		}
	}

	public function read( int $bytes ): string|bool {
		return fread( $this->stream, $bytes );
	}

	public function close(): void {
		fclose( $this->stream );
	}
}
