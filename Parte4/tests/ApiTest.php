<?php
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase {
    public function testApiRetornaListaEmJson() {
        // Simulamos uma requisição para a nossa nova api.php
        $url = "http://localhost/api.php?action=list&quot;;
        $content = file_get_contents($url);

        // Verificamos se o que recebemos é um JSON válido
        $data = json_decode($content, true);
        $this->assertIsArray($data);
    }
}
