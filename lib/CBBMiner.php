<?php

	namespace CBBMiner;
	require "Invoker.php";

	class CBBMiner {
		
		private $help = "Usage: \n-h this help\n--getValues [dd/mm/yyyy] [dd/mmmm/yyyy] [CodeSeriesValue1] [CodeSeriesValue2] ...";
		private $invoker;

		public function __construct($args) {
			$this->invoker = new Invoker();

			switch ($args[1]) {
				case '-h' :
					echo $this->help;
					break;
				case '--getValues':
					$this->invoker->getValues($args);
					break;
				default:
					echo 'Wrong argument. Try -h';
				
			}
		}
	}
