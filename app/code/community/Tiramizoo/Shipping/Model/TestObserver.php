<?php

class Tiramizoo_Shipping_Model_TestObserver
{
	public function convertProductDimensions($observer)
	{
		$event = $observer->getEvent();
		$weight = $event->getWeight();
		$width = $event->getWidth();
		$height = $event->getHeight();
		$length = $event->getLength();

		// Your code
		// $weight = $weight / 10;
		// $width = $width / 100;
		// $height = $height * 10;
		// $length = $length * 100;

		// Dimensions in cm
		$event->setWeight($weight);
		$event->setWidth($width);
		$event->setHeight($height);
		$event->setLength($length);
	}

	public function convertCategoryDimensions($observer)
	{
		$event = $observer->getEvent();
		$weight = $event->getWeight();
		$width = $event->getWidth();
		$height = $event->getHeight();
		$length = $event->getLength();

		// Your code
		// $weight = $weight / 10;
		// $width = $width / 100;
		// $height = $height * 10;
		// $length = $length * 100;

		// Dimensions in cm
		$event->setWeight($weight);
		$event->setWidth($width);
		$event->setHeight($height);
		$event->setLength($length);
	}

	public function ratesPrice($observer)
	{
		$event = $observer->getEvent();
		$immediatePrice = $event->getImmediate();
		$eveningPrice = $event->getEvening();

		// Your code
		// $immediatePrice = $immediatePrice * 15;
		// $eveningPrice = $eveningPrice * 12;

		$event->setImmediate($immediatePrice);
		$event->setEvening($eveningPrice);
	}
}