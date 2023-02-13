<?php declare(strict_types=1);
// phpcs:ignoreFile
/**
 * Testing content replacement.
 *
 * @package WordPress.
 */

use PHPUnit\Framework\TestCase;
use bulk_ai\Bulk_AI_Content;

require dirname( __FILE__ ) . '/../includes/class-bulk-ai-content.php';

/**
 * Replacing node data in template sections.
 */
final class ReplaceNodeDataInSectionsTest extends TestCase {

	private $bulkAiContent;

	/**
	 * Getting everything ready.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->bulkAiContent = new Bulk_AI_Content();
	}

	/**
	 * Should return section data as is.
	 */
	public function testEmptyNodeArray(): void {

		$node_data = array();
		$sections  = array(
			array( 'name' => 'intro', 'content' => 'Esta es la introducción.' ),
			array( 'name' => 'sección_1', 'content' => 'Esta es la sección uno.' )
		);

		$data = $this->bulkAiContent->replace_node_data_in_sections( $node_data, $sections );

		$this->assertSame( $data, $sections );

	}

	/**
	 * Should return section data as is.
	 */
	public function testEmptySectionsArray(): void {

		$node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);
		$sections = array();

		$data = $this->bulkAiContent->replace_node_data_in_sections( $node_data, $sections );

		$this->assertEmpty( $data );

	}

	/**
	 * There is no node data in sections.
	 */
	public function testNoNodeDataInSections(): void {

		$node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);

		$sections  = array(
			array( 'name' => 'intro', 'content' => 'Esta es la introducción.' ),
			array( 'name' => 'sección_1', 'content' => 'Esta es la sección uno.' )
		);

		$data = $this->bulkAiContent->replace_node_data_in_sections( $node_data, $sections );

		$this->assertSame( $data, $sections );

	}

	/**
	 * All section items contain node data.
	 */
	public function testAllSectionItemsContainData(): void {

		$node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);

		$sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Esta es la introducción para la ciudad de {bai_ciudad}, y su platillo {bai_comida}'
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Esta es la sección uno. El mejor platillo típico en {bai_ciudad} es {bai_comida}'
			)
		);

		$expected_sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Esta es la introducción para la ciudad de Barcelona, y su platillo Pan con tomate'
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Esta es la sección uno. El mejor platillo típico en Barcelona es Pan con tomate'
			)
		);

		$data = $this->bulkAiContent->replace_node_data_in_sections( $node_data, $sections );

		$this->assertSame( $data, $expected_sections );

	}

	/**
	 * Some section items contain node data.
	 */
	public function testSomeSectionItemsContainData(): void {

		$node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Madrid',
			'bai_comida'   => 'Paella',
		);

		$sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Esta es la introducción para este artículo',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Esta es la sección uno. El mejor platillo típico en {bai_ciudad} es {bai_comida}',
			),
			array(
				'name' => 'sección_2',
				'content' => '{bai_comida} es el mejor platillo de {bai_ciudad}'
			),
		);

		$expected_sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Esta es la introducción para este artículo',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Esta es la sección uno. El mejor platillo típico en Madrid es Paella'
			),
			array(
				'name' => 'sección_2',
				'content' => 'Paella es el mejor platillo de Madrid'
			),
		);

		$data = $this->bulkAiContent->replace_node_data_in_sections( $node_data, $sections );

		$this->assertSame( $data, $expected_sections );

	}

	/**
	 * Spaces in sections.
	 */
	public function testSpacesInSections(): void {

		$node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Madrid',
			'bai_comida'   => 'Paella',
		);

		$sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Esta es la introducción para este artículo',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Esta es la sección uno. El mejor platillo típico en { bai_ciudad} es {bai_comida}',
			),
			array(
				'name' => 'sección_2',
				'content' => '{bai_comida } es el mejor platillo de {bai_ciudad}'
			),
		);

		$expected_sections  = array(
			array( 
				'name' => 'intro', 
				'content' => 'Esta es la introducción para este artículo',
			),
			array( 
				'name' => 'sección_1', 
				'content' => 'Esta es la sección uno. El mejor platillo típico en { bai_ciudad} es Paella'
			),
			array(
				'name' => 'sección_2',
				'content' => '{bai_comida } es el mejor platillo de Madrid'
			),
		);

		$data = $this->bulkAiContent->replace_node_data_in_sections( $node_data, $sections );

		$this->assertSame( $data, $expected_sections );		

	}

	/**
	 * Reloading.
	 */
	protected function tearDown(): void {
		parent::tearDown();
	}

}
