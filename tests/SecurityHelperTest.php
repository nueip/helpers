<?php

namespace nueip\helpers\tests;

use nueip\helpers\SecurityHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test for SecurityHelperTest - PHP Unit
 */
class SecurityHelperTest extends TestCase
{
    /**
     * test DeepHtmlspecialchars
     */
    public function testDeepHtmlspecialchars()
    {
        $templete = [
            'test1' => [45678, -87654, 0.6524],
            'test2' => 'test2 <script> alert("Error"); </script>',
            'test3' => [
                null,
                'test3 <script> alert("Error"); </script>',
            ],
            'test4' => (object) [
                'test4 <script> alert("Error"); </script>',
                'key' => 'test4 <script> alert("Error"); </script>',
            ],
            'test5' => [
                true, false,
                1, 0,
            ],
        ];

        $expected = [
            'test1' => [
                0 => '45678',
                1 => '-87654',
                2 => '0.6524'
            ],
            'test2' => 'test2 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
            'test3' => [
                0 => '',
                1 => 'test3 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
            ],
            'test4' => (object) [
                0 => 'test4 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
                'key' => 'test4 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
            ],
            'test5' => [
                0 => '1',
                1 => '',
                2 => '1',
                3 => '0',
            ],
        ];

        $output = SecurityHelper::deepHtmlspecialchars($templete);

        $this->assertEquals($expected, $output);
    }
}
