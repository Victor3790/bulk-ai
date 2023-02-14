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

/**
 * Replacing node data in template content.
 */
final class ReplaceNodeDataInContentTest extends TestCase {
    
    private $bulkAiContent;

	/**
	 * Getting everything ready.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->bulkAiContent = new Bulk_AI_Content();
	}


    /**
     * Test if the node array is empty.
     */
    public function testEmptyNodeArray(): void {

        $node_data = array();
        $content   = 'Aquí habrá algo de contenido';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $content );

    }

    /**
     * Test if the content string is empty.
     */
    public function testEmptyContentString(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);
        $content = '';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertEmpty( $data );

    }

    /**
     * No node data is present in content.
     */
    public function testNoNodeDataInContent(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);
        $content = 'Aquí habrá algo de contenido. {sección_1}';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $content );

    }

    /**
     * All node data is present in conent.
     */
    public function testAllNodeDataInContent(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);
        $content = 'El platillo más famoso en {bai_ciudad} es {bai_comida}.';
        $expected_content = 'El platillo más famoso en Barcelona es Pan con tomate.';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $expected_content );

    }

    /**
     * Some node data is present in conent.
     */
    public function testSomeNodeDataInContent(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);
        $content = 'El contenido es acerca de {bai_ciudad}. {seccion_uno}';
        $expected_content = 'El contenido es acerca de Barcelona. {seccion_uno}';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $expected_content );

    }

    /**
     * Spaces in conent.
     */
    public function testSpacesInContent(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Barcelona',
			'bai_comida'   => 'Pan con tomate',
		);
        $content = '{bai_ciudad }. El contenido es acerca de { bai_ciudad} y su platillo {bai_comida}. {seccion_uno}';
        $expected_content = '{bai_ciudad }. El contenido es acerca de { bai_ciudad} y su platillo Pan con tomate. {seccion_uno}';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $expected_content );

    }

    /**
     * Breaks in content
     */
    public function testBreaksInContent(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Madrid',
			'bai_comida'   => 'Paella',
		);
        $content = '<h1>Artículo sobre {bai_comida}</h1>
            {intro}';

        $expected_content = '<h1>Artículo sobre Paella</h1>
            {intro}';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $expected_content );

    }

    /**
     * HTML in content.
     */
    public function testHTMLInContent(): void {

        $node_data = array(
			'bai_template' => 'comida_tipica',
			'bai_ciudad'   => 'Madrid',
			'bai_comida'   => 'Paella',
		);
        $content = '<h1>Artículo sobre {bai_comida}, el mejor platillo de {bai_ciudad}</h1>
            {intro}
            
            {bai_comida} es el platillo más delicioso de {bai_ciudad}.
            
            Si tienes la oportunidad de visitar {bai_ciudad}, no olvides acudir a algún restaurante para probar {bai_comida}.';

        $expected_content = '<h1>Artículo sobre Paella, el mejor platillo de Madrid</h1>
            {intro}
            
            Paella es el platillo más delicioso de Madrid.
            
            Si tienes la oportunidad de visitar Madrid, no olvides acudir a algún restaurante para probar Paella.';

        $data = $this->bulkAiContent->replace_node_data_in_content( $node_data, $content );

        $this->assertSame( $data, $expected_content );

    }

    /**
	 * Reloading.
	 */
	protected function tearDown(): void {
		parent::tearDown();
	}

}
