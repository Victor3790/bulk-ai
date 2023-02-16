<?php declare(strict_types=1);
// phpcs:ignoreFile
/**
 * Testing content replacement.
 *
 * @package WordPress.
 */

use PHPUnit\Framework\TestCase;
use bulk_ai\Bulk_AI_Content;

if( ! class_exists( 'bulk_ai\Bulk_AI_Content' ) ) {

    require dirname( __FILE__ ) . '/../includes/class-bulk-ai-content.php';

}

if( ! class_exists( 'bulk_ai\Open_AI_Api_Connection' ) ) {

    require dirname( __FILE__ ) . '/../includes/class-open-ai-api-connection.php';

}

/**
 * Replacing node data in template content.
 */
final class GetSectionDataTest extends TestCase {

    private $bulkAiContent;

	/**
	 * Getting everything ready.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->bulkAiContent = new Bulk_AI_Content();
	}

    /**
     * Test for empty sections array.
     */
    public function testEmptySectionsArray(): void {

        $stub = $this->createStub( bulk_ai\Open_AI_Api_Connection::class );
        
        $data = $this->bulkAiContent->get_section_data( $stub, array() );

        $this->assertEmpty( $data );

    }

    /**
     * No sections to substitute.
     */
    public function testNoSectionsToSubstitute(): void {

        $stub = $this->createStub( bulk_ai\Open_AI_Api_Connection::class );
        $stub->method('get_completion')->willReturn('Open AI data.');

        $sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Intro prompt',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Seccion 1 prompt',
			),
			array(
				'name' => 'lista_1',
				'content' => 'Lista 1 prompt'
			),
		);

        $expected_sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Open AI data.',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Open AI data.',
			),
			array(
				'name' => 'lista_1',
				'content' => 'Open AI data.'
			),
		);

        $result_sections = $this->bulkAiContent->get_section_data( $stub, $sections );

        $this->assertSame( $result_sections, $expected_sections );

    }

    /**
     * Some sections to substitute.
     */
    public function testSomeSectionsToSubstitute(): void {

        $stub = $this->createStub( bulk_ai\Open_AI_Api_Connection::class );
        $stub->method('get_completion')->willReturn('Open AI data.');

        $sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Intro prompt',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Seccion 1 prompt',
			),
			array(
				'name' => 'lista_1',
				'content' => '{sección_1}. Lista 1 prompt'
			),
            array(
				'name' => 'seccion_2',
				'content' => '{lista_1}. Seccion 2 prompt'
			),
		);

        $expected_sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Open AI data.',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Open AI data.',
			),
			array(
				'name' => 'lista_1',
				'content' => 'Open AI data.'
			),
            array(
				'name' => 'seccion_2',
				'content' => 'Open AI data.'
			),
		);

        $result_sections = $this->bulkAiContent->get_section_data( $stub, $sections );

        $this->assertSame( $result_sections, $expected_sections );        

    }

    /**
     * All sections to substitute.
     */
    public function testAllSectionsToSubstitute(): void {

        $stub = $this->createStub( bulk_ai\Open_AI_Api_Connection::class );
        $stub->method('get_completion')->willReturn('Open AI data.');

        $sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Intro prompt',
			),
			array( 
				'name' => 'sección_1', 
				'content' => '{intro}. Seccion 1 prompt',
			),
			array(
				'name' => 'lista_1',
				'content' => '{sección_1}. Lista 1 prompt'
			),
            array(
				'name' => 'seccion_2',
				'content' => '{lista_1}. Seccion 2 prompt'
			),
		);

        $expected_sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Open AI data.',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Open AI data.',
			),
			array(
				'name' => 'lista_1',
				'content' => 'Open AI data.'
			),
            array(
				'name' => 'seccion_2',
				'content' => 'Open AI data.'
			),
		);

        $result_sections = $this->bulkAiContent->get_section_data( $stub, $sections );

        $this->assertSame( $result_sections, $expected_sections );        

    }

    /**
     * Wrong section order.
     */
    public function testWrongSectionOrder(): void {

        $stub = $this->createStub( bulk_ai\Open_AI_Api_Connection::class );
        $stub->method('get_completion')->willReturn('Open AI data.');

        $sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Intro prompt',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Seccion 1 prompt',
			),
			array(
				'name' => 'lista_1',
				'content' => '{sección_2}. Lista 1 prompt'//sección_2 should be before.
			),
            array(
				'name' => 'seccion_2',
				'content' => '{lista_1}. Seccion 2 prompt'
			),
		);

        $this->expectException( \Exception::class );
        $this->expectExceptionCode( 100 );

        $result_sections = $this->bulkAiContent->get_section_data( $stub, $sections );

    }

    /**
     * Wrong section name.
     */
    public function testWrongSectionName(): void {

        $stub = $this->createStub( bulk_ai\Open_AI_Api_Connection::class );
        $stub->method('get_completion')->willReturn('Open AI data.');

        $sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Intro prompt',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Seccion 1 prompt',
			),
			array(
				'name' => 'lista_1',
				'content' => '{sección_1}. Lista 1 prompt'
			),
            array(
				'name' => 'seccion_2',
				'content' => '{lista_10}. Seccion 2 prompt'//lista_10 does not exist.
			),
		);

        $this->expectException( \Exception::class );
        $this->expectExceptionCode( 100 );

        $result_sections = $this->bulkAiContent->get_section_data( $stub, $sections );

    }
}
