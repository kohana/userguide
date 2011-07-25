<?php
/**
 * Tests for the Kodoc userguide class
 * @group userguide
 */
class KodocTest extends Kohana_Unittest_TestCase
{
	const EXPECT_EXCEPTION = -1;

	/**
	 * Provides test data for test_transparent_classes
	 * @return array
	 */
	public function provider_transparent_classes()
	{
		return array(
			//Kohana_Core is a special case
			array('kohana','kohana_core',null),
			array('Controller_Template','Kohana_Controller_Template',null),
			array('controller_template','kohana_controller_template',array('kohana_controller_template'=>'kohana_controller_template',
			                                                               'controller_template'=>'controller_template')),
		array(false,'kohana_controller_template',array('kohana_controller_template'=>'kohana_controller_template')),
		array(false,'Controller_Template',null),
		array(self::EXPECT_EXCEPTION,'Kohana_Controller_Template',array('kohana_controller_template'=>'kohana_controller_template'))
		);
	}

	/**
	 * Tests Kodoc::is_transparent
	 *
	 * Checks that a selection of transparent and non-transparent classes give expected results
	 *
	 * @group userguide.feat3529-configurable-transparent-classes
	 * @dataProvider provider_transparent_classes
	 * @param mixed $expected
	 * @param string $class
	 * @param array $classes
	 */
	public function test_transparent_classes($expected, $class, $classes)
	{
		try
		{
			$result = Kodoc::is_transparent($class, $classes);

			if ($expected == self::EXPECT_EXCEPTION)
			{
				$this->fail('Kodoc::is_transparent did not throw an expected InvalidArgumentException');
			} 
			else 
			{
				$this->assertSame($expected,$result);
			}
		} catch (InvalidArgumentException $e) {
			if ($expected != self::EXPECT_EXCEPTION)
			{
				// We weren't expecting that
				$this->fail('Kodoc::is_transparent threw unexpected InvalidArgumentException with message '.$e->getMessage());
			}
		}

	}
}