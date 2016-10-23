<?php

if ( !defined( 'ABSPATH' ) )
	exit;

class daytip_monthday {

	private $month;
	private $day;

	private function __construct( int $month, int $day ) {
		$this->month = $month;
		$this->day = $day;
	}

	public function title(): string {
		return sprintf( '%02d-%02d', $this->month, $this->day );
	}

	public function next(): self {
		if ( $this->day === self::limit( $this->month ) )
			return new self( $this->month % 12 + 1, 1 );
		return new self( $this->month, $this->day + 1 );
	}

	public static function parse( string $title ) {
		if ( preg_match( '/^(\d{2})-(\d{2})$/', $title, $matches ) !== 1 )
			return NULL;
		$m = intval( $matches[1] );
		$d = intval( $matches[2] );
		if ( $m < 1 || $m > 12 || $d < 1 || $d > self::limit( $m ) )
			return NULL;
		return new self( $m, $d );
	}

	public static function today(): self {
		$dt = new DateTime();
		$dt->setTimestamp( current_time( 'timestamp' ) );
		return self::parse( $dt->format( 'm-d' ) );
	}

	public function comp( self $obj ): int {
		if ( $this->month !== $obj->month )
			return $this->month <=> $obj->month;
		return $this->day <=> $obj->day;
	}

	public function abs(): int {
		$abs = $this->day;
		for ( $m = $this->month - 1; $m >= 1; $m-- )
			$abs += self::limit( $m );
		return $abs;
	}

	private static function limit( int $m ): int {
		switch ( $m ) {
			case  2:
				return 29;
			case  4:
			case  6:
			case  9:
			case 11:
				return 30;
			case  1:
			case  3:
			case  5:
			case  7:
			case  8:
			case 10:
			case 12:
				return 31;
			default: return 0;
		}
	}
}
