<?php
namespace App\Helpers;

class DateFormat
{
	static function humanTiming($time)
	{
		$time = time() - $time;
		$time = $time < 1 ? 1 : $time;
		$tokens = [
			31536000 => 'año',
			2592000 => 'mes',
			604800 => 'semana',
			86400 => 'dia',
			3600 => 'hora',
			60 => 'minuto',
			1 => 'segundo',
		];

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) {
				continue;
			}
			$numberOfUnits = floor($time / $unit);
			$endmMultiple =
				$numberOfUnits > 1
					? (substr($text, -1) == 's'
						? 'es'
						: 's')
					: '';

			return 'hace ' . $numberOfUnits . ' ' . $text . $endmMultiple;
		}
	}
}
