<?php

class DateTimeView {


	/**
	 * This method show current date and time
	 * @return string, which contains current time
     */
	public function show() {

		$timeString = date('l') . ', the ' . date('jS \of F Y') . ', The time is ' . date('H:i:s');

		return '<p>' . $timeString . '</p>';
	}
}